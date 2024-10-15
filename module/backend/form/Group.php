<?php

namespace module\backend\form;

use engine\theme\Form;

class Group extends Form
{
  public function __construct()
  {
    $this->model = $this->loadModel('Group');

    $this->model->column['language']['type'] = 'hidden';
    $this->model->column['name']['className'] = 'col-xs-12';
  }
}
