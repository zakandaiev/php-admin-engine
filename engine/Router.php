<?php

namespace Engine;

class Router
{
	protected static $route = [];

	public static function initialize()
	{
		self::loadModuleRoutes();
		self::checkForm();
		self::checkRoutes();
		self::check404();
		self::setController();
	}

	public static function register($method, $path, $controller, $option = [])
	{
		if (!in_array($method, ['get', 'post', 'put', 'patch', 'delete', 'options', 'any'])) {
			throw new \Exception(sprintf('Invalid method declaration for %s route in %s module.', $path, Module::getName()));
			return false;
		}

		if (is_closure($controller)) {
			$route_controller = $controller;
			$route_action = null;
		} else {
			@list($route_controller, $route_action) = explode('@', $controller, 2);

			if (empty($route_controller) || empty($route_action)) {
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

	protected static function loadModuleRoutes()
	{
		foreach (Module::get() as $module) {
			if (!$module['is_enabled']) {
				continue;
			}

			Module::setName($module['name']);

			$path = Path::file('module');
			$name = Module::getName();

			$routes = "$path/$name/routes.php";

			if (is_file($routes)) {
				require $routes;
			}
		}

		Module::setName(null);

		return true;
	}

	protected static function checkForm()
	{
		if (Request::method() === 'get') {
			return false;
		}

		$token = Request::uri_parts(0);

		if (empty($token)) {
			return false;
		}

		$statement = new Statement('SELECT * FROM {form} WHERE token = :token ORDER BY date_created DESC LIMIT 1');

		$form = $statement->execute(['token' => Request::uri_parts(0)])->fetch();

		if (empty($form)) {
			return false;
		}

		if (Request::ip() !== $form->ip) {
			Server::answer(null, 'error', __('engine.form.forbidden'), 403);
		}

		$timestamp_now = time();
		$timestamp_created = strtotime($form->date_created);
		$timestamp_diff = $timestamp_now - $timestamp_created;

		if ($timestamp_diff > LIFETIME['form']) {
			Server::answer(null, 'error', __('engine.form.inactive'), 405);
		}

		Module::loadHooks();
		Module::setName($form->module);

		self::initRoute();

		new User();

		Form::execute($form->action, $form->form_name, $form->item_id, $form->is_translation);

		Server::answer();

		return true;
	}

	protected static function checkRoutes()
	{
		foreach (Module::get() as $module) {
			if (!$module['is_enabled']) {
				continue;
			}

			if (isset($module['routes']) && !empty($module['routes'])) {
				foreach ($module['routes'] as $route) {
					if (self::checkRoute($module['name'], $route)) {
						return true;
					}
				}
			}
		}

		return false;
	}

	protected static function checkRoute($module, $route)
	{
		Module::setName($module);

		$language = Request::uri_parts(0);

		if (Language::has($language)) {
			Language::setCurrent($language);
		}

		$method = strtolower(trim($route['method'] ?? ''));

		if (
			($method === 'any' || $method === Request::method())
			&& self::isRouteMatched($route['path'])
		) {
			foreach ($route as $key => $value) {
				self::$route[$key] = $value;
			}

			Module::loadHooks();
			Module::setName($module);

			return true;
		}

		Module::setName(null);

		self::$route = [];

		return false;
	}

	protected static function isRouteMatched($route)
	{
		$parameter = [];

		self::$route['parameter'] = $parameter;

		if ($route === '/' && Request::uri() === '/') {
			return true;
		}

		$route = $route === '/' ? $route : rtrim($route ?? '', '/');
		$uri = Request::uri() === '/' ? Request::uri() : rtrim(Request::uri() ?? '', '/');

		$route_parts = explode('/', $route);
		array_shift($route_parts);
		$uri_parts = Request::uri_parts();

		if (Language::has($uri_parts[0])) {
			array_shift($uri_parts);

			if ($route === '/' && count($uri_parts) === 0) {
				return true;
			}
		}

		if (count($route_parts) !== count($uri_parts)) {
			return false;
		}

		for ($__i__ = 0; $__i__ < count($route_parts); $__i__++) {
			$route_part = $route_parts[$__i__];

			if (preg_match('/^[$]/', $route_part)) {
				$found_variable = ltrim($route_part ?? '', '$');
				$parameter[$found_variable] = $uri_parts[$__i__];
			} else if ($route_parts[$__i__] !== $uri_parts[$__i__]) {
				return false;
			}
		}

		self::$route['parameter'] = $parameter;

		return true;
	}

	protected static function check404()
	{
		if (empty(self::$route)) {
			if (Request::method() !== 'get') {
				Server::answer(null, 'error', 'Not found', 404);
			}

			$module_name = 'public';

			foreach (Module::get() as $module) {
				Module::setName($module['name']);

				$uri_parts = Request::uri_parts();
				if (Language::has($uri_parts[0])) {
					array_shift($uri_parts);
				}

				if ($uri_parts[0] === $module['name']) {
					$module_name = $module['name'];
				} else if ($uri_parts[0] === $module['extends']) {
					$module_name = $module['extends'];
				}
			}

			Module::setName($module_name);

			self::$route['method'] = Request::method();
			self::$route['path'] = Request::uri();
			self::$route['controller'] = 'Error';
			self::$route['action'] = 'get404';
			self::$route['option'] = [];
			self::$route['parameter'] = [];

			return true;
		}

		return false;
	}

	protected static function initRoute()
	{
		foreach (self::$route as $key => $value) {
			Route::set($key, $value);
		}

		return true;
	}

	protected static function setController()
	{
		self::initRoute();

		if (is_closure(self::$route['controller'])) {
			self::$route['controller'](self::$route['parameter']);
			return true;
		}

		$controller_class = Path::class('controller') . '\\' . ucfirst(self::$route['controller']);

		if (class_exists($controller_class)) {
			$controller_action = self::$route['action'];

			if (method_exists($controller_class, $controller_action)) {
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
