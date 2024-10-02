<?php

namespace engine\database;

abstract class Model
{
  protected $column = [];
  protected $errors = [];
  protected $validations = ['required', 'boolean', 'min', 'max', 'regex'];

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
      $result = $this->validateColumn($columnName, $columnDefinition);

      if ($result !== true) {
        $this->errors[] = $result;
      }
    }

    return empty($this->errors) ? true : $this->errors;
  }

  public function validateColumn($columnName, $columnDefinition = [])
  {
    $result = true;

    $columnType = $columnDefinition['type'];
    $columnValue = @$columnDefinition['value'];

    if (@$columnDefinition['required'] !== true && empty($columnValue)) {
      return $result;
    }

    $isColumnValidated = false;

    foreach ($this->validations as $validation) {
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
}
