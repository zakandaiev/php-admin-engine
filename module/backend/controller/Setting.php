<?php

namespace module\backend\controller;

use module\backend\controller\Backend;
use engine\module\Setting as ModuleSetting;

class Setting extends Backend
{
  public function getSection()
  {
    $section = $this->route['parameter']['section'];

    if (!ModuleSetting::exists($section)) {
      $this->view->error('404');
      return false;
    }

    $this->view->setData('section', $section);
    $this->view->render('setting/section');
  }
}
