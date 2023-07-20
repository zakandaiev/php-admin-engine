<?php

namespace Engine;

class Session {
	public static function initialize() {
		if(headers_sent()) {
			return false;
		}

		return session_start();
	}

	public static function get($key = null) {
		return $key ? $_SESSION[$key] : $_SESSION;
	}

	public static function set($key, $data = null) {
		$_SESSION[$key] = $data;

		return true;
	}

	public static function has($key) {
		return isset($_SESSION[$key]);
	}

	public static function flush($key) {
		if(isset($key) && self::has($key)) {
			unset($_SESSION[$key]);
		}
		else {
			$_SESSION = [];
		}

		return true;
	}

	public static function getCookie($key) {
		return $key ? Request::$cookie[$key] : Request::$cookie;
	}

	public static function setCookie($key, $value, $lifetime = null) {
		Request::$cookie[$key] = $value;

		return setcookie($key, $value, time() + intval($lifetime ?? LIFETIME['auth']), '/', '', false, true);
	}

	public static function hasCookie($key) {
		return isset(Request::$cookie[$key]);
	}

	public static function flushCookie($key) {
		if(isset($key) && self::hasCookie($key)) {
			self::setCookie($key, '', 0);
		}
		else {
			foreach(Request::$cookie as $key => $value) {
				self::setCookie($key, '', 0);
			}
		}

		return true;
	}

	public static function getCookieAll() {
		return Request::$cookie;
	}
}
