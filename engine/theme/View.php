<?php

namespace engine\theme;

use engine\module\Module;
use engine\theme\Template;

class View
{
  protected static $data = [];

  public function render($template, $isRequired = true)
  {
    $moduleName = Module::getName();
    $moduleExtends = Module::getProperty('extends');

    if ($moduleExtends) {
      Module::setName($moduleExtends);
      Template::load('functions', false, $moduleExtends);
      Module::setName($moduleName);
    }

    Template::load('functions', false);

    Template::load($template, $isRequired, $moduleName);
  }

  public function error($code)
  {
    http_response_code($code);

    $this->render('error/' . $code);
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
