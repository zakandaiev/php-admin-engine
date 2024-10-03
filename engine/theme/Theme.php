<?php

namespace engine\theme;

class Theme
{
  const BLOCK_DIR = 'block';
  const BLOCK_MASK = [
    'header'  => 'header-%s',
    'footer'  => 'footer-%s',
    'sidebar'  => 'sidebar-%s',
    'widget'  => 'widget-%s',
    'menu'  => 'menu-%s',
    'breadcrumb'  => 'breadcrumb-%s',
    'pagination'  => 'pagination-%s'
  ];

  protected static $themePageTemplates = [];

  public static function header($name = '', $data = [])
  {
    self::loadTemplate(__FUNCTION__, $name, $data);
  }

  public static function footer($name = '', $data = [])
  {
    self::loadTemplate(__FUNCTION__, $name, $data);
  }

  public static function sidebar($name = '', $data = [])
  {
    self::loadTemplate(__FUNCTION__, $name, $data);
  }

  public static function widget($name = '', $data = [])
  {
    self::loadTemplate(__FUNCTION__, $name, $data);
  }

  public static function breadcrumb($name = '', $data = [])
  {
    $data['items'] = Page::get(__FUNCTION__)->items;
    $data['options'] = Page::get(__FUNCTION__)->options;

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

  protected static function loadTemplate($type, $name = '', $data = [])
  {
    $file = self::detectNameFile($name, $type);
    $file = self::BLOCK_DIR . '/' . $file;

    View::setData($data);
    Template::load($file, true);
  }

  public static function block($name, $data = [])
  {
    $file = self::BLOCK_DIR . '/' . $name;

    View::setData($data);
    Template::load($file, true);
  }

  public static function pageTemplates()
  {
    if (!empty(self::$themePageTemplates)) {
      return self::$themePageTemplates;
    }

    $path = Path::file('theme');

    foreach (glob($path . '/*.php') as $template) {
      $templateName = getFileName($template);

      if ($templateName === 'functions' || $templateName === 'home' || $templateName === 'page' || $templateName === 'category') {
        continue;
      }

      self::$themePageTemplates[] = $templateName;
    }

    return self::$themePageTemplates;
  }

  protected static function detectNameFile($name, $function)
  {
    if (empty($name)) {
      return $function;
    } else if ($name[0] === '/') {
      return sprintf(str_replace('-', '', self::BLOCK_MASK[$function]), $name);
    } else {
      return sprintf(self::BLOCK_MASK[$function], $name);
    }
  }
}
