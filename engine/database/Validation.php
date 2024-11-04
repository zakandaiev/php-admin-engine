<?php

namespace engine\database;

use engine\i18n\I18n;
use engine\util\File;

abstract class Validation
{
  protected $table;
  protected $primaryKey;
  protected $itemId;
  protected $column = [];
  protected $columnKeysToValidate = [];
  protected $error = [];
  protected $validation = [
    // COLUMN TYPES TO VALIDATE
    'boolean',
    'date',
    'email',
    'file',
    'url',
    'time',

    // COLUMN ATTRIBUTES TO VALIDATE
    'required',
    'min',
    'max',
    'regex',
    'color',
    'extensions',
    'maxSize',
    'isArray',
  ];

  public function setTable($table)
  {
    $this->table = $table;

    return true;
  }

  public function hasTable()
  {
    return !empty($this->table);
  }

  public function getTable()
  {
    return $this->table;
  }

  public function setPrimaryKey($primaryKey)
  {
    $this->primaryKey = $primaryKey;

    return true;
  }

  public function hasPrimaryKey()
  {
    return !empty($this->primaryKey);
  }

  public function getPrimaryKey()
  {
    if ($this->hasPrimaryKey()) {
      return $this->primaryKey;
    }

    if (empty($this->table)) {
      return false;
    }

    $query = new Query("SHOW KEYS FROM {{$this->table}} WHERE Key_name='PRIMARY'");
    $result = $query->execute()->fetch();
    if (!$result) {
      return false;
    }

    $this->primaryKey = $result->Column_name;

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

  public function setColumn($columnName, $value = null)
  {
    $this->column[$columnName] = $value;

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

  public function hasColumnValue($columnName)
  {
    if (!$this->hasColumn($columnName)) {
      return false;
    }

    return isset($this->column[$columnName]['value']);
  }

  public function getColumnValue($columnName)
  {
    return @$this->column[$columnName]['value'];
  }

  public function setColumnProperty($columnName, $propertyName, $value = null)
  {
    if (!$this->hasColumn($columnName)) {
      return false;
    }

    $this->column[$columnName][$propertyName] = $value;

    return true;
  }

  public function hasColumnProperty($columnName, $propertyName)
  {
    if (!$this->hasColumn($columnName)) {
      return false;
    }

    return isset($this->column[$columnName][$propertyName]);
  }

  public function getColumnProperty($columnName, $propertyName)
  {
    return @$this->column[$columnName][$propertyName];
  }

  public function setColumnKeysToValidate($value = null)
  {
    $this->columnKeysToValidate = $value ?? [];

    return true;
  }

  public function hasColumnKeysToValidate()
  {
    return count($this->columnKeysToValidate) > 0 ? true : false;
  }

  public function getColumnKeysToValidate()
  {
    return $this->columnKeysToValidate;
  }

  public function setColumnKeyToValidate($columnName)
  {
    if (in_array($columnName, $this->columnKeysToValidate)) {
      return false;
    }

    $this->columnKeysToValidate[] = $columnName;

    return true;
  }

  public function hasColumnKeyToValidate($columnName)
  {
    return in_array($columnName, $this->columnKeysToValidate);
  }

  public function getColumnsToValidate()
  {
    if ($this->hasColumnKeysToValidate()) {
      return array_intersect_key($this->getColumn(), array_flip($this->getColumnKeysToValidate()));
    }

    return $this->getColumn();
  }

  public function flushError()
  {
    $this->error = [];

    return true;
  }

  public function setError($columnName = null, $validation = null, $columnValue = null)
  {
    if (is_array($columnName)) {
      $this->error[] = $columnName;

      return true;
    }

    if (in_array($columnName, $this->error)) {
      return false;
    }

    $this->error[] = [
      'column' => $columnName,
      'validation' => $validation,
      'value' => $columnValue
    ];

    return true;
  }

  public function hasError()
  {
    return count($this->error) > 0 ? true : false;
  }

  public function getError()
  {
    return $this->error;
  }

  public function validate()
  {
    foreach ($this->getColumn() as $columnName => $columnDefinition) {
      $result = $this->validateColumn($columnName);

      if ($result !== true) {
        $this->setError($result);
      }
    }

    return $this->hasError();
  }

  public function validateColumn($columnName)
  {
    $result = true;

    if ($this->hasColumnKeysToValidate() && !$this->hasColumnKeyToValidate($columnName)) {
      return $result;
    }

    $columnDefinition = $this->getColumn($columnName);
    if (!$columnDefinition) {
      return $result;
    }

    $columnType = $columnDefinition['type'];
    $columnValue = @$columnDefinition['value'];

    $this->setColumnValue($columnName, $columnValue);

    if (@$columnDefinition['required'] !== true && $columnValue === null) {
      return $result;
    }

    $isColumnValidated = false;

    foreach ($this->validation as $validation) {
      if ($isColumnValidated || (!isset($columnDefinition[$validation]) && $columnType !== $validation)) {
        continue;
      }

      $validationName = $validation;
      $validationResult = $this->{'validate' . ucfirst($validation)}($columnType, @$columnDefinition[$validation], $columnValue, $columnDefinition);

      if ($validationResult !== true) {
        $result = [
          'column' => $columnName,
          'validation' => $columnDefinition['message'][$validationName] ?? I18n::translate("{$this->table}.$columnName.validation.$validationName", $columnDefinition),
          'value' => $columnValue
        ];

        $isColumnValidated = true;
      }
    }

    return $result;
  }

  protected function validateRequired($type, $rule, $value, $column)
  {
    if ($rule !== true && empty($value)) {
      return true;
    }

    $result = true;

    switch ($type) {
      case 'boolean': {
          $result = is_bool($value);
          break;
        }
      case 'number': {
          $result = is_numeric($value);
          break;
        }
      case 'file': {
          $result = $column['isUpload'] || !empty($value) ? true : false;
          break;
        }
      default: {
          $result = !empty($value);
        }
    }

    return $result;
  }

  protected function validateBoolean($type, $rule, $value)
  {
    return is_bool($value);
  }

  protected function validateDate($type, $rule, $value, $column)
  {
    $result = true;

    if (@$column['isMultiple'] === true) {
      foreach ($value as $v) {
        if (!strtotime($v)) {
          $result = false;
        }
      }
    } else {
      $result = strtotime($value) ? true : false;
    }

    return $result;
  }

  protected function validateEmail($type, $rule, $value)
  {
    return filter_var($value, FILTER_VALIDATE_EMAIL) ? true : false;
  }

  protected function validateFile($type, $rule, $value, $column)
  {
    if (@$column['isMultiple'] === true) {
      return is_array($value) ? true : false;
    }

    return true;
  }

  protected function validateUrl($type, $rule, $value)
  {
    return filter_var($value, FILTER_VALIDATE_URL) ? true : false;
  }

  protected function validateTime($type, $rule, $value)
  {
    return preg_match('/^[0-9]{2}:[0-9]{2}$/', $value ?? '') ? true : false;
  }

  protected function validateMin($type, $rule, $value, $column)
  {
    $result = true;

    switch ($type) {
      case 'date':
      case 'datetime':
      case 'month': {
          if (@$column['isMultiple'] === true) {
            foreach ($value as $v) {
              $result = strtotime($v) >= strtotime($rule);
            }
          } else {
            $result = strtotime($value) >= strtotime($rule);
          }

          break;
        }
      case 'number': {
          if (@$column['isMultiple'] === true) {
            foreach ($value as $v) {
              $result = $v >= $rule;
            }
          } else {
            $result = $value >= $rule;
          }

          break;
        }
      case 'file': {
          if (@$column['isMultiple'] === true) {
            $countToUpload = @$column['isUpload'] === true ? count($column['toUpload']['tmp_name']) : 0;
            $countValue = count($value);

            $result = ($countToUpload + $countValue) >= $rule;
          }

          break;
        }
      default: {
          if (@$column['isMultiple'] === true) {
            $result = count($value) >= $rule;
          } else {
            $result = mb_strlen($value ?? '') >= $rule;
          }
        }
    }

    return $result;
  }

  protected function validateMax($type, $rule, $value, $column)
  {
    $result = true;

    switch ($type) {
      case 'date':
      case 'datetime':
      case 'month': {
          if (@$column['isMultiple'] === true) {
            foreach ($value as $v) {
              $result = strtotime($v) <= strtotime($rule);
            }
          } else {
            $result = strtotime($value) <= strtotime($rule);
          }

          break;
        }
      case 'number': {
          if (@$column['isMultiple'] === true) {
            foreach ($value as $v) {
              $result = $v <= $rule;
            }
          } else {
            $result = $value <= $rule;
          }

          break;
        }
      case 'file': {
          if (@$column['isMultiple'] === true) {
            $countToUpload = @$column['isUpload'] === true ? count($column['toUpload']['tmp_name']) : 0;
            $countValue = count($value);

            $result = ($countToUpload + $countValue) <= $rule;
          }

          break;
        }
      default: {
          if (@$column['isMultiple'] === true) {
            $result = count($value) <= $rule;
          } else {
            $result = mb_strlen($value ?? '') <= $rule;
          }
        }
    }

    return $result;
  }

  protected function validateRegex($type, $rule, $value)
  {
    return preg_match($rule, $value ?? '') ? true : false;
  }

  protected function validateColor($type, $rule, $value)
  {
    return preg_match('/^#([a-f0-9]{6}|[a-f0-9]{3})$/i', $value ?? '') ? true : false;
  }

  protected function validateExtensions($type, $rule, $value, $column)
  {
    if ($type !== 'file') {
      return true;
    }

    if (@$column['isMultiple'] === true && @$column['isUpload'] === true) {
      foreach ($column['toUpload']['name'] as $file) {
        $fileExtension = strtolower(File::getExtension($file));
        if (!in_array($fileExtension, $rule)) {
          return false;
        }
      }

      return true;
    } else if (@$column['isMultiple'] === true) {
      foreach ($value as $file) {
        $fileExtension = strtolower(File::getExtension($file));
        if (!in_array($fileExtension, $rule)) {
          return false;
        }
      }

      return true;
    }

    $fileExtension = strtolower(File::getExtension(!empty($value) ? $value : @$column['toUpload']['name']));
    if (!in_array($fileExtension, $rule)) {
      return false;
    }

    return true;
  }

  protected function validateMaxSize($type, $rule, $value, $column)
  {
    if ($type !== 'file' || !$rule || @$column['isUpload'] !== true) {
      return true;
    }

    if (@$column['isMultiple'] === true) {
      foreach ($column['toUpload']['size'] as $size) {
        if ($size > $rule) {
          return false;
        }
      }

      return true;
    }

    return $column['toUpload']['size'] > $rule ? false : true;
  }

  protected function validateIsArray($type, $rule, $value)
  {
    return is_array($value);
  }
}
