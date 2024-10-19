<?php

namespace engine\database;

use \PDO;
use \PDOException;
use \Exception;

use engine\Config;
use engine\database\Database;
use engine\database\Filter;
use engine\database\Pagination;
use engine\http\Request;
use engine\util\Cache;

class Query
{
  protected $sql;
  protected $cache;
  protected $debug;
  protected $prefix;
  protected $statement;
  protected $binding = [];
  protected $filter;
  protected $filterSql;
  protected $filterBinding = [];
  protected $pagination = [];
  protected $isFilter = false;
  protected $isPaginate = false;
  protected $isSelect = false;
  protected $error = [];

  public function __construct($sql, $cache = null, $debug = null)
  {
    if (!Database::isConnected()) {
      throw new Exception('Database connection is failed.');
    }

    $this->isSelect = preg_match('/^\s*SELECT/mi', $sql) ? true : false;
    $this->cache = ($cache === true && $this->isSelect);
    $this->debug = isset($debug) && $debug ? true : false;
    $this->prefix = Config::getProperty('prefix', 'database');

    $replacement = '$1';
    if (!empty($this->prefix)) {
      $replacement = $this->prefix . '_$1';
    }

    $this->sql = preg_replace('/{([\w\d\-\_]+)}/miu', $replacement, $sql);

    return $this;
  }

  public function hasError()
  {
    return isset($this->error['message']);
  }

  public function getError($key = null)
  {
    return isset($key) ? @$this->error[$key] : $this->error;
  }

  public function filter($name, $mixed = null)
  {
    if (!$this->isSelect) {
      return $this;
    }

    $mixed = isset($mixed) ? $mixed : true;

    $this->isFilter = false;
    $this->filterSql = $this->sql;
    $this->filterBinding = [];

    if ($mixed === true) {
      $this->isFilter = true;
    } else if (is_array($mixed) && !empty($mixed)) {
      foreach ($mixed as $alias => $options) {
        $this->filter->setOptions($alias, $options);
      }
    }

    $this->filter = new Filter($name);

    if (empty($this->filter->get('sql')) && empty($this->filter->get('order'))) {
      return $this;
    }

    $sql = !empty($this->filter->get('sql')) ? "WHERE {$this->filter->get('sql')}" : '';
    $sql = "SELECT * FROM ({$this->filterSql}) t_filter $sql";

    foreach ($this->filter->get('binding') as $key => $value) {
      $this->filterBinding[$key] = $key;
      $this->addBinding($key, $value);
    }

    if (!empty($this->filter->get('order'))) {
      $sql .= " ORDER BY {$this->filter->get('order')}";
    }

    $this->sql = $sql;

    return $this;
  }

  protected function setFilterValues()
  {
    if (!$this->isPaginate || empty($this->filterSql) || empty($this->filter->get('data')) || preg_match('/GROUP\s+BY/mi',  $this->filterSql)) {
      return false;
    }

    $sqlCuted = $this->cutSelectionPartFromSQL($this->filterSql);
    $sqlCuted = preg_replace('/\s*ORDER\s+BY\s+[\w\s\@\<\>\.\,\=\-\'\"\`]+$/mi', '', $sqlCuted);

    foreach ($this->filter->get('data') as $alias => $filterData) {
      if (is_array($filterData['column']) || !in_array($filterData['type'], ['checkbox', 'radio', 'select'])) {
        continue;
      }

      $filterSql = "SELECT COUNT(*) as `count`, {$filterData['column']} as `id`, {$filterData['column']} as `text` FROM $sqlCuted";

      $filterData['show_all_options'] = $filterData['show_all_options'] ?? false;

      if (!$filterData['show_all_options'] && !empty($this->filter->get('sql'))) {
        $filterSql .= " WHERE {$this->filter->get('sql')}";

        foreach ($this->filter->get('binding') as $key => $value) {
          $this->filterBinding[$key] = $value;
        }
      }

      $filterSql .= " GROUP BY {$filterData['column']} ORDER BY count DESC";

      $filterOptions = new Query($filterSql);

      $filterOptions = $filterOptions->execute($this->filterBinding)->fetchAll();

      $this->filter->setOptions($alias, $filterOptions);
    }

    return true;
  }

  public function paginate($total = null, $options = [])
  {
    if (!$this->isSelect) {
      return $this;
    }

    if (isset($total)) {
      $this->pagination['total'] = $total;
    }

    foreach ($options as $key => $option) {
      $this->pagination[$key] = $option;
    }

    $this->isPaginate = true;

    return $this;
  }

  protected function initializePagination()
  {
    if (!$this->isPaginate) {
      return false;
    }

    $this->sql = preg_replace('/\s+(LIMIT|OFFSET)[\w\s\@\<\>\.\,\=\-\'\"\`]+$/mi', ' ', $this->sql);

    if (!isset($this->pagination['total'])) {
      // $total = "SELECT COUNT(*) FROM ({$this->sql}) as total";
      $totalSql = "SELECT COUNT(*) as total FROM " . $this->cutSelectionPartFromSQL($this->sql);

      $totalBinding = [];
      foreach ($this->binding as $key => $value) {
        if (str_contains($totalSql, ':' . $key)) {
          $totalBinding[$key] = $value;
        }
      }

      $total = new Query($totalSql);

      $this->pagination['total'] = $total->execute($totalBinding)->fetchColumn();
    }

    $pagination = new Pagination($this->pagination['total'], $this->pagination);

    $this->sql = rtrim($this->sql, ';') . ' LIMIT :limit OFFSET :offset';

    $this->addBinding('limit', $pagination->get('limit'));
    $this->addBinding('offset', $pagination->get('offset'));

    return true;
  }

