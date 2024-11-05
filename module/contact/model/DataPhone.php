<?php

namespace module\contact\model;

use module\backend\model\DataTable;

class DataPhone extends DataTable
{
  public function __construct()
  {
    parent::__construct();

    $this->setColumn('phone', [
      'type' => 'text',
      'required' => true,
      'min' => 8,
      'max' => 64
    ]);
  }
}
