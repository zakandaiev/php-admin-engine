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
				$path = self::file('module') . "/$module/language";

				if($module === 'public') {
					$path = self::file('theme') . '/language';
				}

				return $path;
			}
			case 'controller': {
				return self::file('module') . "/$module/controller";
			}
			case 'model': {
				return self::file('module') . "/$module/model";
			}
			case 'view': {
				$path = self::file('module') . "/$module/view";

				if($module === 'public') {
					$path = self::file('theme');
				}

				return $path;
			}
			case 'asset': {
				return self::file('view', $module) . '/asset';
			}
			case 'form': {
				$path = self::file('module') . "/$module/form";

				if($module === 'public') {
					$path = self::file('theme') . '/form';
				}

				return $path;
			}
			case 'field': {
				$path = self::file('module') . "/$module/field";

				if($module === 'public') {
					$path = self::file('theme') . '/field';
				}

				return $path;
			}
			case 'filter': {
				$path = self::file('module') . "/$module/filter";

				if($module === 'public') {
					$path = self::file('theme') . '/filter';
				}

				return $path;
			}
			case 'mail': {
				$path = self::file('module') . "/$module/mail";

				if($module === 'public') {
					$path = self::file('theme') . '/mail';
				}

				return $path;
			}
			case 'config': {
				return self::file('module') . "/$module/config.php";
			}
			case 'temp': {
				if(!IS_SHARED_HOSTING) {
					return sys_get_temp_dir();
				}

				$doc_root = $_SERVER['DOCUMENT_ROOT'];

				return substr($doc_root, 0, strpos($doc_root, 'data')) . 'data/tmp';
			}
			case 'cache': {
				return self::file('temp') . '/' . trim(CACHE['folder'], '/');
			}
		}

		return ROOT_DIR;
	}

	public static function url($section = '', $module = null) {
		$url_base = Request::$base;

		$module = $module ?? Module::getName();

		switch(strtolower($section)) {
			case 'module': {
				return $url_base . '/module';
			}
			case 'theme': {
				return $url_base . '/theme';
			}
			case 'view': {
				$path = self::url('module') . "/$module/view";

				if($module === 'public') {
					$path = self::url('theme');
				}

				return $path;
			}
			case 'asset': {
				return self::url('view', $module) . '/asset';
			}
			case 'upload': {
				return $url_base . '/' . trim(UPLOAD['folder'], '/');
			}
		}

		return $url_base;
	}
}
