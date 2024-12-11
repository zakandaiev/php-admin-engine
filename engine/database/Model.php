<?php

namespace engine\database;

use engine\Config;
use engine\database\Validation;
use engine\http\Request;
use engine\util\Upload;

abstract class Model extends Validation
{
  protected $columnForeign = [];
  protected $submitMessage = [];

  protected static $instances = [];

  public function __construct()
  {
    self::$instances[get_called_class()] = $this;
  }

  public static function getInstance()
  {
    $calledClass = get_called_class();

    if (isset(self::$instances[$calledClass])) {
      return self::$instances[$calledClass];
    }

    return new $calledClass();
  }

  public function setSubmitMessage($key, $value = null)
  {
    $this->submitMessage[$key] = $value;

    return true;
  }

  public function hasSubmitMessage($key)
  {
    return isset($this->submitMessage[$key]);
  }

  public function getSubmitMessage($key = null)
  {
    return isset($key) ? @$this->submitMessage[$key] : $this->submitMessage;
  }

  public function setData($columnData = null, $columnKeysToValidate = null)
  {
    $primaryKey = $this->getPrimaryKey();
    if ($primaryKey && is_array($columnKeysToValidate)) {
      $columnKeysToValidate[] = $primaryKey;
    }

    $this->setColumnKeysToValidate($columnKeysToValidate);

    foreach ($this->getColumn() as $columnName => $column) {
      $this->formatColumnValue($columnName, $column['type'], $columnData[$columnName] ?? $column['value'] ?? null, $column);
    }

    return $this;
  }

  public function add()
  {
    return $this->processQuery(__FUNCTION__);
  }

  public function edit()
  {
    return $this->processQuery(__FUNCTION__);
  }

  public function delete()
  {
    return $this->processQuery(__FUNCTION__);
  }

  public function insertIntoTable($table, $values)
  {
    if (empty($table) || !is_array($values)) {
      return false;
    }

    $keys = array_keys($values);
    $sqlParams = '(' . implode(', ', $keys) . ') VALUES (:' . implode(', :', $keys) . ')';
    $sql = "INSERT INTO {{$table}} $sqlParams";
    $query = new Query($sql);

    $result = $query->execute($values);
    if ($query->hasError()) {
      $this->setError($query->getError());
      return false;
    }

    $insertId = $result->insertId();

    if ($insertId > 0 || $insertId !== '0') {
      return $insertId;
    }

    return $values[$this->getPrimaryKey()] ?? true;
  }

  public function updateTable($table, $values, $pkName, $pkValue)
  {
    if (empty($table) || !is_array($values) || empty($pkName)) {
      return false;
    }

    $values[$pkName] = $pkValue;
    $keys = array_keys($values);

    $sqlParams = array_reduce($keys, function ($carry, $v) {
      return ($carry ? "$carry, " : '') . "$v=:$v";
    });
    $sql = "UPDATE {{$table}} SET $sqlParams WHERE $pkName=:$pkName";
    $query = new Query($sql);

    $result = $query->execute($values);
    if ($query->hasError()) {
      $this->setError($query->getError());

      return false;
    }

    return $pkValue;
  }

  public function deleteFromTable($table, $values, $pkName, $pkValue)
  {
    if (empty($table) || !is_array($values) || empty($pkName)) {
      return false;
    }

    $values[$pkName] = $pkValue;
    $keys = array_keys($values);

    $sqlParams = array_reduce($keys, function ($carry, $v) use ($values) {
      $operand = '=';

      if (is_null($values[$v])) {
        $operand = '<=>';
      }

      return ($carry ? "$carry AND " : '') . "$v$operand:$v";
    });
    $sql = "DELETE FROM {{$table}} WHERE $sqlParams AND $pkName=:$pkName";
    $query = new Query($sql);

    $result = $query->execute($values);
    if ($query->hasError()) {
      $this->setError($query->getError());

      return false;
    }

    return $pkValue;
  }

