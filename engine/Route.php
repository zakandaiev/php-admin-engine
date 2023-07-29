<?php

namespace Engine;

class Route {
	public static $method = '';
	public static $path = '';
	public static $controller = '';
	public static $action = '';
	public static $option = [];
	public static $parameter = [];

	public static function get($path, $controller, $option = []) {
		self::initRoute(__FUNCTION__, $path, $controller, $option);
	}

	public static function post($path, $controller, $option = []) {
		self::initRoute(__FUNCTION__, $path, $controller, $option);
	}

	public static function put($path, $controller, $option = []) {
		self::initRoute(__FUNCTION__, $path, $controller, $option);
	}

	public static function patch($path, $controller, $option = []) {
		self::initRoute(__FUNCTION__, $path, $controller, $option);
	}

	public static function delete($path, $controller, $option = []) {
		self::initRoute(__FUNCTION__, $path, $controller, $option);
	}

	public static function options($path, $controller, $option = []) {
		self::initRoute(__FUNCTION__, $path, $controller, $option);
	}

	public static function any($path, $controller, $option = []) {
		self::initRoute(__FUNCTION__, $path, $controller, $option);
	}

	public static function isRouteActive($route) {
		if(is_array($route)) {
			$state = false;

			foreach($route as $r) {
				if(self::isRouteActive($r)) {
					$state = true;
				}
			}

			return $state;
		}

		$route_parts = explode('/', $route);
		array_shift($route_parts);
		$uri_parts = Request::$uri_parts;

		if(Language::has($uri_parts[0])) {
			array_shift($uri_parts);

			if($route === '/' && count($uri_parts) === 0) {
				return true;
			}
		}

		foreach($uri_parts as $key => $part) {
			if($part !== $route_parts[$key]) {
				return false;
			}
		}

		return true;
	}

	private static function initRoute($method, $path, $controller, $option = []) {
		if(is_closure($controller)) {
			$route_controller = $controller;
			$route_action = null;
		}
		else {
			@list($route_controller, $route_action) = explode('@', $controller, 2);

			if(empty($route_controller) || empty($route_action)) {
				throw new \Exception(sprintf('Invalid controller declaration for %s route in %s module.', $path, Module::getName()));
				return false;
			}
		}

		$route = [
			'method' => $method,
			'path' => $path,
			'controller' => $route_controller,
			'action' => $route_action,
			'option' => $option
		];

		$routes = Module::get('routes');
		$routes[] = $route;

		Module::set('routes', $routes);

		return true;
	}
}
