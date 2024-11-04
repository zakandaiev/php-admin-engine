<?php

namespace module\contact\model;

use module\backend\model\DataTable;

class DataPhone extends DataTable
{
  public function __construct($columnData = null, $columnKeysToValidate = null)
  {
    $this->setColumn('phone', [
      'type' => 'text',
      'required' => true,
      'min' => 8,
      'max' => 64
    ]);

    parent::__construct($columnData, $columnKeysToValidate);
  }
}
