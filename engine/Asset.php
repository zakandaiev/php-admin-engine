<?php
namespace Engine;

class Asset {
	const EXTENSION_MASK = [
		'js' => '<script src="%s"%s></script>',
		'css' => '<link href="%s" rel="stylesheet"%s>'
	];

	private static $container = [];
	private static $optimization = [
		'js' => [
			'attributes' => null,
			'routes' => null
		],
		'css' => [
			'attributes' => null,
			'routes' => null
		]
	];

	private static function add($extension, $file_name, $attributes = '', $routes = null, $module = null) {
		$file_path = Path::file('asset') . "/$file_name.$extension";

		if(is_file($file_path)) {
			self::$container[$extension][] = [
				'module' => $module ?? Module::getName(),
				'file' => "$file_name.$extension",
				'attributes' => $attributes,
				'routes' => $routes
			];

			return true;
		}

		return false;
	}

	public static function css($asset, $attributes = null, $routes = null, $module = null) {
		return self::add(__FUNCTION__, $asset, $attributes, $routes, $module);
	}

	public static function js($asset, $attributes = null, $routes = null, $module = null) {
		return self::add(__FUNCTION__, $asset, $attributes, $routes, $module);
	}

	public static function optimization($extension, $attributes = null, $routes = null) {
		self::$optimization[$extension]['attributes'] = $attributes;
		self::$optimization[$extension]['routes'] = $routes;

		return true;
	}

	public static function render($extension) {
		$assets = self::$container[$extension] ?? [];

		if(empty($assets)) {
			return false;
		}

		// TODO
		// $group_setting = @Setting::get('engine')->{'group_' . $extension};
		// if(Module::getName() === 'public' && $group_setting != 'false' && !empty($group_setting)) {
		// 	$assets = [
		// 		[
		// 			'module' => 'public',
		// 			'file' => "$extension/$group_setting.$extension",
		// 			'attributes' => self::$optimization[$extension]['attributes'],
		// 			'routes' => self::$optimization[$extension]['routes']
		// 		]
		// 	];
		// }

		$output = '';

		foreach($assets as $asset) {
			// TODO
			// add * support
			// if(!self::checkRoute($asset['routes'])) {
			// 	continue;
			// }

			$output .= sprintf(
				self::EXTENSION_MASK[$extension],
				Path::url('asset', @$asset['module']) . '/' . $asset['file'] . '?version=' . Module::get('version'),
				!empty($asset['attributes']) ? ' ' . $asset['attributes'] : ''
			) . PHP_EOL;
		}

		return $output;
	}

	public static function url($module = null) {
		return Path::url('asset', $module ?? Module::get('extends'));
	}

	public static function getContainer($extension = null) {
		return isset($extension) ? @self::$container[$extension] : self::$container;
	}

	public static function hasContainer($extension) {
		return isset(self::$container[$extension]);
	}

	public static function setContainer($key, $data = null) {
		self::$container[$key] = $data;

		return true;
	}

	private static function checkRoute($routes = null) {
		if(empty($routes)) {
			return true;
		}

		$routes = is_array($routes) ? $routes : (!empty($routes) ? [$routes] : []);

		$routes = array_map(function($string) {
			return trim(trim(strval($string ?? '')), '/');
		}, $routes);

		$route = trim(trim(Router::$route['uri'] ?? ''), '/');

		if(in_array($route, $routes)) {
			return true;
		}

		return false;
	}
}
