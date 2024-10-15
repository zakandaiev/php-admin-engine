<?php

namespace engine\util;

use engine\Config;
use engine\http\Request;
use engine\module\Module;

class Path
{
  public static function class($className = '', $module = null)
  {
    $module = $module ?? Module::getName();
    $className = strtolower($className);

    switch ($className) {
      case 'engine': {
          return "\\engine";
        }
      case 'module': {
          return "\\module";
        }
      case 'controller': {
          return "\\module\\$module\\controller";
        }
      case 'model': {
          return "\\module\\$module\\model";
        }
      case 'view': {
          return "\\module\\$module\\view";
        }
      case 'form': {
          return "\\module\\$module\\form";
        }
    }

    return null;
  }

  public static function file($section = '', $module = null)
  {
    $module = $module ?? Module::getName();
    $section = strtolower($section);

    switch ($section) {
      case 'engine':
      case 'module':
      case 'theme': {
          return self::resolve(true, $section);
        }
      case 'log':
      case 'upload': {
          return self::resolve(true, trim(Config::getProperty('folder', $section), '/'));
        }
      case 'controller':
      case 'model': {
          return self::resolve(self::file('module'), $module, $section);
        }
      case 'i18n':
      case 'form':
      case 'field':
      case 'filter':
      case 'mail': {
          if ($module === 'frontend') {
            return self::resolve(self::file('theme'), $module, $section);
          }

          return self::resolve(self::file('module'), $module, $section);
        }
      case 'view': {
          if ($module === 'frontend') {
            return self::file('theme');
          }

          return self::resolve(self::file('module'), $module, $section);
        }
      case 'asset':
      case 'error':
      case 'page':
      case 'template': {
          return self::resolve(self::file('view', $module), $section);
        }
      case 'config': {
          return self::resolve(self::file('module'), $module, 'config.php');
        }
      case 'temp': {
          if (Config::getProperty('isSharedHosting', 'engine') === true) {
            $docRoot = $_SERVER['DOCUMENT_ROOT'];

            return substr($docRoot, 0, strpos($docRoot, 'data')) . 'data/tmp';
          }

          return sys_get_temp_dir();
        }
      case 'cache': {
          return self::resolve(self::file('temp'), trim(Config::getProperty('folder', $section), '/'));
        }
    }

    return ROOT_DIR;
  }

  public static function url($section = '', $module = null)
  {
    $urlBase = Request::base();
    $module = $module ?? Module::getName();
    $section = strtolower($section);

    switch ($section) {
      case 'module':
      case 'theme': {
          return self::resolve($urlBase, $section);
        }

      case 'view': {
          if ($module === 'frontend') {
            return self::url('theme');
          }

          return self::resolve(self::url('module'), $module, $section);
        }
      case 'asset': {
          return self::resolve(self::url('view', $module), $section);
        }
      case 'upload': {
          return self::resolve($urlBase, trim(Config::getProperty('folder', $section), '/'));
        }
    }

    return $urlBase;
  }

  public static function resolve(...$args)
  {
    $base = ROOT_DIR;

    if (empty($args)) {
      return $base;
    }

    if (gettype($args[0]) !== 'string') {
      $args[0] = $base;
    }

    $args = array_map(function ($item) {
      return trim($item ?? '', '/');
    }, $args);

    return implode('/', $args);
  }

  public static function resolveUrl(...$args)
  {
    $base = self::url();

    if (empty($args)) {
      return $base;
    }

    if (gettype($args[0]) !== 'string') {
      $args[0] = $base;
    }

    $args = array_map(function ($item) {
      return trim($item ?? '', '/');
    }, $args);

    return implode('/', $args);
  }
}
