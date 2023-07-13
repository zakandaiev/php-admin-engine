<?php

namespace Engine;

class Router {
	public static $route = [];

	public static function initialize() {
		self::loadModuleRoutes();
		self::checkRoutes();
		// self::checkForm(); TODO
		self::check404();
		self::setController();
	}

	private static function loadModuleRoutes() {
		foreach(Module::get() as $module) {
			if(!$module['is_enabled']) {
				continue;
			}

			Module::setName($module['name']);

			$path = Path::file('module');
			$name = Module::getName();

			$routes = "$path/$name/routes.php";

			if(is_file($routes)) {
				require $routes;
			}
		}

		Module::setName(null);

		return true;
	}

	private static function checkRoutes() {
		foreach(Module::get() as $module) {
			if(!$module['is_enabled']) {
				continue;
			}

			if(isset($module['routes']) && !empty($module['routes'])) {
				foreach($module['routes'] as $route) {
					if(self::checkRoute($module['name'], $route)) {
						return true;
					}
				}
			}
		}

		return false;
	}

	private static function checkRoute($module, $route) {
		Module::setName($module);

		$method = strtolower(trim($route['method'] ?? ''));

		if(
			($method === 'any' || $method === Request::$method)
			&& self::isRouteMatched($route['path'])
		) {
			foreach($route as $key => $value) {
				self::$route[$key] = $value;
			}

			$language = Request::$uri_parts[0];

			if(Language::has($language)) {
				Language::setCurrent($language);
			}

			Module::loadHooks();
			Module::setName($module);

			return true;
		}

		Module::setName(null);

		self::$route = [];

		return false;
	}

	private static function isRouteMatched($route) {
		$parameters = [];

		self::$route['parameters'] = $parameters;

		if($route === '/' && Request::$uri === '/') {
			return true;
		}

		$route = $route === '/' ? $route : rtrim($route ?? '', '/');
		$uri = Request::$uri === '/' ? Request::$uri : rtrim(Request::$uri ?? '', '/');

		$route_parts = explode('/', $route);
		array_shift($route_parts);
		$uri_parts = Request::$uri_parts;

		if(Language::has($uri_parts[0])) {
			array_shift($uri_parts);

			if($route === '/' && count($uri_parts) === 0) {
				return true;
			}
		}

		if(count($route_parts) !== count($uri_parts)) {
			return false;
		}

		for($__i__ = 0; $__i__ < count($route_parts); $__i__++) {
			$route_part = $route_parts[$__i__];

			if(preg_match('/^[$]/', $route_part)) {
				$found_variable = ltrim($route_part ?? '', '$');
				$parameters[$found_variable] = $uri_parts[$__i__];
			} else if($route_parts[$__i__] !== $uri_parts[$__i__]) {
				return false;
			}
		}

		self::$route['parameters'] = $parameters;

		return true;
	}

	private static function checkForm() {
		if(Request::$method !== 'post') {
			return false;
		}

		$statement = new Statement('SELECT * FROM {form}');

		$forms = $statement->execute()->fetchAll();

		if(empty($forms)) {
			return false;
		}

		$timestamp_now = time();

		foreach($forms as $form) {
			$timestamp_created = strtotime($form->date_created);
			$timestamp_diff = $timestamp_now - $timestamp_created;

			if(trim(Request::$uri ?? '', '/') === $form->token) {
				Module::loadHooks();
				Module::setName($form->module);

				if($timestamp_diff < LIFETIME['form']) {
					Form::execute($form->action, $form->form_name, $form->item_id);
				} else {
					$error_message = __('Current form is already inactive. Reload the page and try again');

					Server::answer(null, 'error', $error_message, 409);
				}

				exit;
			}
		}

		return false;
	}

	private static function check404() {
		if(empty(self::$route)) {
			Module::setName('public');

			if(str_starts_with(Request::$uri, '/admin')) {
				Module::setName('admin');
			}

			if(Request::$method !== 'get') {
				Server::answer([], 'error', 'Request not found', 404);
			}

			self::$route['method'] = Request::$method;
			self::$route['path'] = Request::$uri;
			self::$route['controller'] = 'Error';
			self::$route['action'] = 'get404';
			self::$route['options'] = [
				'is_public' => true,
				'breadcrumbs' => []
			];
			self::$route['parameters'] = [];

			return true;
		}

		return false;
	}

	private static function setController() {
		foreach(self::$route as $key => $value) {
			Route::$$key = $value;
		}

		if(is_closure(self::$route['controller'])) {
			self::$route['controller'](self::$route['parameters']);
			return true;
		}

		$controller_class = Path::class('controller') . '\\' . ucfirst(self::$route['controller']);

		if(class_exists($controller_class)) {
			$controller_action = self::$route['action'];

			if(method_exists($controller_class, $controller_action)) {
				$controller = new $controller_class;
				$controller->$controller_action();
			} else {
				throw new \Exception(sprintf('Action %s does not exist in %s.', $controller_action, $controller_class));
			}
		} else {
			throw new \Exception(sprintf('Controller %s does not exist.', $controller_class));
		}

		return true;
	}
}
