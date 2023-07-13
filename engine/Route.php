<?php

namespace Engine;

class Route {
	public static $method = '';
	public static $path = '';
	public static $controller = '';
	public static $action = '';
	public static $options = [];
	public static $parameters = [];

	public static function get($path, $controller, $options = []) {
		self::initRoute(__FUNCTION__, $path, $controller, $options);
	}

	public static function post($path, $controller, $options = []) {
		self::initRoute(__FUNCTION__, $path, $controller, $options);
	}

	public static function put($path, $controller, $options = []) {
		self::initRoute(__FUNCTION__, $path, $controller, $options);
	}

	public static function patch($path, $controller, $options = []) {
		self::initRoute(__FUNCTION__, $path, $controller, $options);
	}

	public static function delete($path, $controller, $options = []) {
		self::initRoute(__FUNCTION__, $path, $controller, $options);
	}

	public static function options($path, $controller, $options = []) {
		self::initRoute(__FUNCTION__, $path, $controller, $options);
	}

	public static function any($path, $controller, $options = []) {
		self::initRoute(__FUNCTION__, $path, $controller, $options);
	}

	private static function initRoute($method, $path, $controller, $options = []) {
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
			'options' => $options
		];

		$routes = Module::get('routes');
		$routes[] = $route;

		Module::set('routes', $routes);

		return true;
	}
}