  protected function formatColumnValue($name, $type, $value, $column)
  {
    $valueFormatted = $value === '' ? null : $value;

    if ($type === 'boolean') {
      $valueFormatted = $value === true || in_array($value, ['on', 'yes', '1', 'true']) ? true : false;
    } else if ($type === 'number') {
      $valueFormatted = !empty($value) && is_numeric($value) ? floatval($value) : null;
    } else if (in_array($type, ['date', 'datetime', 'month', 'time']) && @$column[$name]['isMultiple'] === true) {
      $valueFormatted = !empty($value) ? explode(' - ', $value) : [];
    } else if ($type === 'file') {
      $this->setColumnProperty($name, 'isUpload', false);
      $this->setColumnProperty($name, 'maxSize', $column[$name]['maxSize'] ?? Upload::getMaxSize());
      $this->setColumnProperty($name, 'extensions', $column[$name]['extensions'] ?? Upload::getExtensions());

      $files = Request::files($name);
      if (
        (isset($files['tmp_name']) && !is_array($files['tmp_name']) && !empty($files['tmp_name']))
        || (isset($files['tmp_name']) && is_array($files['tmp_name']) && !empty($files['tmp_name'][0]))
      ) {
        $this->setColumnProperty($name, 'isUpload', true);
        $this->setColumnProperty($name, 'toUpload', $files);
      }
    }

    $this->setColumnValue($name, $valueFormatted);

    return true;
  }

  protected function processQuery($action)
  {
    if (!$this->hasTable()) {
      if (Config::getProperty('isEnabled', 'debug')) {
        $this->setSubmitMessage('error', 'Model table is empty');
      }

      return false;
    }

    if (empty($this->getColumn())) {
      if (Config::getProperty('isEnabled', 'debug')) {
        $this->setSubmitMessage('error', 'Model column list is empty');
      }

      return false;
    }

    $this->modifyColumns();

    $this->validate();
    if ($this->hasError()) {
      return false;
    }

    $table = $this->getTable();
    $pkName = $this->getPrimaryKey();
    $pkValue = $this->hasItemId() ? $this->getItemId() : @$this->getColumnValue($pkName);

    $this->prepareForeignColumns();
    $this->prepareMediaColumns();

    $columnValues = [];
    foreach ($this->getColumn() as $columnName => $column) {
      if (isset($column['foreign']) || ($this->hasColumnKeysToValidate() && !$this->hasColumnKeyToValidate($columnName))) {
        continue;
      }

      $columnValues[$columnName] = @$column['value'];
    }

    if (empty($columnValues)) {
      if (Config::getProperty('isEnabled', 'debug')) {
        $this->setSubmitMessage('error', 'Model column list is invalid');
      }

      return false;
    }

    $result = false;
    if ($action === 'add') {
      $result = $this->insertIntoTable($table, $columnValues);
    } else if ($action === 'edit') {
      $result = $this->updateTable($table, $columnValues, $pkName, $pkValue);
    } else if ($action === 'delete') {
      $result = $this->deleteFromTable($table, $columnValues, $pkName, $pkValue);
    }

    if (!$result) {
      return false;
    }

    $resultForeign = $this->processForeignColumns($result);
    if (!$resultForeign) {
      return false;
    }

    $resultMedia = $this->processMediaColumns();
    if (!$resultMedia) {
      return false;
    }

    return $result;
  }

  protected function modifyColumns()
  {
    foreach ($this->getColumn() as $columnName => $column) {
      if (isset($column['unsetNull']) && $column['unsetNull'] === true && (@$column['value'] === null || @$column['value'] === '')) {
        $this->unsetColumn($columnName);
        continue;
      }

      if (!isset($column['modify']) || !isClosure($column['modify'])) {
        continue;
      }

      $modifiedValue = $column['modify'](@$column['value'], $column, $this->getColumn());

      $this->setColumnValue($columnName, $modifiedValue);
    }
  }

  protected function prepareForeignColumns()
  {
    foreach ($this->getColumn() as $columnName => $column) {
      if (!isset($column['foreign']) || empty($column['foreign']) || ($this->hasColumnKeysToValidate() && !$this->hasColumnKeyToValidate($columnName))) {
        continue;
      }

      list($foreignTable, $foreignPkName) = explode('@', $column['foreign'], 2);

      if (empty($foreignTable) || empty($foreignPkName)) {
        continue;
      }

      $this->columnForeign[$foreignTable][] = [
        'table' => $foreignTable,
        'pkName' => $foreignPkName,
        'pkValue' => null,
        'fkName' => $columnName,
        'fkValue' => @$column['value'],
        'isFkDeleteSkip' => @$column['isForeignDeleteSkip'] ?? false
      ];
    }

    return true;
  }

