<?php

namespace engine\database;

use \PDO;
use \PDOException;
use \Exception;

use engine\Config;

class Database
{
  protected static $connection;
  protected static $prefix;
  protected static $isConnected = false;

  public static function initialize()
  {
    if (!self::$connection instanceof PDO) {
      self::$connection = self::connect();
    }

    return true;
  }

  public static function finalize()
  {
    self::$connection = null;

    return true;
  }

  public static function isConnected()
  {
    return self::$isConnected;
  }

  public static function getConnection()
  {
    return self::$connection;
  }

  public static function getPrefix()
  {
    return self::$prefix;
  }

  protected static function connect()
  {
    $isConfigExists = Config::exists('database');
    if (!$isConfigExists) {
      throw new Exception('Database config is missed.');
    }

    $config = Config::get('database');
    if (!is_array($config) || empty($config)) {
      throw new Exception('Database config is empty.');
    }

    extract($config);

    $isConfigValid = isset($host) && isset($name) && isset($username) && isset($password) && isset($charset) && isset($prefix);
    if (!$isConfigValid) {
      throw new Exception('Database config is invalid.');
    }

    self::$prefix = $prefix;

    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $host, $name, $charset);

    try {
      $connection = new PDO($dsn, $username, $password, @$options);

      self::$isConnected = true;
    } catch (PDOException $error) {
      throw new Exception($error->getMessage());
    }

    return $connection ?? null;
  }

  public static function isTableExists($table)
  {
    if (!self::isConnected()) {
      return false;
    }

    $replacement = '$1';
    if (!empty(self::$prefix)) {
      $replacement = self::$prefix . '_$1';
    }

    $sql = preg_replace('/{([\w\d\-\_]+)}/miu', $replacement, "SELECT 1 FROM {{$table}} LIMIT 1");

    try {
      $result = self::getConnection()->query($sql);
    } catch (Exception $e) {
      return false;
    }

    return $result !== false;
  }
}
