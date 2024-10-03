<?php

namespace module\dev\controller;

use engine\util\Path;
use module\backend\controller\Backend;

class UI extends Backend
{
  public function getHome()
  {
    $this->view->render('ui');
  }

  public function getSection()
  {
    $section = $this->route['parameter']['section'];

    $data['section'] = $section;

    $view = 'ui/' . $section;
    $path = Path::resolve(Path::file('view'), "$view.php");

    if (!is_file($path)) {
      $this->view->error('404');
    }

    $this->view->setData($data);
    $this->view->render($view);
  }
}
