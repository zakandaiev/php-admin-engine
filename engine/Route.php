<?php

namespace Engine;

class Route
{
	protected static $data = [];

	public static function get($key = null)
	{
		return isset($key) ? @self::$data[$key] : self::$data;
	}

	public static function set($key, $data = null)
	{
		self::$data[$key] = $data;

		return true;
	}

	public static function isRouteActive($route)
	{
		// TODO
		// - refactor
		// - handle route part with * or **

		if (is_array($route)) {
			$state = false;

			foreach ($route as $r) {
				if (self::isRouteActive($r)) {
					$state = true;
				}
			}

			return $state;
		}

		$route_parts = explode('/', $route);
		array_shift($route_parts);
		$uri_parts = Request::uri_parts();

		if (Language::has($uri_parts[0])) {
			array_shift($uri_parts);

			if ($route === '/' && count($uri_parts) === 0) {
				return true;
			}
		}

		foreach ($uri_parts as $key => $part) {
			if ($part !== @$route_parts[$key]) {
				return false;
			}
		}

		return true;
	}
}