  protected function processForeignColumns($pkValue)
  {
    if (empty($this->columnForeign)) {
      return true;
    }

    $foreignSqlData = [];

    foreach ($this->columnForeign as $foreignTable => $foreignTableColumns) {
      $table = $foreignTable;
      $tablePkName = null;
      $tablePkValue = null;
      $keys = [];
      $keysDelete = [];
      $values = [];
      $valuesDelete = [];
      $isValuesArray = false;

      if (count($foreignTableColumns) === 1 && is_array(@$foreignTableColumns[0]['fkValue'])) {
        if (empty($foreignTableColumns[0]['pkName']) || empty($foreignTableColumns[0]['fkName'])) {
          continue;
        }

        $tablePkName = $foreignTableColumns[0]['pkName'];
        $tablePkValue = @$foreignTableColumns[0]['pkValue'];

        $fkName = $foreignTableColumns[0]['fkName'];
        $fkValue = $foreignTableColumns[0]['fkValue'];

        $keys = $fkName;
        $values = $fkValue;

        $isValuesArray = true;
      } else {
        foreach ($foreignTableColumns as $foreignTableColumn) {
          if (empty($foreignTableColumn['pkName']) || empty($foreignTableColumn['fkName'])) {
            continue;
          }

          $tablePkName = $foreignTableColumn['pkName'];
          $tablePkValue = @$foreignTableColumn['pkValue'];

          $fkName = $foreignTableColumn['fkName'];
          $fkValue = @$foreignTableColumn['fkValue'];

          $keys[] = $tablePkName;
          $keys[] = $fkName;
          if (!$foreignTableColumn['isFkDeleteSkip']) {
            $keysDelete[] = $fkName;
          }

          $values[$tablePkName] = $pkValue;
          $values[$fkName] = $fkValue;
          if (!$foreignTableColumn['isFkDeleteSkip']) {
            $valuesDelete[$fkName] = $fkValue;
          }
        }
      }

      $foreignSqlData[] = [
        'table' => $table,
        'tablePkName' => $tablePkName,
        'tablePkValue' => $tablePkValue ?? $pkValue,
        'keys' => is_array($keys) ? array_unique($keys) : $keys,
        'keysDelete' => $keysDelete,
        'values' => $values,
        'valuesDelete' => $valuesDelete,
        'isValuesArray' => $isValuesArray,
      ];
    }

    foreach ($foreignSqlData as $foreignData) {
      $table = $foreignData['table'];
      $tablePkName = $foreignData['tablePkName'];
      $tablePkValue = $foreignData['tablePkValue'];
      $keys = $foreignData['keys'];
      $keysDelete = $foreignData['keysDelete'];
      $values = $foreignData['values'];
      $valuesDelete = $foreignData['valuesDelete'];
      $isValuesArray = $foreignData['isValuesArray'];

      $this->deleteFromTable($table, $valuesDelete, $tablePkName, $tablePkValue);

      if ($isValuesArray === true && !empty($values)) {
        foreach ($values as $fdValue) {
          $fdValues = [$tablePkName => $tablePkValue, $keys => $fdValue];

          $this->insertIntoTable($table, $fdValues);
        }
      }

      if ($isValuesArray === true) {
        continue;
      }

      $this->insertIntoTable($table, $values);
    }

    return true;
  }

  protected function prepareMediaColumns()
  {
    foreach ($this->getColumn() as $columnName => $column) {
      if (@$column['isUpload'] !== true) {
        continue;
      }

      if (@$column['isMultiple'] === true) {
        $upload = [];
        $value = [];

        foreach ($column['toUpload']['tmp_name'] as $key => $tmpName) {
          $file = [
            'name' => $column['toUpload']['name'][$key],
            'full_path' => $column['toUpload']['full_path'][$key],
            'type' => $column['toUpload']['type'][$key],
            'tmp_name' => $column['toUpload']['tmp_name'][$key],
            'error' => $column['toUpload']['error'][$key],
            'size' => $column['toUpload']['size'][$key]
          ];

          $uploadClass = new Upload($file, @$column['folder']);
          $uploadPath = $uploadClass->get('path');

          $upload[] = $uploadClass;
          $value[] = $uploadPath;
        }

        $this->setColumnProperty($columnName, 'upload', $upload);
        $this->setColumnValue($columnName, $value);
      } else {
        $uploadClass = new Upload($column['toUpload'], @$column['folder']);
        $uploadPath = $uploadClass->get('path');

        $this->setColumnProperty($columnName, 'upload', $uploadClass);
        $this->setColumnValue($columnName, $uploadPath);
      }
    }

    return true;
  }

  protected function processMediaColumns()
  {
    foreach ($this->getColumn() as $columnName => $column) {
      if (@$column['isUpload'] !== true) {
        continue;
      }

      if (@$column['isMultiple'] === true) {
        foreach ($column['upload'] as $upload) {
          $upload->execute();
        }
      } else {
        $column['upload']->execute();
      }
    }

    return true;
  }
}
