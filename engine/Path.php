<?php

namespace Engine;

class Path {
	public static function class($class_name = '', $module = null) {
		$module = $module ?? Module::getName();

		switch(strtolower($class_name)) {
			case 'controller': {
				return '\\Module\\' . ucfirst($module) . '\\Controller';
			}
			case 'model': {
				return '\\Module\\' . ucfirst($module) . '\\Model';
			}
			case 'view': {
				return '\\Module\\' . ucfirst($module) . '\\View';
			}
		}

		return null;
	}

	public static function file($section = '', $module = null) {
		$module = $module ?? Module::getName();

		switch(strtolower($section)) {
			case 'engine': {
				return ROOT_DIR . '/engine';
			}
			case 'log': {
				return ROOT_DIR . '/' . trim(LOG['folder'], '/');
			}
			case 'module': {
				return ROOT_DIR . '/module';
			}
			case 'theme': {
				return ROOT_DIR . '/theme';
			}
			case 'upload': {
				return ROOT_DIR . '/' . trim(UPLOAD['folder'], '/');
			}
			case 'language': {
				$path = ROOT_DIR . "/module/$module/language";

				if($module === 'public') {
					$path = self::file('theme', $module) . '/language';
				}

				return $path;
			}
			case 'controller': {
				return ROOT_DIR . "/module/$module/controller";
			}
			case 'model': {
				return ROOT_DIR . "/module/$module/model";
			}
			case 'view': {
				$path = ROOT_DIR . "/module/$module/view";

				if($module === 'public') {
					$path = self::file('theme', $module);
				}

				return $path;
			}
			case 'asset': {
				return self::file('view', $module) . '/asset';
			}
			case 'form': {
				return self::file('view', $module) . '/form';
			}
			case 'fields': {
				return self::file('view', $module) . '/fields';
			}
			case 'filter': {
				return self::file('view', $module) . '/filter';
			}
			case 'mail': {
				return self::file('view', $module) . '/mail';
			}
			case 'config': {
				$path = ROOT_DIR . '/config.php';

				if($module) {
					$path = self::file('module', $module) . "/$module/config.php";
				}

				return $path;
			}
			case 'temp': {
				return sys_get_temp_dir();
				// Use below path if there are problems with sys_get_temp_dir() on shared hosting
				$doc_root = $_SERVER['DOCUMENT_ROOT'];
				return substr($doc_root, 0, strpos($doc_root, 'data')) . 'data/tmp';
			}
			case 'cache': {
				return self::file('temp', $module) . '/' . trim(CACHE['folder'], '/');
			}
		}

		return ROOT_DIR;
	}

	public static function url($section = '', $module = null) {
		$url_base = Request::$base;

		$module = $module ?? Module::getName();

		switch(strtolower($section)) {
			case 'upload': {
				return $url_base . '/' . trim(UPLOAD['folder'], '/');
			}
			case 'theme': {
				return $url_base . '/theme';
			}
			case 'view': {
				$path = $url_base . "/module/$module/view";

				if($module === 'public') {
					$path = self::url('theme', $module);
				}

				return $path;
			}
			case 'asset': {
				return self::url('view', $module) . '/asset';
			}
		}

		return $url_base;
	}
}
