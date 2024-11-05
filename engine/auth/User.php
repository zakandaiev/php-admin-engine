<?php

namespace engine\auth;

use engine\Config;
use engine\database\Query;
use engine\http\Cookie;
use engine\http\Request;
use engine\util\Hash;

class User
{
  protected static $data;
  protected static $cookieKey;
  protected static $cookieLifetime;
  protected static $bindSessionToIp;

  protected static $storedUsersById = [];

  public function __construct()
  {
    self::$cookieKey = self::getCookieKey();
    self::$cookieLifetime = self::getCookieLifetime();
    self::$bindSessionToIp = self::getBindSessionToIp();

    self::$data = new \stdClass();
    self::$data->id = null;
    self::$data->isAuthorized = false;

    if (!Cookie::has(self::$cookieKey)) {
      return $this;
    }

    $bindIp = self::$bindSessionToIp ? 'AND auth_ip=:auth_ip' : '';
    $sql = "
        SELECT
          *
        FROM
          {user}
        WHERE
          auth_token=:auth_token
          AND is_enabled IS true
          $bindIp
        ORDER BY
          date_created DESC
        LIMIT 1
      ";

    $query = new Query($sql);

    $binding = ['auth_token' => Cookie::get(self::$cookieKey)];
    if (self::$bindSessionToIp) {
      $binding['auth_ip'] = Request::ip();
    }

    $user = $query->execute($binding)->fetch();

    if ($user) {
      self::$data = self::format($user, true);
    }

    return $this;
  }

  public static function set($key = null, $data = null)
  {
    if (!isset($key) && !isset($data)) {
      self::$data = new \stdClass();
      self::$data->id = null;
      self::$data->isAuthorized = false;

      return true;
    }

    if (!isset($key) && isset($data)) {
      self::$data = $data;

      return true;
    }

    self::$data->$key = $data;

    return true;
  }

  public static function has($key)
  {
    return isset(self::$data->$key);
  }

  public static function get($key = null)
  {
    return isset($key) ? @self::$data->$key : self::$data;
  }

  public static function getCookieKey()
  {
    return isset(self::$cookieKey) ? self::$cookieKey : Config::getProperty('authKey', 'cookie');
  }

  public static function getCookieLifetime()
  {
    return isset(self::$cookieLifetime) ? self::$cookieLifetime : Config::getProperty('auth', 'lifetime');
  }

  public static function getBindSessionToIp()
  {
    return isset(self::$bindSessionToIp) ? self::$bindSessionToIp : Config::getProperty('bindSessionToIp', 'auth');
  }

  public static function getUserById($id)
  {
    if (isset(self::$storedUsersById[$id])) {
      return self::$storedUsersById[$id];
    }

    $sql = 'SELECT * FROM {user} WHERE id=:id';
    $query = new Query($sql);
    $user = $query->execute(['id' => $id])->fetch();
    if (!$user) {
      return false;
    }

    $user = self::format($user);
    self::$storedUsersById[$id] = $user;

    return $user;
  }

  public static function format($user, $isAuthorized = null)
  {
    if (!$user) {
      return $user;
    }

    $user->isAuthorized = $isAuthorized === true ? true : false;
    $user->fullname = !empty($user->name) ? "{$user->name} ($user->email)" : $user->email;
    $user->setting = isJson($user->setting) ? json_decode($user->setting) : new \stdClass();
    $user->setting->notifications = isset($user->setting->notifications) && isJson($user->setting->notifications) ? json_decode($user->setting->notifications) : new \stdClass();

    return $user;
  }

  public static function authorize($user, $lifetime = null)
  {
    if (empty($user)) {
      return false;
    }

    $user->ip = Request::ip();
    $user->auth_token = Hash::token();

    $cookieKey = self::getCookieKey();
    $lifetime = $lifetime ?? self::getCookieLifetime();

    $sql = '
    	UPDATE {user} SET
    		auth_token=:auth_token,
    		auth_ip=:auth_ip,
    		auth_date=CURRENT_TIMESTAMP
    	WHERE
        id=:user_id
    ';

    $query = new Query($sql);
    $queryResult = $query->execute(['user_id' => $user->id, 'auth_ip' => $user->ip, 'auth_token' => $user->auth_token]);
    if ($queryResult->hasError()) {
      return false;
    }

    Cookie::set($cookieKey, $user->auth_token, $lifetime);

    self::$data = self::format($user, true);

    // TODO
    // Notification::create('user_authorize', $user->id, ['ip' => $user->ip]);
    // Log::write("User ID: {$user->id} logged in from IP: {$user->ip}", 'user');
    // Hook::run('user.authorize', $user);

    return true;
  }

  public static function unauthorize()
  {
    $userId = self::get('id');
    $userIp = Request::ip();
    $cookieKey = self::getCookieKey();

    self::set();
    Cookie::flush($cookieKey);

    // TODO
    // Log::write("User ID: $userId logged out from IP: $userIp", 'user');
    // Hook::run('user.unauthorize', self::$data);

    return true;
  }

  public static function register($name, $email, $password)
  {
    if (empty($name) || empty($email) || empty($password)) {
      return false;
    }

    $sql = '
    	INSERT INTO {user}
    		(id, name, email, password)
    	VALUES
        (:id, :name, :email, :password)
    ';

    $query = new Query($sql);
    $queryResult = $query->execute(['id' => Hash::token(), 'name' => $name, 'email' => $email, 'password' => Hash::password($password)]);
    if ($queryResult->hasError()) {
      return false;
    }

    // TODO
    // Notification::create('user_register', $user->id, ['ip' => $user->ip]);
    // Mail::send('Register', $user->email, $user);

    // $user_ip = Request::ip();
    // Log::write("User ID: {$user->id} registered from IP: {$user->ip}", 'user');

    // Hook::run('user.register', $user);

    return true;
  }

  public static function restore($user)
  {
    if (empty($user)) {
      return false;
    }

    $user->password = Hash::token();

    $sql = '
    	UPDATE {user} SET
    		password=:password
    	WHERE
        id=:user_id
    ';

    $query = new Query($sql);
    $queryResult = $query->execute(['user_id' => $user->id, 'password' => Hash::password($user->password)]);
    if ($queryResult->hasError()) {
      return false;
    }

    self::$data = self::format($user);

    // TODO
    // Notification::create('user_restore', $user->id, ['ip' => Request::ip()]);
    // Mail::send('Restore', $email, $user);

    // $user_ip = Request::ip();
    // Log::write("User ID: {$user->id} restored password from IP: $user_ip", 'user');

    // Hook::run('user.restore', $user);

    return true;
  }
}
