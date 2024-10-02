<?php

namespace engine\http;

use engine\Config;

class Cookie
{
  public static function set($key, $value = null, $lifetime = null)
  {
    $_COOKIE[$key] = $value;

    $lifetime = isset($lifetime) ? intval($lifetime) : Config::getProperty('auth', 'lifetime');

    return setcookie($key, $value, time() + $lifetime, '/', '', false, true);
  }

  public static function has($key)
  {
    return isset($_COOKIE[$key]);
  }

  public static function get($key = null)
  {
    return isset($key) ? @$_COOKIE[$key] : $_COOKIE;
  }

  public static function flush($key = null)
  {
    if (isset($key) && self::has($key)) {
      self::set($key, null, 0);
    } else {
      foreach ($_COOKIE as $key => $value) {
        self::set($key, $value, 0);
      }
    }

    return true;
  }
}
