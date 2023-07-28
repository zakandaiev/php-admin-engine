<?php

namespace Engine;

class Request {
	public static $method;
	public static $protocol;
	public static $host;
	public static $base;
	public static $uri;
	public static $uri_full;
	public static $uri_parts = [];
	public static $url;
	public static $referer;
	public static $ip;

	public static $get = [];
	public static $post = [];
	public static $request = [];
	public static $cookie = [];
	public static $files = [];
	public static $server = [];

	public static $csrf;

	public static function initialize() {
		self::$method 		= strtolower($_SERVER['REQUEST_METHOD'] ?? 'get');
		self::$protocol 	= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http');
		self::$host 			= $_SERVER['HTTP_HOST'];
		self::$base 			= self::$protocol . '://' . self::$host;
		self::$uri 				= strtok($_SERVER['REQUEST_URI'], '?');
		self::$uri_full 	= $_SERVER['REQUEST_URI'];
		self::$uri_parts 	= explode('/', self::$uri); array_shift(self::$uri_parts);
		self::$url 				= self::$base . self::$uri;
		self::$referer 		= $_SERVER['HTTP_REFERER'] ?? null;
		self::$ip 				= $_SERVER['REMOTE_ADDR'];

		self::$get				= $_GET;
		self::$post				= $_POST;
		self::$request		= $_REQUEST;
		self::$cookie			= $_COOKIE;
		self::$files			= $_FILES;
		self::$server			= $_SERVER;

		self::$csrf = self::setCSRF();

		if(self::$method !== 'get' && !self::verifyCSRF()) {
			Server::answer(null, 'error', 'Bad Request', 400);
		}

		return true;
	}

	public static function get($key = null) {
		return isset($key) ? @self::$get[$key] : self::$get;
	}

	public static function has($key) {
		return isset(self::$get[$key]);
	}

	public static function setCSRF() {
		$token_key = COOKIE_KEY['csrf'];
		$token = Session::has($token_key) ? Session::get($token_key) : null;

		if(empty($token)) {
			$token = Hash::token();
			Session::set($token_key, $token);
		}

		return $token;
	}

	public static function verifyCSRF() {
		$token_key = COOKIE_KEY['csrf'];
		$token_post = self::$post[$token_key] ?? '';
		$token_session = Session::get($token_key) ?? '';

		if(hash_equals($token_post, $token_session)) {
			return true;
		}

		return false;
	}
}
