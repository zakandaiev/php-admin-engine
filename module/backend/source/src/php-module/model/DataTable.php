<?php

namespace module\backend\model;

use engine\database\Model;
use engine\http\Response;

class DataTable extends Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function execute()
  {
    $this->modifyColumns();

    $this->validate();
    if ($this->hasError()) {
      return false;
    }

    $this->prepareMediaColumns();
    $resultMedia = $this->processMediaColumns();
    if (!$resultMedia) {
      return false;
    }

    $answer = [];
    foreach ($this->getColumnsToValidate() as $columnName => $column) {
      $answer[$columnName] = $column['value'];
    }

    Response::answer('success', null, $answer);

    return true;
  }
}
