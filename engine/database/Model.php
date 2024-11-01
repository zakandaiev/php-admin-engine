<?php

namespace engine\database;

use engine\database\Validation;
use engine\http\Request;
use engine\util\Date;
use engine\util\Upload;

abstract class Model extends Validation
{
  protected static $instance;
  protected $columnForeign = [];

  public function __construct($columnData = null, $columnKeysToValidate = null)
  {
    $this->setData($columnData, $columnKeysToValidate);

    self::$instance = $this;
  }

  public static function getInstance()
  {
    return self::$instance;
  }

  public function setData($columnData = null, $columnKeysToValidate = null)
  {
    foreach ($this->column as $columnName => $column) {
      $this->column[$columnName]['value'] = $this->formatColumnValue($column['type'], $columnData[$columnName] ?? $column['value'] ?? null, $columnName);
    }

    if (is_array($columnKeysToValidate)) {
      $columnKeysToValidate[] = $this->getPrimaryKey();
      $this->columnKeysToValidate = $columnKeysToValidate;
    }

    return $this;
  }

  public function add()
  {
    if (!$this->hasTable()) {
      return false;
    }

    return $this->processQuery(__FUNCTION__);
  }

  public function edit()
  {
    if (!$this->hasTable()) {
      return false;
    }

    return $this->processQuery(__FUNCTION__);
  }

  public function delete()
  {
    if (!$this->hasTable()) {
      return false;
    }

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

  protected function formatColumnValue($type, $value, $name)
  {
    if ($type === 'boolean') {
      return $value === true || in_array($value, ['on', 'yes', '1', 'true']) ? true : false;
    }

    if ($type === 'number') {
      return !empty($value) && is_numeric($value) ? floatval($value) : null;
    }

    if ($type === 'file') {
      $this->column[$name]['isUpload'] = false;
      $this->column[$name]['maxSize'] = $this->column[$name]['maxSize'] ?? Upload::getMaxSize();
      $this->column[$name]['extensions'] = $this->column[$name]['extensions'] ?? Upload::getExtensions();

      $files = Request::files($name);
      if (
        (isset($files['tmp_name']) && !is_array($files['tmp_name']) && !empty($files['tmp_name']))
        || (isset($files['tmp_name']) && is_array($files['tmp_name']) && !empty($files['tmp_name'][0]))
      ) {
        $this->column[$name]['isUpload'] = true;
        $this->column[$name]['toUpload'] = $files;
      }

      return $value;
    }

    return $value;
  }

  protected function processQuery($action)
  {
    if (!$this->hasTable() || empty($this->column)) {
      return false;
    }

    $this->modifyColumns();

    $this->validate();
    if ($this->hasError()) {
      return false;
    }

    $table = $this->getTable();
    $pkName = $this->getPrimaryKey();
    $pkValue = $this->hasItemId() ? $this->getItemId() : @$this->column[$pkName]['value'];

    $this->prepareForeignColumns();
    $this->prepareMediaColumns();

    $columnValues = [];
    foreach ($this->column as $columnName => $column) {
      if (isset($column['foreign']) || ($this->hasColumnKeysToValidate() && !$this->hasColumnKeyToValidate($columnName))) {
        continue;
      }

      $columnValues[$columnName] = @$column['value'];
    }

    if (empty($columnValues)) {
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
    foreach ($this->column as $columnName => $column) {
      if (isset($column['unsetNull']) && $column['unsetNull'] === true && (@$column['value'] === null || @$column['value'] === '')) {
        $this->unsetColumn($columnName);
        continue;
      }

      if (!isset($column['modify']) || !isClosure($column['modify'])) {
        continue;
      }

      $modifiedValue = $column['modify'](@$column['value'], $column, $this->column);

      $this->setColumnValue($columnName, $modifiedValue);
    }
  }

  protected function prepareForeignColumns()
  {
    foreach ($this->column as $columnName => $column) {
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
    foreach ($this->column as $columnName => $column) {
      if (@$column['isUpload'] !== true) {
        continue;
      }

      if (@$column['isMultiple'] === true) {
        $this->column[$columnName]['upload'] = [];
        foreach ($column['toUpload']['tmp_name'] as $key => $tmpName) {
          $file = [
            'name' => $column['toUpload']['name'][$key],
            'full_path' => $column['toUpload']['full_path'][$key],
            'type' => $column['toUpload']['type'][$key],
            'tmp_name' => $column['toUpload']['tmp_name'][$key],
            'error' => $column['toUpload']['error'][$key],
            'size' => $column['toUpload']['size'][$key]
          ];

          $upload = new Upload($file, @$column['folder']);
          $uploadPath = $upload->get('path');

          $this->column[$columnName]['upload'][] = $upload;
          $this->column[$columnName]['value'][] = $uploadPath;
        }
      } else {
        $this->column[$columnName]['upload'] = new Upload($column['value'], @$column['folder']);
        $this->column[$columnName]['value'] = $this->column[$columnName]['upload']->get('path');
      }
    }

    return true;
  }

  protected function processMediaColumns()
  {
    foreach ($this->column as $columnName => $column) {
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
