<?php

namespace engine\util;

use engine\Config;
use engine\auth\User;
use engine\http\Request;
use engine\util\Date;
use engine\util\File;
use engine\util\Path;

class Log
{
  public static function exists($file, $folder = null)
  {
    $configFileExtension = Config::getProperty('extension', 'log');

    $fileExtension = $configFileExtension ? ".$configFileExtension" : '';
    $fileName = trim($file, '/');

    $pathBase = Path::file('log');
    $pathFolder = $folder ? Path::resolve($pathBase, $folder) : $pathBase;
    $path = Path::resolve($pathFolder, $fileName . $fileExtension);

    return is_file($path);
  }

  public static function list()
  {
    function dirToArray($dir)
    {
      $result = [];

      if (!file_exists($dir)) {
        return $result;
      }

      $filesInDir = scandir($dir);

      foreach ($filesInDir as $fileName) {
        if (in_array($fileName, ['.', '..'])) {
          continue;
        }

        $filePath = Path::resolve($dir, $fileName);

        if (is_dir($filePath)) {
          $result[$fileName] = dirToArray($filePath);
        } else if (File::getExtension($fileName) === Config::getProperty('extension', 'log')) {
          $result[] = File::getName($fileName);
        }
      }

      uasort($result, function ($log1, $log2) {
        if (is_string($log1) && is_array($log2)) {
          return 1;
        }

        return 0;
      });

      return $result;
    }

    return dirToArray(Path::file('log'));
  }

  public static function get($file, $folder = null)
  {
    $configFileExtension = Config::getProperty('extension', 'log');

    $fileExtension = $configFileExtension ? ".$configFileExtension" : '';
    $fileName = trim($file, '/');

    $pathBase = Path::file('log');
    $pathFolder = $folder ? Path::resolve($pathBase, $folder) : $pathBase;
    $path = Path::resolve($pathFolder, $fileName . $fileExtension);

    return File::getContent($path);
  }

  public static function write($data, $folder = null)
  {
    return self::saveRowToFile(self::formatRow($data), $folder);
  }

  protected static function formatRow($data)
  {
    $dataFormatted = $data;

    if (is_bool($data)) {
      $dataFormatted = $data === true ? 'true' : 'false';
    } else if (is_array($data) || is_object($data)) {
      $dataFormatted = json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    $userId = User::get('isAuthorized') ? User::get('id') : 'unlogged';
    $date = Date::format(null, 'Y-m-d H:i:s');
    $ip = Request::ip();

    $row = "[$date] [$ip] [$userId] - $dataFormatted\n";

    return $row;
  }

  protected static function saveRowToFile($row, $folder = null)
  {
    $configFileExtension = Config::getProperty('extension', 'log');
    $configFileMask = Config::getProperty('fileMask', 'log');

    $fileExtension = $configFileExtension ? ".$configFileExtension" : '';
    $fileName = Date::format(null, $configFileMask ?? 'Y-m-d');

    $pathBase = Path::file('log');
    $pathFolder = $folder ? Path::resolve($pathBase, $folder) : $pathBase;
    $path = Path::resolve($pathFolder, $fileName . $fileExtension);

    return File::createFile($path, $row, FILE_APPEND | LOCK_EX);
  }
}
