<?php

namespace engine;

class Config
{
  protected static $configList = [];
  protected static $requiredConfigList = ['auth', 'cache', 'cookie', 'database', 'debug', 'engine', 'lifetime', 'log', 'service', 'upload'];

  public static function initialize()
  {
    self::loadFromFolder();

    foreach (self::$requiredConfigList as $configName) {
      if (!self::exists($configName)) {
        throw new \Exception(sprintf('%s config is missed.', $configName));
      }
    }

    return true;
  }

  public static function exists($configName)
  {
    return isset(self::$configList[$configName]);
  }

  public static function list()
  {
    return self::$configList;
  }

  public static function set($configName, $data)
  {
    self::$configList[$configName] = $data;

    return true;
  }

  public static function get($configName)
  {
    return self::exists($configName) ? self::$configList[$configName] : null;
  }

  public static function hasProperty($propertyName, $configName)
  {
    return isset(self::$configList[$configName][$propertyName]);
  }

  public static function setProperty($data, $propertyName, $configName)
  {
    if (!self::exists($configName)) {
      return null;
    }

    self::$configList[$configName][$propertyName] = $data;

    return true;
  }

  public static function getProperty($propertyName, $configName)
  {
    return self::hasProperty($propertyName, $configName) ? self::$configList[$configName][$propertyName] : null;
  }

  public static function loadFromFolder()
  {
    $configPath = ROOT_DIR . '/config';

    $configFiles = is_dir($configPath) ? scandir($configPath) : [];

    foreach ($configFiles as $configFile) {
      if (in_array($configFile, ['.', '..'], true)) {
        continue;
      }

      $config = require $configPath . '/' . $configFile;
      $configName = getFileName($configFile);

      if (!is_array($config)) {
        throw new \Exception(sprintf('%s config is invalid.', $configFile));
      }

      self::set($configName, $config);
    }

    return true;
  }
}
