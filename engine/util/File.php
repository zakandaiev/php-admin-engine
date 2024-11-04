<?php

namespace engine\util;

class File
{
  public static function getDirectory($path)
  {
    return pathinfo($path, PATHINFO_DIRNAME);
  }

  public static function getName($path)
  {
    return pathinfo($path, PATHINFO_FILENAME);
  }

  public static function getBaseName($path)
  {
    return pathinfo($path, PATHINFO_BASENAME);
  }

  public static function getExtension($path)
  {
    return pathinfo($path, PATHINFO_EXTENSION);
  }

  public static function getSize($path)
  {
    $size = 0;

    if (is_file($path)) {
      $size = filesize($path);
    } else {
      foreach (self::globRecursive($path . '/*.*') as $file) {
        $size += filesize($file);
      }
    }

    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    for ($i = 0; $size > 1024; $i++) $size /= 1024;

    return round($size, 2) . ' ' . $units[$i];
  }

  public static function getContent($pathToFile)
  {
    if (is_file($pathToFile)) {
      return file_get_contents($pathToFile);
    }

    return null;
  }

  public static function createDir($directory, $permissions = 0755, $recursive = true)
  {
    if (!file_exists($directory)) {
      return mkdir($directory, $permissions, $recursive);
    }

    return false;
  }

  public static function createFile($path, $content = PHP_EOL, $flags = 0)
  {
    $directory = self::getDirectory($path);

    self::createDir($directory);

    return file_put_contents($path, $content, $flags);
  }

  public static function delete($path)
  {
    if (is_dir($path)) {
      return rmdir($path);
    } else if (is_file($path)) {
      return unlink($path);
    }

    return false;
  }

  public static function deleteRecurcive($path)
  {
    if (is_dir($path)) {
      $files = array_diff(scandir($path), array('.', '..'));

      foreach ($files as $file) {
        self::deleteRecurcive(realpath($path) . '/' . $file);
      }

      return rmdir($path);
    } else if (is_file($path)) {
      return unlink($path);
    }

    return false;
  }

  public static function globRecursive($pattern, $flags = 0)
  {
    $files = glob($pattern, $flags);

    foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
      $files = array_merge($files, self::globRecursive($dir . '/' . basename($pattern), $flags));
    }

    return $files;
  }
}
