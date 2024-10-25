<?php

namespace engine\database;

use engine\database\Validation;

abstract class Model
{
  protected $table;
  protected $primaryKey;
  protected $itemId;
  protected $column = [];
  protected $columnKeysToValidate = [];

  protected $queryForeign = [];
  protected $queryError = [];

  public $validation;

  protected static $instance;

  public function __construct($columnData = null, $columnKeysToValidate = null)
  {
    $this->setData($columnData, $columnKeysToValidate);

    self::$instance = $this;
  }

  public function setData($columnData = null, $columnKeysToValidate = null)
  {
    foreach ($this->column as $columnName => $column) {
      $this->column[$columnName]['value'] = $this->formatColumnValue($column['type'], $columnData[$columnName] ?? $column['value'] ?? null);
    }

    if (is_array($columnKeysToValidate)) {
      $columnKeysToValidate[] = $this->getPrimaryKey();
      $this->columnKeysToValidate = $columnKeysToValidate;
    }

    $this->validation = new Validation($this->table, $this->column, $this->columnKeysToValidate);

    return $this;
  }

  public function hasTable()
  {
    return !empty($this->table);
  }

  public function getTable()
  {
    return $this->table;
  }

  public function getPrimaryKey()
  {
    if (!empty($this->primaryKey)) {
      return $this->primaryKey;
    }

    if (empty($this->table)) {
      return false;
    }

    $query = new Query("SHOW KEYS FROM {{$this->table}} WHERE Key_name='PRIMARY'", false, true);
    $this->primaryKey = $query->execute()->fetch();

    return $this->primaryKey;
  }

  public function setItemId($itemId)
  {
    $this->itemId = $itemId;

    return true;
  }

  public function hasItemId()
  {
    return !empty($this->itemId);
  }

  public function getItemId()
  {
    return $this->itemId;
  }

  public function unsetColumn($columnName)
  {
    unset($this->column[$columnName]);

    if (in_array($columnName, $this->columnKeysToValidate)) {
      unset($this->columnKeysToValidate[$columnName]);
    }

    return true;
  }

  public function hasColumn($columnName)
  {
    return isset($this->column[$columnName]);
  }

  public function getColumn($columnName = null)
  {
    return isset($columnName) ? @$this->column[$columnName] : $this->column;
  }

  public function setColumnValue($columnName, $value = null)
  {
    if (!$this->hasColumn($columnName)) {
      return false;
    }

    $this->column[$columnName]['value'] = $value;

    return true;
  }

  public function getColumnKeysToValidate()
  {
    return $this->columnKeysToValidate;
  }

  public function setError($columnName, $validation, $columnValue = null)
  {
    $this->queryError[] = [
      'column' => $columnName,
      'validation' => $validation,
      'value' => $columnValue
    ];

    return true;
  }

  public function hasError()
  {
    return count($this->queryError) > 0 ? true : false;
  }

  public function getError()
  {
    return $this->queryError;
  }

  public function add()
  {
    if (empty($this->table)) {
      return false;
    }

    return $this->processQuery('add');
  }

  public function edit()
  {
    if (empty($this->table)) {
      return false;
    }

    return $this->processQuery('edit');
  }

  public function delete()
  {
    if (empty($this->table)) {
      return false;
    }

    return $this->processQuery('delete');
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
      $this->queryError = $query->getError();
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
      $this->queryError = $query->getError();

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
      $this->queryError = $query->getError();

      return false;
    }

    return $pkValue;
  }

  public static function getInstance()
  {
    return self::$instance;
  }

  protected function formatColumnValue($type, $value)
  {
    if ($type === 'boolean') {
      return $value === true || in_array($value, ['on', 'yes', '1', 'true']) ? true : false;
    } else if ($type === 'file') {
      // TODO
    } else if ($type === 'number') {
      return !empty($value) && is_numeric($value) ? floatval($value) : null;
    }

    return $value;
  }

  protected function processQuery($action)
  {
    if (empty($this->table) || empty($this->column)) {
      return false;
    }

    $this->modifyColumns();

    $this->validation->validate();
    if ($this->validation->hasError()) {
      $this->queryError = $this->validation->getError();
      return false;
    }

    // TODO
    // self::prepareMediaFields();
    // $foreign_data = self::getForeignFields();
    // $translation_data = self::getTranslationFields($form_data['modelName']);

    $table = $this->table;
    $pkName = $this->getPrimaryKey();
    $pkValue = $this->hasItemId() ? $this->getItemId() : @$this->column[$pkName]['value'];

    $this->prepareForeignColumns();

    $columnValues = [];
    foreach ($this->column as $columnName => $column) {
      if (isset($column['foreign']) || (!empty($this->columnKeysToValidate) && !in_array($columnName, $this->columnKeysToValidate))) {
        continue;
      }

      $columnValues[$columnName] = @$column['value'];
    }

    if (empty($columnValues)) {
      return false;
    }

    $result = null;
    if ($action === 'add') {
      $result = $this->insertIntoTable($table, $columnValues);
    } else if ($action === 'edit') {
      $result = $this->updateTable($table, $columnValues, $pkName, $pkValue);
    } else if ($action === 'delete') {
      $result = $this->deleteFromTable($table, $columnValues, $pkName, $pkValue);
    } else {
      return false;
    }

    if (!$result) {
      return false;
    }

    $resultForeign = $this->processForeignColumns($result);
    if (!$resultForeign) {
      return false;
    }

    // TODO
    // self::uploadMediaFields();
    // self::processForeignColumns($foreign_data, $form_data);
    // self::processTranslationFields($translation_data, $form_data);

    // if (isset($form['execute_post']) && is_closure($form['execute_post'])) {
    //   $form['execute_post']($form_data['rowCount'], self::$fields, $form_data);
    // }

    // if (isset($form['submit_message']) && is_closure($form['submit_message'])) {
    //   $submit_message = $form['submit_message'](self::$fields, $form_data);
    // } else if (isset($form['submit_message'])) {
    //   $submit_message = $form['submit_message'];
    // }

    // if (!$form_data['force_no_answer']) {
    //   Server::answer(null, 'success', @$submit_message);
    // }

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
      if (!isset($column['foreign']) || empty($column['foreign']) || (!empty($this->columnKeysToValidate) && !in_array($columnName, $this->columnKeysToValidate))) {
        continue;
      }

      list($foreignTable, $foreignPkName) = explode('@', $column['foreign'], 2);

      if (empty($foreignTable) || empty($foreignPkName)) {
        continue;
      }

      $this->queryForeign[$foreignTable][] = [
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
    if (empty($this->queryForeign)) {
      return true;
    }

    $foreignSqlData = [];

    foreach ($this->queryForeign as $foreignTable => $foreignTableColumns) {
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
}
