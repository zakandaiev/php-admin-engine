<?php

namespace engine\database;

class Validation
{
  protected $column = [];
  protected $error = [];
  protected $validation = [
    // COLUMN TYPES
    'array',
    'boolean',
    'date',
    'email',
    'url',
    'time',

    // COLUMN ATTRIBUTES
    'required',
    'min',
    'max',
    'regex',
    'color',
    'extensions',
  ];

  public function __construct($data = [])
  {
    $this->column = $data;
  }

  public function hasColumn($key)
  {
    return isset($this->column[$key]);
  }

  public function getColumn($key = null)
  {
    return isset($key) ? @$this->column[$key] : $this->column;
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
    foreach ($this->column as $columnName => $columnDefinition) {
      $result = $this->validateColumn($columnName);

      if ($result !== true) {
        $this->error[] = $result;
      }
    }

    return empty($this->error) ? true : false;
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

    $this->column[$columnName]['value'] = $columnValue;

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
    if ($rule !== true && empty($value)) {
      return true;
    }

    $result = true;

    if ($type === 'boolean' && !is_bool($value)) {
      $result = false;
    } else if ($type === 'number' && !is_numeric($value)) {
      $result = false;
    } else if (empty($value)) {
      $result = false;
    }

    return $result;
  }

  protected function validateArray($type, $rule, $value)
  {
    return is_array($value);
  }

  protected function validateBoolean($type, $rule, $value)
  {
    return is_bool($value);
  }

  protected function validateDate($type, $rule, $value, $columnDefinition)
  {
    $result = true;

    // TODO
    // if ((isset($columnDefinition['multiple']) && $columnDefinition['multiple']) || (isset($columnDefinition['range']) && $columnDefinition['range'])) {
    //   $result = true;

    //   foreach ($value as $v) {
    //     if (!strtotime($v)) {
    //       $result = false;
    //     }
    //   }

    //   return $result;
    // } else if() {
    $result = strtotime($value) ? true : false;
    // }

    return $result;
  }

  protected function validateEmail($type, $rule, $value)
  {
    return filter_var($value, FILTER_VALIDATE_EMAIL) ? true : false;
  }

  protected function validateUrl($type, $rule, $value)
  {
    return filter_var($value, FILTER_VALIDATE_URL) ? true : false;
  }

  protected function validateTime($type, $rule, $value)
  {
    return preg_match('/^[0-9]{2}:[0-9]{2}$/', $value ?? '') ? true : false;
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

    // TODO
    // case 'min': {
    //   if (isset($field_data['multiple']) && $field_data['multiple'] && in_array($type, ['date', 'datetime', 'month'])) {
    //     $result = true;

    //     foreach ($value as $v) {
    //       if (strtotime($v) >= strtotime($operand_value)) {
    //         $result = false;
    //       }
    //     }

    //     return $result;
    //   } else if (isset($field_data['range']) && $field_data['range'] && in_array($type, ['date', 'datetime', 'month'])) {
    //     return strtotime($value[0]) >= strtotime($operand_value) ? true : false;
    //   } else if (isset($field_data['multiple']) && $field_data['multiple']) {
    //     return count($value ?? []) >= $operand_value ? true : false;
    //   }

    //   switch ($type) {
    //     case 'date':
    //     case 'datetime':
    //     case 'month': {
    //         return strtotime($value) >= strtotime($operand_value) ? true : false;
    //       }
    //     case 'number':
    //     case 'range': {
    //         return $value >= $operand_value ? true : false;
    //       }
    //     case 'wysiwyg': {
    //         $value = html($value);
    //       }
    //     case 'file': {
    //         todo
    //       }
    //   }

    //   return mb_strlen($value ?? '') >= $operand_value ? true : false;
    // }

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

    // TODO
    // case 'max': {
    //   if (isset($field_data['multiple']) && $field_data['multiple'] && in_array($type, ['date', 'datetime', 'month'])) {
    //     $result = true;

    //     foreach ($value as $v) {
    //       if (strtotime($v) <= strtotime($operand_value)) {
    //         $result = false;
    //       }
    //     }

    //     return $result;
    //   } else if (isset($field_data['range']) && $field_data['range'] && in_array($type, ['date', 'datetime', 'month'])) {
    //     return strtotime($value[1]) <= strtotime($operand_value) ? true : false;
    //   } else if (isset($field_data['multiple']) && $field_data['multiple']) {
    //     return count($value ?? []) <= $operand_value ? true : false;
    //   }

    //   switch ($type) {
    //     case 'date':
    //     case 'datetime':
    //     case 'month': {
    //         return strtotime($value) <= strtotime($operand_value) ? true : false;
    //       }
    //     case 'number':
    //     case 'range': {
    //         return $value <= $operand_value ? true : false;
    //       }
    //     case 'wysiwyg': {
    //         $value = html($value);
    //       }
    //     case 'file': {
    //         todo
    //       }
    //   }

    //   return mb_strlen($value ?? '') <= $operand_value ? true : false;
    // }

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

  protected function validateExtensions($type, $rule, $value)
  {
    // case 'extensions': {
    //   if (isset($field_data['to_upload']) && $field_data['to_upload'] === false) {
    //     return true;
    //   }

    //   foreach ($value as $file) {
    //     $file_extension = strtolower(file_extension($file['name']));

    //     $allowed_extensions = is_array($operand_value) ? $operand_value : UPLOAD['extensions'];

    //     if (!in_array($file_extension, $allowed_extensions)) {
    //       return false;
    //     }
    //   }

    //   return true;
    // }

    return true;
  }
}
