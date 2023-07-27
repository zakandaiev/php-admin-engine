<?php

namespace Engine;

class Module {
	private static $name;
	private static $list = [];
	private static $is_hooks_loaded = false;

	public static function initialize() {
		self::loadModules();

		return true;
	}

	public static function get($key = null, $name = null) {
		if(!isset($key) && !isset($name)) {
			return self::$list;
		}

		$name = $name ?? self::$name;

		if(
			(!isset($key) && isset($name))
			|| $key == 'all'
		) {
			return self::$list[$name];
		}

		if($key === 'languages' && empty($name)) {
			$name = Module::get('extends') ?? $name;
		}

		$value = self::$list[$name][$key] ?? null;

		if($key === 'languages' && empty($value)) {
			$name = Module::get('extends') ?? $name;
			$value = self::$list[$name][$key] ?? null;
		}

		return $value;
	}

	public static function has($name) {
		return isset(self::$list[$name]);
	}

	public static function set($key, $data = null, $name = null) {
		$name = $name ?? self::$name;

		if(!isset($name) || !self::has($name)) {
			return false;
		}

		self::$list[$name][$key] = $data;

		return true;
	}

	public static function getName() {
		return self::$name;
	}

	public static function setName($name) {
		self::$name = $name;

		return true;
	}

	private static function loadModules() {
		$module_path = Path::file('module');

		$modules = [];

		foreach(scandir($module_path) as $module) {
			if(in_array($module, ['.', '..'], true)) continue;

			$config_file = $module_path . '/' . $module . '/config.php';

			if(is_file($config_file)) {
				$config = require $config_file;
			} else {
				continue;
			}

			if(!is_array($config) || empty($config)) {
				throw new \Exception(sprintf($module . '\'s config module file %s is invalid.', $config_file));
			}

			$config['name'] = $config['name'] ?? $module;
			$config['languages'] = [];
			$config['routes'] = [];

			$modules[$config['name']] = $config;
		}

		uasort($modules, function ($module1, $module2) {
			if(isset($module1['priority']) && isset($module2['priority'])) {
				return $module2['priority'] <=> $module1['priority'];
			}
			return 0;
		});

		self::$list = $modules;

		return true;
	}

	// TODO
	public static function loadHooks() {
		if(self::$is_hooks_loaded) {
			return false;
		}

		self::$is_hooks_loaded = true;

		$path = Path::file('module');

		$modules = self::$list;

		// uasort($modules, function ($module1, $module2) {
		// 	if(isset($module1['priority']) && isset($module2['priority'])) {
		// 		return $module1['priority'] <=> $module2['priority'];
		// 	}
		// 	return 0;
		// });

		foreach($modules as $module) {
			if(!$module['is_enabled']) {
				continue;
			}

			self::setName($module['name']);

			$hooks = "$path/{$module['name']}/hooks.php";

			if($module['name'] === 'public') {
				$hooks = Path::file('theme') . '/hooks.php';
			}

			if(is_file($hooks)) {
				require $hooks;
			}
		}

		self::setName(null);

		return true;
	}






	// TODO
	public static function update($key, $value, $module = null) {
		$name = $module ?? self::$name;
		$config_file = Path::file('module') . '/' . $name . '/config.php';

		if(!is_file($config_file)) {
			return false;
		}

		if(is_numeric($value)) {
			$value = $value;
		} else if(is_string($value)) {
			$value = "'$value'";
		} else if(is_bool($value)) {
			$value = $value ? 'true' : 'false';
		} else if(is_null($value)) {
			$value = 'null';
		} else {
			return false;
		}

		$config_content = file_get_contents($config_file);

		$replacement = "'$key' => $value";

		if(preg_match('/([\'"]' . $key . '[\'"][\s]*=>)/mi', $config_content)) {
			$pattern = '/([\'"]' . $key . '[\'"][\s]*=>[\s]*[^,\]\n\/#]+)/mi';
		} else {
			$pattern = '/(return[\s]*(\[|array\())/mi';
			$replacement = "$1\n\t" . $replacement . ",";
		}

		$config_content = preg_replace($pattern, $replacement, $config_content);

		static $is_edited = false;

		if(file_put_contents($config_file, $config_content, LOCK_EX)) {
			if(!$is_edited) {
				Log::write('Module: ' . $name. ' edited by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'module');
				Hook::run('module_update', $name);
			}

			$is_edited = true;

			return true;
		}

		return false;
	}

	public static function delete($name) {
		Log::write('Module: ' . $name. ' deleted by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'module');

		Hook::run('module_delete', $name);

		return rmdir_recursive(Path::file('module') . '/' . $name);
	}

	public static function install($name) {
		$path = Path::file('module') . '/' . $name . '/Install';

		if(!file_exists($path)) {
			return false;
		}

		// INSTALL SCRIPT
		$path_install = $path . '/install.php';

		if(is_file($path_install)) {
			require $path_install;
		}

		Log::write('Module: ' . $name. ' installed by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'module');

		Hook::run('module_install', $name);

		return true;
	}

	public static function uninstall($name) {
		$path = Path::file('module') . '/' . $name . '/Install';

		if(!file_exists($path)) {
			return false;
		}

		// UNINSTALL SCRIPT
		$path_uninstall = $path . '/uninstall.php';

		if(is_file($path_uninstall)) {
			require $path_uninstall;
		}

		Log::write('Module: ' . $name. ' uninstalled by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'module');

		Hook::run('module_uninstall', $name);

		return true;
	}
}
