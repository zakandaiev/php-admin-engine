<?php

namespace module\backend\controller;

use module\backend\controller\Backend;

class Dashboard extends Backend
{
  public function getDashboard()
  {
    $this->view->render('dashboard');
  }
}
