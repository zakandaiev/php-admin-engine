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

  protected static $timeStart;
  protected static $timeEnd;
  protected static $timeResult;
  protected static $isDebug;

  public static function start()
  {
    Config::initialize();

    self::$isDebug = Config::getProperty('isEnabled', 'debug') ?? false;
    if (self::$isDebug) {
      ini_set('display_errors', '1');
      ini_set('display_startup_errors', '1');
      error_reporting(E_ALL);

      self::$timeStart = hrtime(true);
    }

    $config = Config::get('engine');

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

    $config['isReady'] = true;
    Config::set('engine', $config);

    Router::watch();
  }

  public static function stop()
  {
    Database::finalize();

    if (self::$isDebug) {
      self::$timeEnd = hrtime(true);
      self::$timeResult = self::$timeEnd - self::$timeStart;
      self::$timeResult /= 1e+6; // convert ns to ms

      echo PHP_EOL . '<!-- Execution time: ' . self::$timeResult . ' ms -->';
    }
  }
}
