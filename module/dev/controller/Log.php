<?php

namespace module\dev\controller;

use module\backend\controller\Backend;
use engine\util\Log as LogUtil;

class Log extends Backend
{
  public function getList()
  {
    $data['logs'] = LogUtil::list();

    $this->view->setData($data);
    $this->view->render('log/list');
  }

  public function getView()
  {
    $file = $this->route['parameter']['file'];

    @list($file, $folder) = explode('_', $file, 2);

    if (!LogUtil::exists($file, $folder)) {
      $this->view->error('404');
      return false;
    }

    $data['file'] = $file;
    $data['folder'] = $folder;
    $data['log'] = LogUtil::get($file, $folder);

    $this->view->setData($data);
    $this->view->render('log/view');
  }
}
