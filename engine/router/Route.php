<?php

namespace engine\router;

use engine\router\Router;
use engine\http\Request;
use engine\i18n\I18n;
use engine\util\Path;
use engine\util\Text;

class Route
{
  protected static $data = [];

  public static function has($key)
  {
    return isset(self::$data[$key]);
  }

  public static function set($key, $data = null)
  {
    self::$data[$key] = $data;

    return true;
  }

  public static function get($key = null)
  {
    return isset($key) ? @self::$data[$key] : self::$data;
  }

  public static function link($routeName, $routeParams = null, $routeQuery = null, $moduleName = null)
  {
    $routeParams = $routeParams ?? [];
    $routeQuery = $routeQuery ?? [];

    $uri = $routeName;

    $route = Router::get($routeName, $moduleName);
    if (isset($route['path'])) {
      $uri = preg_replace_callback('/\$(\w+)/iu', function ($matches) use ($routeParams) {
        $key = $matches[1];
        return isset($routeParams[$key]) ? $routeParams[$key] : $matches[0];
      }, $route['path'] ?? '');

      $uri = trim($uri, '/');
    }

    $base = Request::base();
    $language = I18n::getCurrent();
    $query = http_build_query($routeQuery);
    $query = !empty($query) ? "?$query" : '';
    $url = Path::resolveUrl($base, $language, $uri . $query);

    return Text::html($url);
  }

  // public static function linkRaw($routeLink = null, $routeQuery = null)
  // {
  //   $routeLink = $routeLink ?? '/';
  //   $routeQuery = $routeQuery ?? [];
  //
  //   $uri = trim($routeLink, '/');
  //   $base = Request::base();
  //   $language = I18n::getCurrent();
  //   $query = http_build_query($routeQuery ?? []);
  //   $query = !empty($query) ? "?$query" : '';
  //   $url = Path::resolveUrl($base, $language, $uri . $query);

  //   return Text::html($url);
  // }

  public static function isActive($routeName, $routeParams = null, $moduleName = null)
  {
    $routeParams = $routeParams ?? [];

    $uri = $routeName;

    $route = Router::get($routeName, $moduleName);
    if (isset($route['path'])) {
      $uri = preg_replace_callback('/\$(\w+)/iu', function ($matches) use ($routeParams) {
        $key = $matches[1];
        return isset($routeParams[$key]) ? $routeParams[$key] : $matches[0];
      }, $route['path'] ?? '');

      $uri = trim($uri, '/');
    }

    return self::compareUri($uri, Request::uri());
  }

  public static function compareUri($url1, $url2)
  {
    $path1 = parse_url($url1)['path'] ?? $url1;
    $path2 = parse_url($url2)['path'] ?? $url2;

    $path1 = trim($path1 ?? '', '/');
    $path2 = trim($path2 ?? '', '/');

    $path1 = explode('?', $path1)[0];
    $path2 = explode('?', $path2)[0];

    $pathParts1 = explode('/', $path1);
    $pathParts2 = explode('/', $path2);

    if (I18n::has($pathParts1[0])) {
      array_shift($pathParts1);
    }

    if (I18n::has($pathParts2[0])) {
      array_shift($pathParts2);
    }

    $partsFrom = $pathParts1;
    $partsTo = $pathParts2;

    if (count($pathParts2) > count($pathParts1)) {
      $partsFrom = $pathParts2;
      $partsTo = $pathParts1;
    }

    foreach ($partsFrom as $key => $partFrom) {
      $partTo = @$partsTo[$key];

      if ($partFrom === '**' || $partTo === '**') {
        return true;
      }

      if ($partFrom !== $partTo && $partFrom !== '*' && $partTo !== '*') {
        return false;
      }
    }

    return true;
  }

  public static function changeQuery($params = null, $returnUri = null)
  {
    $params = $params ?? [];
    $returnUri = $returnUri ?? false;

    $newParams = [...Request::get(), ...$params];

    $base = $returnUri ? '' : null;
    $uri = Request::uri();
    $query = http_build_query($newParams);
    $query = !empty($query) ? "?$query" : '';

    return Path::resolveUrl($base, $uri . $query);
  }
}
