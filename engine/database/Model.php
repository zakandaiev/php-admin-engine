<?php

namespace engine\database;

use engine\database\Validation;

abstract class Model
{
  protected $table;
  protected $primaryKey;
  protected $column = [];

  public $validation;

  public function __construct($data = [])
  {
    foreach ($this->column as $columnName => $column) {
      $this->column[$columnName]['value'] = $this->formatColumnValue($column['type'], $data[$columnName] ?? $column['value'] ?? null);
    }

    $this->validation = new Validation($this->column);
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

    $query = new Query("SHOW KEYS FROM {{$this->table}} WHERE Key_name='PRIMARY'");
    $this->primaryKey = $query->execute()->fetch();

    return $this->primaryKey;
  }

  public function hasColumn($key)
  {
    return isset($this->column[$key]);
  }

  public function getColumn($key = null)
  {
    return isset($key) ? @$this->column[$key] : $this->column;
  }

  public function add()
  {
    if (empty($this->table) || !$this->validation->validate()) {
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

    $sqlParams = '(' . implode(', ', $columnKeys) . ') VALUES (:' . implode(', :', $columnKeys) . ')';
    $sql = "INSERT INTO {{$this->table}} $sqlParams";

    $query = new Query($sql);
    $result = $query->execute($columnValues)->insertId();

    return $result;
  }

  public function edit()
  {
    if (empty($this->table) || !$this->validation->validate()) {
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
    if (empty($this->table) || !$this->validation->validate()) {
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
}
