<?php

namespace engine\http;

use engine\Config;
use engine\util\Hash;
use engine\http\Response;
use engine\http\Session;

class Request
{
  protected static $request = [];
  protected static $cookie = [];
  protected static $files = [];
  protected static $server = [];

  protected static $method;
  protected static $protocol;
  protected static $host;
  protected static $base;
  protected static $uri;
  protected static $uriFull;
  protected static $uriParts = [];
  protected static $url;
  protected static $referer;
  protected static $ip;
  protected static $csrfToken;

  protected static function loadVariables()
  {
    self::$request = $_REQUEST;
    self::$cookie = $_COOKIE;
    self::$files = $_FILES;
    self::$server = $_SERVER;

    self::$method = strtolower($_SERVER['REQUEST_METHOD'] ?? 'get');
    self::$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http');
    self::$host = $_SERVER['HTTP_HOST'];
    self::$base = self::$protocol . '://' . self::$host;
    self::$uri = strtok($_SERVER['REQUEST_URI'], '?');
    self::$uriFull = $_SERVER['REQUEST_URI'];
    self::$uriParts = explode('/', self::$uri);
    array_shift(self::$uriParts);
    self::$url = self::$base . self::$uri;
    self::$referer = $_SERVER['HTTP_REFERER'] ?? null;
    self::$ip = $_SERVER['REMOTE_ADDR'];

    return true;
  }

  protected static function handleCSRF()
  {
    if (self::$method === 'get') {
      self::$csrfToken = self::setCSRF();
    } else if (!self::verifyCSRF()) {
      Response::answer(null, 'error', 'Forbidden', 403);
    }

    return true;
  }

  public static function initialize()
  {
    self::loadVariables();
    self::handleCSRF();

    return true;
  }

  public static function has($key)
  {
    return isset(self::$request[$key]);
  }

  public static function get($key = null)
  {
    return isset($key) ? @self::$request[$key] : self::$request;
  }

  public static function cookie($key = null)
  {
    return isset($key) ? @self::$cookie[$key] : self::$cookie;
  }

  public static function files($key = null)
  {
    return isset($key) ? @self::$files[$key] : self::$files;
  }

  public static function server($key = null)
  {
    return isset($key) ? @self::$server[$key] : self::$server;
  }

  public static function method()
  {
    return self::$method;
  }

  public static function protocol()
  {
    return self::$protocol;
  }

  public static function host()
  {
    return self::$host;
  }

  public static function base()
  {
    return self::$base;
  }

  public static function uri()
  {
    return self::$uri;
  }

  public static function uriFull()
  {
    return self::$uriFull;
  }

  public static function uriParts($key = null)
  {
    return isset($key) ? @self::$uriParts[$key] : self::$uriParts;
  }

  public static function url()
  {
    return self::$url;
  }

  public static function referer()
  {
    return self::$referer;
  }

  public static function ip()
  {
    return self::$ip;
  }

  public static function csrfToken()
  {
    return self::$csrfToken;
  }

  public static function setCSRF()
  {
    $tokenKey = Config::getProperty('csrfKey', 'cookie');
    $token = Session::has($tokenKey) ? Session::get($tokenKey) : null;

    if (empty($token)) {
      $token = Hash::token();
      Session::set($tokenKey, $token);
    }

    return $token;
  }

  public static function verifyCSRF()
  {
    $tokenKey = Config::getProperty('csrfKey', 'cookie');
    $tokenRequest = self::$request[$tokenKey] ?? '';
    $tokenSession = Session::get($tokenKey) ?? '';

    if (hash_equals($tokenRequest, $tokenSession)) {
      return true;
    }

    return false;
  }
}
