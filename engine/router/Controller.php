<?php

namespace engine\router;

use engine\auth\User;
use engine\module\Module;
use engine\module\Setting;
use engine\router\Route;
use engine\router\View;
use engine\util\Path;
use engine\theme\Page;

abstract class Controller
{
  protected $module;
  protected $modules;
  protected $route;
  protected $setting;

  protected $model;
  protected $page;
  protected $view;
  protected $user;

  protected static $instances = [];

  public function __construct()
  {
    class_alias('engine\\theme\\Asset', 'Asset');
    class_alias('engine\\theme\\Form', 'Form');
    class_alias('engine\\theme\\Page', 'Page');
    class_alias('engine\\theme\\Template', 'Template');
    class_alias('engine\\theme\\Theme', 'Theme');

    $this->module = Module::get();
    $this->modules = Module::list();
    $this->route = Route::get();
    $this->setting = Setting::get();

    $this->model = $this->loadModel($this->route['controller']);

    $this->page = new Page();
    $this->view = new View();
    $this->user = new User();

    self::$instances[get_called_class()] = $this;
  }

  public static function getInstance()
  {
    $calledClass = get_called_class();

    if (isset(self::$instances[$calledClass])) {
      return self::$instances[$calledClass];
    }

    return new $calledClass();
  }

  public function error($code = '404')
  {
    $this->view->error($code);

    return true;
  }

  public function hasModel()
  {
    return !empty($this->model);
  }

  public function getModel()
  {
    return $this->model;
  }

  protected function loadModel($modelName, $module = null)
  {
    $model = Path::class('model', $module) . '\\' . $modelName;

    if (class_exists($model)) {
      return new $model;
    }

    return null;
  }
}
