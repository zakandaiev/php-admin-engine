<?php

namespace engine\theme;

use engine\util\File;
use engine\util\Path;

class Theme
{
  protected static $pageTemplateList = [];

  public static function breadcrumb($name = '', $data = [])
  {
    $data['items'] = Page::get(__FUNCTION__)->items;
    $data['options'] = Page::get(__FUNCTION__)->options;

    self::loadTemplate(__FUNCTION__, $name, $data);
  }

  public static function footer($name = '', $data = [])
  {
    self::loadTemplate(__FUNCTION__, $name, $data);
  }

  public static function header($name = '', $data = [])
  {
    self::loadTemplate(__FUNCTION__, $name, $data);
  }

  public static function menu($name, $data = [])
  {
    $data[__FUNCTION__] = Menu::get($name);

    self::loadTemplate(__FUNCTION__, $name, $data);
  }

  public static function pagination($name = '', $data = [])
  {
    $data[__FUNCTION__] = Pagination::getInstance();

    self::loadTemplate(__FUNCTION__, $name, $data);
  }

  public static function template($name, $data = [])
  {
    self::loadTemplate($name);
  }

  public static function getPageTemplates()
  {
    if (!empty(self::$pageTemplateList)) {
      return self::$pageTemplateList;
    }

    $path = Path::file('theme');

    foreach (glob($path . '/*.php') as $template) {
      $templateName = File::getName($template);

      if ($templateName === 'functions' || $templateName === 'home' || $templateName === 'page' || $templateName === 'category') {
        continue;
      }

      self::$pageTemplateList[] = $templateName;
    }

    return self::$pageTemplateList;
  }

  protected static function loadTemplate($type, $name = '', $data = [])
  {
    $file = Path::resolve('template', $type);

    if (!empty($name)) {
      $file = "$file-$name";
    }

    View::setData($data);
    Template::load($file, true);
  }
}
