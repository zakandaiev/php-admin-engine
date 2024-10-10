<?php

namespace module\dev\controller;

use engine\util\Path;
use module\backend\controller\Backend;

class UI extends Backend
{
  public function getHome()
  {
    $this->view->render('ui/home');
  }

  public function getSection()
  {
    $section = $this->route['parameter']['section'];

    $data['section'] = $section;

    $view = Path::resolve('ui', $section);
    $path = Path::resolve(Path::file('page'), "$view.php");

    if (!is_file($path)) {
      $this->view->error('404');

      return false;
    }

    $this->view->setData($data);
    $this->view->render($view);
  }
}
