<?php

namespace engine\theme;

use engine\Config;
use engine\module\Module;
use engine\router\Route;
use engine\util\Hash;
use engine\util\Path;
use engine\util\Text;

class Asset
{
  const EXTENSION_MASK = [
    'js' => '<script %s src="%s"></script>',
    'css' => '<link rel="stylesheet" %s href="%s">'
  ];

  protected static $container = [];
  protected static $optimization = [
    'js' => [
      'attributes' => null,
      'routes' => null
    ],
    'css' => [
      'attributes' => null,
      'routes' => null
    ]
  ];

  protected static function add($fileExtension, $fileName, $attributes = [], $routes = [], $module = null)
  {
    $filePath = Path::resolve(Path::file('asset', $module), "$fileName.$fileExtension");

    if (is_file($filePath)) {
      $fileUrl = Path::resolveUrl(self::url($module), "$fileName.$fileExtension");

      self::$container[$fileExtension][] = [
        'url' => $fileUrl,
        'attributes' => $attributes,
        'routes' => $routes
      ];

      return true;
    }

    return false;
  }

  public static function css($asset, $attributes = [], $routes = [], $module = null)
  {
    return self::add(__FUNCTION__, $asset, $attributes, $routes, $module);
  }

  public static function js($asset, $attributes = [], $routes = [], $module = null)
  {
    return self::add(__FUNCTION__, $asset, $attributes, $routes, $module);
  }

  // TODO optimization
  // public static function optimization($extension, $attributes = [], $routes = null)
  // {
  //   self::$optimization[$extension]['attributes'] = $attributes;
  //   self::$optimization[$extension]['routes'] = $routes;

  //   return true;
  // }

  public static function render($extension)
  {
    $assets = self::$container[$extension] ?? [];

    if (empty($assets)) {
      return false;
    }

    // TODO optimization
    // $groupSetting = Setting::getProperty('group_' . $extension, 'engine');
    // if(Module::getName() === 'frontend' && $groupSetting != 'false' && !empty($groupSetting)) {
    // 	$assets = [
    // 		[
    // 			'module' => 'frontend',
    // 			'file' => "$extension/$groupSetting.$extension",
    // 			'attributes' => self::$optimization[$extension]['attributes'],
    // 			'routes' => self::$optimization[$extension]['routes']
    // 		]
    // 	];
    // }

    $version = Config::getProperty('isEnabled', 'debug') ? Hash::token(8) : Module::getProperty('version');

    $output = '';

    foreach ($assets as $asset) {
      // TODO refactor & test
      // if (!self::checkRoute($asset['routes'])) {
      //   continue;
      // }

      $assetUrl = $asset['url'] . '?version=' . $version;

      $assetAttributes = '';
      if (!empty($asset['attributes'])) {
        $assetAttributes = implode(' ', array_map(function ($key, $value) {
          return $key . '="' . Text::html($value) . '"';
        }, array_keys($asset['attributes']), $asset['attributes']));
      }

      $output .= sprintf(
        self::EXTENSION_MASK[$extension],
        $assetAttributes,
        $assetUrl
      ) . PHP_EOL;
    }

    return $output;
  }

  public static function url($module = null)
  {
    return Path::url('asset', $module ?? Module::getProperty('extends'));
  }

  public static function getContent($fileName, $module = null)
  {
    $dir = Path::file('asset', $module);
    $pathToFile = Path::resolveUrl($dir, $fileName);

    if (!is_file($pathToFile) && Module::getProperty('extends')) {
      return self::getContent($fileName, Module::getProperty('extends'));
    }

    if (is_file($pathToFile)) {
      return file_get_contents($pathToFile);
    }

    return null;
  }

  // TODO
  // public static function setContainer($key, $data = null)
  // {
  //   self::$container[$key] = $data;

  //   return true;
  // }

  // public static function hasContainer($extension)
  // {
  //   return isset(self::$container[$extension]);
  // }

  // public static function getContainer($extension = null)
  // {
  //   return isset($extension) ? @self::$container[$extension] : self::$container;
  // }

  // protected static function checkRoute($routes = null)
  // {
  //   if (empty($routes)) {
  //     return true;
  //   }

  //   $routes = is_array($routes) ? $routes : (!empty($routes) ? [$routes] : []);

  //   foreach ($routes as $route) {
  //     if (Route::isActive($route)) {
  //       return true;
  //     }
  //   }

  //   return false;
  // }
}
