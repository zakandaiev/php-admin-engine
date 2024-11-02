<?php

namespace engine;

use engine\Config;
use engine\http\Session;
use engine\http\Request;
use engine\database\Database;
use engine\module\Setting;
use engine\module\Module;
use engine\i18n\I18n;
use engine\router\Router;

class Engine
{
  const PHP_MIN = '7.4.0';
  const NAME = 'PHP Admin Engine';
  const VERSION = '1.0.0';
  const AUTHOR = 'Zakandaiev';
  const AUTHOR_URL = 'https://zakandaiev.com';
  const REPOSITORY_URL = 'https://github.com/zakandaiev/php-admin-engine';
  const WEBSITE_URL = 'https://github.com/zakandaiev/php-admin-engine';

  protected static $config = [];
  protected static $debugData = [];
  protected static $isDebug;
  protected static $timeStart;
  protected static $timeEnd;
  protected static $timeResult;

  public static function start()
  {
    Config::initialize();

    self::$isDebug = Config::getProperty('isEnabled', 'debug') ?? false;
    if (self::$isDebug) {
      ini_set('display_errors', '1');
      ini_set('display_startup_errors', '1');
      error_reporting(E_ALL);
    }

    self::$config = Config::get('engine');
    self::$timeStart = hrtime(true);

    $isInstalled = Config::getProperty('isInstalled', 'engine') ?? false;
    if (!$isInstalled) {
      // TODO create install module
      exit;
    }

    // Order matters
    Session::initialize();
    Request::initialize();
    Database::initialize();
    Setting::initialize();
    Module::initialize();
    I18n::initialize();
    Router::initialize();

    self::$config['isReady'] = true;
    Config::set('engine', self::$config);

    Router::watch();
  }

  public static function stop()
  {
    Database::finalize();

    self::$timeEnd = hrtime(true);
    self::$timeResult = self::$timeEnd - self::$timeStart;
    self::$timeResult /= 1e+6; // convert ns to ms
    self::addDebugData('Total execution time: ' . self::$timeResult . ' ms');

    self::renderDebugData();
  }

  public static function addDebugData($data)
  {
    self::$debugData[] = $data;
  }

  protected static function renderDebugData()
  {
    if (!self::$isDebug) {
      return false;
    }

    foreach (self::$debugData as $key => $data) {
      echo "<!-- $data -->";

      if (isset(self::$debugData[$key + 1])) {
        echo "\n\n";
      }
    }

    return true;
  }
}
