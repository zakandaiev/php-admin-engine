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
		return isset($key) ? @$_SESSION[$key] : $_SESSION;
	}

	public static function has($key) {
		return isset($_SESSION[$key]);
	}

	public static function set($key, $data = null) {
		$_SESSION[$key] = $data;

		return true;
	}

	public static function flush($key = null) {
		if(isset($key) && self::has($key)) {
			unset($_SESSION[$key]);
		}
		else {
			$_SESSION = [];
		}

		return true;
	}

	public static function getCookie($key = null) {
		return isset($key) ? @$_COOKIE[$key] : $_COOKIE;
	}

	public static function hasCookie($key) {
		return isset($_COOKIE[$key]);
	}

	public static function setCookie($key, $value = null, $lifetime = null) {
		$_COOKIE[$key] = $value;

		return setcookie($key, $value, time() + intval($lifetime ?? LIFETIME['auth']), '/', '', false, true);
	}

	public static function flushCookie($key = null) {
		if(isset($key) && self::hasCookie($key)) {
			self::setCookie($key, '', 0);
		}
		else {
			foreach($_COOKIE as $key => $value) {
				self::setCookie($key, '', 0);
			}
		}

		return true;
	}
}
