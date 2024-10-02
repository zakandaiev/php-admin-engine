<?php

namespace engine\theme;

use engine\module\Module;
use engine\theme\View;
use engine\util\Path;

class Template
{
  public static function load($template, $isRequired = true, $module = null)
  {
    $templatePath = Path::file('view', $module) . "/$template.php";

    if (!is_file($templatePath) && Module::getProperty('extends')) {
      $templatePath = Path::file('view', Module::getProperty('extends')) . "/$template.php";
    }

    if (!is_file($templatePath)) {
      if ($isRequired) {
        throw new \Exception(sprintf('Template %s not found in %s.', $template, $templatePath));
      } else {
        return false;
      }
    }

    extract(View::getData());

    ob_start();
    ob_implicit_flush(0);

    try {
      include $templatePath;
    } catch (\Exception $e) {
      ob_end_clean();
      throw $e;
    }

    echo ob_get_clean();

    return true;
  }

  public static function has($template, $module = null)
  {
    $templatePath = Path::file('view', $module) . "/$template.php";

    if (!is_file($templatePath) && Module::getProperty('extends')) {
      $templatePath = Path::file('view', Module::getProperty('extends')) . "/$template.php";
    }

    if (!is_file($templatePath)) {
      return false;
    }

    return true;
  }
}