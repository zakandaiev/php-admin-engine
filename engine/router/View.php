<?php

namespace engine\router;

use engine\module\Module;
use engine\theme\Template;
use engine\util\Path;

class View
{
  protected static $data = [];
  protected static $isViewRendered;

  public function render($template, $isRequired = true)
  {
    if (self::$isViewRendered) {
      return false;
    }

    $moduleName = Module::getName();
    $moduleExtends = Module::getProperty('extends');

    if ($moduleExtends) {
      Module::setName($moduleExtends);
      Template::load('functions', false, $moduleExtends);
      Module::setName($moduleName);
    }

    Template::load('functions', false);
    Template::load(Path::resolve('page', $template), $isRequired, $moduleName);

    self::$isViewRendered = true;

    return true;
  }

  public function error($code)
  {
    http_response_code($code);

    return $this->render($code);
  }

  public static function setData($key, $data = null)
  {
    if (is_string($key)) {
      self::$data[$key] = $data;
    } else {
      self::$data = array_merge(self::$data, $key);
    }

    return true;
  }

  public static function hasData($key)
  {
    return isset(self::$data[$key]);
  }

  public static function getData($key = null)
  {
    return isset($key) ? @self::$data[$key] : self::$data;
  }
}
