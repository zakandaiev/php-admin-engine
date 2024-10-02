<?php

namespace engine\database;

use \PDO;
use \PDOException;
use \Exception;

use engine\Config;
use engine\http\Request;
use engine\http\Response;
use engine\database\Database;

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
  protected $isSelect;
  protected $isPaginate = false;
  protected $isFilter = false;

  public function __construct($sql, $cache = null, $debug = null)
  {
    if (!Database::isConnected()) {
      throw new Exception('Database connection is failed.');
    }

    $is_cached = false;
    $this->isSelect = preg_match('/^\s*SELECT/mi', $sql) ? true : false;

    // TODO
    // if (isset($cache) && $cache && $this->isSelect) {
    //   $is_cached = true;
    // } else if (!isset($cache) && $this->isSelect && Module::getName() === 'frontend' && Setting::getProperty('cache_db', 'engine') == 'true') {
    //   $is_cached = true;
    // }

    $this->cache = $is_cached;
    $this->debug = isset($debug) && $debug ? true : false;

    $this->prefix = Config::getProperty('prefix', 'database');

    $replacement = '$1';
    if (!empty($this->prefix)) {
      $replacement = $this->prefix . '_$1';
    }

    $this->sql = preg_replace('/{([\w\d\-\_]+)}/miu', $replacement, $sql);

    return $this;
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

    $sql_cuted = $this->cutSelectionPartFromSQL($this->filterSql);
    $sql_cuted = preg_replace('/\s*ORDER\s+BY\s+[\w\s\@\<\>\.\,\=\-\'\"\`]+$/mi', '', $sql_cuted);

    foreach ($this->filter->get('data') as $alias => $filter_data) {
      if (is_array($filter_data['column']) || !in_array($filter_data['type'], ['checkbox', 'radio', 'select'])) {
        continue;
      }

      $filterSql = "SELECT COUNT(*) as `count`, {$filter_data['column']} as `id`, {$filter_data['column']} as `text` FROM $sql_cuted";

      $filter_data['show_all_options'] = $filter_data['show_all_options'] ?? false;

      if (!$filter_data['show_all_options'] && !empty($this->filter->get('sql'))) {
        $filterSql .= " WHERE {$this->filter->get('sql')}";

        foreach ($this->filter->get('binding') as $key => $value) {
          $this->filterBinding[$key] = $value;
        }
      }

      $filterSql .= " GROUP BY {$filter_data['column']} ORDER BY count DESC";

      $filter_options = new Statement($filterSql);

      $filter_options = $filter_options->execute($this->filterBinding)->fetchAll();

      $this->filter->setOptions($alias, $filter_options);
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
      $total_sql = "SELECT COUNT(*) as total FROM " . $this->cutSelectionPartFromSQL($this->sql);

      $total_binding = [];

      foreach ($this->binding as $key => $value) {
        if (str_contains($total_sql, ':' . $key)) {
          $total_binding[$key] = $value;
        }
      }

      $total = new Statement($total_sql);

      $this->pagination['total'] = $total->execute($total_binding)->fetchColumn();
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

    $sql_to_array = str_split($sql);

    $left_bracket_count = 0;
    $right_bracket_count = 0;
    $from_position = false;

    $sql_to_array_length = count($sql_to_array);

    for ($i = 0; $i < $sql_to_array_length; $i++) {
      if ($sql_to_array[$i] == '(') {
        $left_bracket_count += 1;
      }

      if ($sql_to_array[$i] == ')') {
        $right_bracket_count += 1;
      }

      if ($sql_to_array[$i] == 'f' || $sql_to_array[$i] == 'F') {
        $checkString = $sql_to_array[$i] . $sql_to_array[$i + 1] . $sql_to_array[$i + 2] . $sql_to_array[$i + 3];

        if ($checkString == 'from' || $checkString == 'FROM') {
          $from_position = $i;

          if ($left_bracket_count == $right_bracket_count) {
            $output = mb_substr($sql, $from_position + 4);

            break;
          }
        }
      }
    }

    return $output;

    // SLOWER
    // $output = '';

    // $paren_count = 0;
    // $from_position = false;

    // for ($i = 0; $i < strlen($sql); $i++) {
    //   if ($sql[$i] == '(') {
    //     $paren_count++;
    //   }
    // 	elseif ($sql[$i] == ')') {
    //     $paren_count--;
    //   }
    // 	elseif (!$from_position && strtolower(mb_substr($sql, $i, 4)) == 'from' && $paren_count == 0) {
    //     $from_position = $i;
    //   }
    // }

    // if ($from_position !== false) {
    //   $output = mb_substr($sql, $from_position + 4);
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

    $is_execute_success = false;

    try {
      $this->statement->execute();
      $is_execute_success = true;
    } catch (PDOException $error) {
      // TODO handle all error codes
      $error_message = $error->getMessage();

      if (preg_match("/Duplicate entry .+ for key '(.+)'/", $error_message, $matches)) {
        $error_message = str_replace($this->prefix . '_', '', $matches[1]);
        $error_message = 'duplicate.' . $error_message;
      }

      if (Request::method() === 'get') {
        if (Config::getProperty('isEnabled', 'debug')) {
          debug(__($error_message), $this->sql, $this->binding); // TODO translation
        } else {
          debug(__($error_message)); // TODO translation
        }
      } else {
        $debug_sql = Config::getProperty('isEnabled', 'debug') ? ['query' => preg_replace('/(\v|\s)+/', ' ', trim($this->sql ?? '')), 'binding' => $this->binding] : null;
        Response::answer($debug_sql, 'error', __($error_message), '409'); // TODO translation
      }
    }

    if ($is_execute_success) {
      $this->setFilterValues();
    }

    return $this;
  }

  protected function fetchCache($type, $mode)
  {
    if ($this->cache) {
      $cache_key =  $this->sql . '@' . json_encode($this->binding, JSON_UNESCAPED_UNICODE);

      $cache = Cache::get($cache_key);

      if ($cache) {
        return $cache;
      } else {
        $this->prepare();
        $this->bind();
        $this->statement->execute();

        $cache = $this->statement->{$type}($mode);

        Cache::set($cache_key, $cache);

        return $cache;
      }
    }

    return $this->statement->{$type}($mode);
  }

  public function fetchAll($mode = PDO::FETCH_OBJ)
  {
    return $this->fetchCache(__FUNCTION__, $mode);
  }

  public function fetch($mode = PDO::FETCH_OBJ)
  {
    return $this->fetchCache(__FUNCTION__, $mode);
  }

  public function fetchColumn($column = 0)
  {
    return intval($this->fetchCache(__FUNCTION__, $column));
  }

  public function insertId()
  {
    return Database::getConnection()->lastInsertId();
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

  protected function addBinding($key_or_array, $value = null)
  {
    if (empty($key_or_array)) {
      return false;
    }

    if (is_array($key_or_array)) {
      foreach ($key_or_array as $k => $v) {
        $this->binding[strval($k)] = $v;
      }
    } else {
      $this->binding[strval($key_or_array)] = $value;
    }

    return true;
  }

  protected function bind()
  {
    if (empty($this->binding)) {
      return false;
    }

    $pdo_param = PDO::PARAM_NULL;

    foreach ($this->binding as $key => $value) {
      if (is_bool($value)) $pdo_param = PDO::PARAM_BOOL;
      if (is_int($value)) $pdo_param = PDO::PARAM_INT;
      if (is_string($value)) $pdo_param = PDO::PARAM_STR;

      if (is_array($value) || is_object($value)) {
        $pdo_param = PDO::PARAM_STR;
        $value = json_encode($value, JSON_UNESCAPED_UNICODE);
      }

      $this->statement->bindValue(':' . $key, $value, $pdo_param);
    }

    return true;
  }
}
