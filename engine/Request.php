<?php

namespace Engine;

class Request {
	protected static $request = [];
	protected static $cookie = [];
	protected static $files = [];
	protected static $server = [];

	protected static $method;
	protected static $protocol;
	protected static $host;
	protected static $base;
	protected static $uri;
	protected static $uri_full;
	protected static $uri_parts = [];
	protected static $url;
	protected static $referer;
	protected static $ip;
	protected static $csrf_token;

	public static function initialize() {
		self::$request		= $_REQUEST;
		self::$cookie			= $_COOKIE;
		self::$files			= $_FILES;
		self::$server			= $_SERVER;

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

		if(self::$method === 'get') {
			self::$csrf_token = self::setCSRF();
		}
		else if(!self::verifyCSRF()) {
			Server::answer(null, 'error', 'Bad Request', 400);
		}

		return true;
	}

	public static function get($key = null) {
		return isset($key) ? @self::$request[$key] : self::$request;
	}

	public static function has($key) {
		return isset(self::$request[$key]);
	}

	public static function cookie($key = null) {
		return isset($key) ? @self::$cookie[$key] : self::$cookie;
	}

	public static function files($key = null) {
		return isset($key) ? @self::$files[$key] : self::$files;
	}

	public static function server($key = null) {
		return isset($key) ? @self::$server[$key] : self::$server;
	}

	public static function method() {
		return self::$method;
	}

	public static function protocol() {
		return self::$protocol;
	}

	public static function host() {
		return self::$host;
	}

	public static function base() {
		return self::$base;
	}

	public static function uri() {
		return self::$uri;
	}

	public static function uri_full() {
		return self::$uri_full;
	}

	public static function uri_parts($key = null) {
		return isset($key) ? @self::$uri_parts[$key] : self::$uri_parts;
	}

	public static function url() {
		return self::$url;
	}

	public static function referer() {
		return self::$referer;
	}

	public static function ip() {
		return self::$ip;
	}

	public static function csrf_token() {
		return self::$csrf_token;
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
		$token_request = self::$request[$token_key] ?? '';
		$token_session = Session::get($token_key) ?? '';

		if(hash_equals($token_request, $token_session)) {
			return true;
		}

		return false;
	}
}
