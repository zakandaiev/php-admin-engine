<?php

namespace engine\util;

use engine\Config;
use engine\module\Hook;
use engine\util\Log;
use engine\util\File;

class Cache
{
  const CACHE_KEY = [
    'data' => 'data',
    'expires' => 'expires'
  ];

  public static function set($key, $data = null, $lifetime = null)
  {
    $name = md5($key);
    $extension = trim(Config::getProperty('extension', 'cache'), '.');

    $path = Path::resolve(Path::file('cache'), $name, $extension);

    $content[self::CACHE_KEY['data']] = $data;
    $content[self::CACHE_KEY['expires']] = time() + intval($lifetime ?? Config::getProperty('cache', 'lifetime'));

    $content = serialize($content);

    File::createFile($path, $content);

    Log::write("add $name", 'cache');
    Hook::run('cache.add', $name);

    return true;
  }

  public static function get($key)
  {
    $name = md5($key);
    $extension = trim(Config::getProperty('extension', 'cache'), '.');

    $path = Path::resolve(Path::file('cache'), $name, $extension);

    $content = File::getContent($path);
    if (!$content) {
      return false;
    }

    $content = unserialize($content);

    if (time() <= $content[self::CACHE_KEY['expires']]) {
      return $content[self::CACHE_KEY['data']];
    }

    return false;
  }

  public static function delete($key)
  {
    $name = md5($key);
    $extension = trim(Config::getProperty('extension', 'cache'), '.');

    $path = Path::resolve(Path::file('cache'), $name, $extension);

    File::delete($path);

    Log::write("delete $name", 'cache');
    Hook::run('cache.delete', $name);

    return true;
  }

  public static function flush()
  {
    $path = Path::file('cache');

    if (!file_exists($path)) {
      return false;
    }

    foreach (scandir($path) as $file) {
      if (in_array($file, ['.', '..'], true)) continue;

      if (File::getExtension($file) !== trim(Config::getProperty('extension', 'cache'), '.')) continue;

      unlink($path . '/' . $file);
    }

    Log::write('flush', 'cache');
    Hook::run('cache.flush');

    return true;
  }
}
