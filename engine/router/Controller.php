<?php

namespace engine\router;

use engine\module\Module;
use engine\module\Setting;
use engine\router\Route;
use engine\util\Path;
use engine\theme\Page;
use engine\theme\View;

abstract class Controller
{
  protected $module;
  protected $modules;
  protected $route;
  protected $setting;

  protected $user;
  protected $page;
  protected $view;
  protected $model;

  public function __construct()
  {
    // TODO refactor
    class_alias('engine\\module\\Module', 'Module');
    class_alias('engine\\module\\Setting', 'Setting');
    class_alias('engine\\router\\Route', 'Route');
    class_alias('engine\\theme\\Asset', 'Asset');
    class_alias('engine\\theme\\Page', 'Page');
    class_alias('engine\\theme\\Template', 'Template');
    class_alias('engine\\theme\\Theme', 'Theme');
    class_alias('engine\\theme\\View', 'View');
    class_alias('engine\\util\\Path', 'Path');

    // TODO refactor
    // $this->module = Module::get();
    // $this->modules = Module::list();
    $this->route = Route::get();
    // $this->setting = Setting::get();

    // TODO
    // $this->user = new User();
    $this->page = new Page();
    $this->view = new View();
    $this->model = $this->loadModel($this->route['controller']);
  }

  protected function loadModel($modelName, $module = null)
  {
    $model = Path::class('model', $module) . '\\' . $modelName;

    if (class_exists($model)) {
      return new $model;
    }

    return null;
  }

  public function error($code = '404')
  {
    $this->view->error($code);

    return true;
  }
}
