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