  protected function cutSelectionPartFromSQL($sql)
  {
    $output = '';

    $sqlToArray = str_split($sql);

    $leftBracketCount = 0;
    $rightBracketCount = 0;
    $fromPosition = false;

    $sqlToArrayLength = count($sqlToArray);

    for ($i = 0; $i < $sqlToArrayLength; $i++) {
      if ($sqlToArray[$i] == '(') {
        $leftBracketCount += 1;
      }

      if ($sqlToArray[$i] == ')') {
        $rightBracketCount += 1;
      }

      if ($sqlToArray[$i] == 'f' || $sqlToArray[$i] == 'F') {
        $checkString = $sqlToArray[$i] . $sqlToArray[$i + 1] . $sqlToArray[$i + 2] . $sqlToArray[$i + 3];

        if ($checkString == 'from' || $checkString == 'FROM') {
          $fromPosition = $i;

          if ($leftBracketCount == $rightBracketCount) {
            $output = mb_substr($sql, $fromPosition + 4);

            break;
          }
        }
      }
    }

    return $output;

    // SLOWER
    // $output = '';

    // $paren_count = 0;
    // $fromPosition = false;

    // for ($i = 0; $i < strlen($sql); $i++) {
    //   if ($sql[$i] == '(') {
    //     $paren_count++;
    //   }
    // 	elseif ($sql[$i] == ')') {
    //     $paren_count--;
    //   }
    // 	elseif (!$fromPosition && strtolower(mb_substr($sql, $i, 4)) == 'from' && $paren_count == 0) {
    //     $fromPosition = $i;
    //   }
    // }

    // if ($fromPosition !== false) {
    //   $output = mb_substr($sql, $fromPosition + 4);
    // }

    // return trim($output);
  }

  public function execute($params = [])
  {
    $this->addBinding($params);

    if ($this->debug) {
      debug(trim($this->sql ?? ''), $this->binding);
    }

    if ($this->cache) {
      return $this;
    }

    $this->filterBinding = array_diff_key($this->binding, $this->filterBinding);

    $this->initializePagination();
    $this->prepare();
    $this->bind();

    try {
      $this->statement->execute();
    } catch (PDOException $error) {
      $this->error['message'] = $error->getMessage();

      if (preg_match("/duplicate.+key.+[\'\"\`](.+)[\'\"\`]/iu", $this->error['message'], $matches)) {
        $columnName = str_replace($this->prefix . '_', '', $matches[1]);
        $errorMessage = "$columnName.duplicate";

        $this->error['message'] = $errorMessage;
        $this->error['column'] = $columnName;
        $this->error['validation'] = 'duplicate';
      }

      if (Request::method() === 'get' && Config::getProperty('isEnabled', 'debug')) {
        debug($this->error, $this->sql, $this->binding);
      } else if (Config::getProperty('isEnabled', 'debug')) {
        $this->error['sql'] = $this->sql;
        $this->error['binding'] = $this->binding;
      }
    }

    // TODO
    // if (!$this->hasError()) {
    //   $this->setFilterValues();
    // }

    return $this;
  }

  public function fetchAll($mode = PDO::FETCH_OBJ)
  {
    return $this->fetchPre(__FUNCTION__, $mode);
  }

  public function fetch($mode = PDO::FETCH_OBJ)
  {
    return $this->fetchPre(__FUNCTION__, $mode);
  }

  public function fetchColumn($column = 0)
  {
    return $this->fetchPre(__FUNCTION__, $column);
  }

  public function insertId()
  {
    return !$this->hasError() ? Database::getConnection()->lastInsertId() : false;
  }

  public function rowCount()
  {
    return $this->statement->rowCount();
  }

  protected function prepare()
  {
    $this->statement = Database::getConnection()->prepare($this->sql);

    return true;
  }

  protected function addBinding($keyOrArray, $value = null)
  {
    if (empty($keyOrArray)) {
      return false;
    }

    if (is_array($keyOrArray)) {
      foreach ($keyOrArray as $k => $v) {
        $this->binding[strval($k)] = $v;
      }
    } else {
      $this->binding[strval($keyOrArray)] = $value;
    }

    return true;
  }

  protected function bind()
  {
    if (empty($this->binding)) {
      return false;
    }

    $pdoParam = PDO::PARAM_NULL;

    foreach ($this->binding as $key => $value) {
      if (is_bool($value)) {
        $pdoParam = PDO::PARAM_BOOL;
      } else if (is_int($value)) {
        $pdoParam = PDO::PARAM_INT;
      } else if (is_string($value)) {
        $pdoParam = PDO::PARAM_STR;
      } else if (is_array($value) || is_object($value)) {
        $pdoParam = PDO::PARAM_STR;
        $value = json_encode($value, JSON_UNESCAPED_UNICODE);
      }

      $this->statement->bindValue(':' . $key, $value, $pdoParam);
    }

    return true;
  }

  protected function fetchPre($type, $mode)
  {
    if ($this->hasError()) {
      return false;
    }

    if ($this->cache) {
      $cacheKey =  $this->sql . '@' . json_encode($this->binding, JSON_UNESCAPED_UNICODE);

      $cache = Cache::get($cacheKey);

      if ($cache) {
        return $cache;
      } else {
        $this->prepare();
        $this->bind();
        $this->statement->execute();

        $cache = $this->statement->{$type}($mode);

        Cache::set($cacheKey, $cache);

        return $cache;
      }
    }

    return $this->statement->{$type}($mode);
  }
}
