<?php

namespace engine\http;

class Session
{
  public static function initialize()
  {
    if (headers_sent()) {
      return false;
    }

    return session_start();
  }

  public static function set($key, $data = null)
  {
    $_SESSION[$key] = $data;

    return true;
  }

  public static function has($key)
  {
    return isset($_SESSION[$key]);
  }

  public static function get($key = null)
  {
    return isset($key) ? @$_SESSION[$key] : $_SESSION;
  }

  public static function flush($key = null)
  {
    if (isset($key) && self::has($key)) {
      unset($_SESSION[$key]);
    } else {
      $_SESSION = [];
    }

    return true;
  }
}
