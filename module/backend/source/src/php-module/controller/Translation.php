<?php

namespace module\backend\controller;

use module\backend\controller\Backend;
use engine\i18n\I18n;

class Translation extends Backend
{
  public function getList()
  {
    $this->view->setData('modules', $this->modules);

    $this->view->render('translation/list');
  }

  public function getAdd()
  {
    $this->view->render('translation/add');
  }

  public function getEdit()
  {
    $module = $this->route['parameter']['module'];
    $language = $this->route['parameter']['language'];

    if (!I18n::has($language, $module)) {
      $this->view->error('404');
      return false;
    }

    $region = I18n::getProperty('region', $language, $module, 1);
    $translation = $this->model->getModuleTranslationContent($module, $language);

    $this->view->setData('module', $module);
    $this->view->setData('language', $language);
    $this->view->setData('region', $region);
    $this->view->setData('translation', $translation);
    $this->view->render('translation/edit');
  }
}
