<?php

namespace engine\database;

abstract class Model
{
  protected $table;
  protected $primaryKey;
  protected $column = [];
  protected $errors = [];
  protected $validation = ['required', 'boolean', 'min', 'max', 'regex'];

  public function __construct($data = [])
  {
    foreach ($data as $key => $value) {
      if (isset($this->column[$key])) {
        $this->column[$key]['value'] = $value;
      }
    }
  }

  public function hasColumn($key)
  {
    return isset($this->column[$key]) > 0 ? true : false;
  }

  public function getColumn($key = null)
  {
    return isset($key) ? @$this->column[$key] : $this->column;
  }

  public function hasErrors()
  {
    return count($this->errors) > 0 ? true : false;
  }

  public function getErrors()
  {
    return $this->errors;
  }

  public function validate()
  {
    foreach ($this->column as $columnName => $columnDefinition) {
      $result = $this->validateColumn($columnName);

      if ($result !== true) {
        $this->errors[] = $result;
      }
    }

    return empty($this->errors) ? true : false;
  }

  public function validateColumn($columnName)
  {
    $result = true;

    $columnDefinition = @$this->column[$columnName];
    if (!$columnDefinition) {
      return $result;
    }

    $columnType = $columnDefinition['type'];
    $columnValue = @$columnDefinition['value'];

    if (@$columnDefinition['required'] !== true && $columnValue === null) {
      return $result;
    }

    $isColumnValidated = false;

    foreach ($this->validation as $validation) {
      if ($isColumnValidated || (!isset($columnDefinition[$validation]) && $columnType !== $validation)) {
        continue;
      }

      $validationName = $validation;
      $validationResult = $this->{'validate' . ucfirst($validation)}($columnType, @$columnDefinition[$validation], $columnValue);

      if ($validationResult !== true) {
        $result = [
          'column' => $columnName,
          'validation' => $validationName,
          'value' => $columnValue
        ];

        $isColumnValidated = true;
      }
    }

    return $result;
  }

  protected function validateRequired($type, $rule, $value)
  {
    $result = true;

    if ($rule !== true && empty($value)) {
      return $result;
    }

    if ($type === 'boolean' && !is_bool($value)) {
      $result = false;
    } else if ($type === 'number' && !is_numeric($value)) {
      $result = false;
    } else if (empty($value)) {
      $result = false;
    }

    return $result;
  }

  protected function validateMin($type, $rule, $value)
  {
    $result = true;

    if ($type === 'number' && $value < $rule) {
      $result = false;
    } else if ($type === 'array' && count($value) < $rule) {
      $result = false;
    } else if (mb_strlen($value ?? '') < $rule) {
      $result = false;
    }

    return $result;
  }

  protected function validateMax($type, $rule, $value)
  {
    $result = true;

    if ($type === 'number' && $value > $rule) {
      $result = false;
    } else if ($type === 'array' && count($value) > $rule) {
      $result = false;
    } else if (mb_strlen($value ?? '') > $rule) {
      $result = false;
    }

    return $result;
  }

  protected function validateBoolean($type, $rule, $value)
  {
    return is_bool($value);
  }

  protected function validateRegex($type, $rule, $value)
  {
    return preg_match($rule, $value ?? '') ? true : false;
  }

  public function add()
  {
    if (!$this->validate()) {
      return false;
    }

    $columnKeys = [];
    $columnValues = [];

    foreach ($this->column as $columnName => $column) {
      if (!isset($column['value'])) {
        continue;
      }

      $columnKeys[] = $columnName;
      $columnValues[$columnName] = $column['value'];
    }

    if (empty($columnKeys) || empty($columnValues)) {
      return false;
    }

    $sqlParams = '(' . implode(', ', array_keys($columnKeys)) . ') VALUES (:' . implode(', :', array_keys($columnKeys)) . ')';

    $sql = "INSERT INTO {{$this->table}} $sqlParams";
    $query = new Query($sql);
    $result = $query->execute($columnValues)->insertId();

    return $result;
  }

  public function edit()
  {
    if (!$this->validate()) {
      return false;
    }

    $pkName = $this->getPrimaryKey();
    if (!$pkName) {
      return false;
    }

    $columnKeys = [];
    $columnValues = [];

    foreach ($this->column as $columnName => $column) {
      if (!isset($column['value'])) {
        continue;
      }

      $columnKeys[] = $columnName;
      $columnValues[$columnName] = $column['value'];
    }

    if (empty($columnKeys) || empty($columnValues)) {
      return false;
    }

    $sqlParams = array_reduce($columnKeys, function ($carry, $v) {
      return ($carry ? "$carry, " : '') . "$v=:$v";
    });

    $sql = "UPDATE {{$this->table}} SET $sqlParams WHERE $pkName=:$pkName";
    $query = new Query($sql);
    $query->execute($columnValues);

    return true;
  }

  public function delete()
  {
    if (!$this->validate()) {
      return false;
    }

    $pkName = $this->getPrimaryKey();
    if (!$pkName) {
      return false;
    }

    $columnKeys = [];
    $columnValues = [];

    foreach ($this->column as $columnName => $column) {
      if (!isset($column['value'])) {
        continue;
      }

      $columnKeys[] = $columnName;
      $columnValues[$columnName] = $column['value'];
    }

    if (empty($columnKeys) || empty($columnValues)) {
      return false;
    }

    $sqlParams = array_reduce($columnKeys, function ($carry, $v) {
      return ($carry ? "$carry AND " : '') . "$v=:$v";
    });

    $sql = "DELETE FROM {{$this->table}} WHERE $sqlParams AND $pkName=:$pkName";
    $query = new Query($sql);
    $query->execute($columnValues);

    return true;
  }

  public function getPrimaryKey()
  {
    if (!empty($this->primaryKey)) {
      return $this->primaryKey;
    }

    if (empty($this->table)) {
      return false;
    }

    $query = new Query("SHOW KEYS FROM {{$this->table}} WHERE Key_name='PRIMARY'");

    return $query->execute()->fetch();
  }
}
