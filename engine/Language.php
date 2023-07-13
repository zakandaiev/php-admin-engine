<?php

namespace Engine;

class Language {
	private static $language = [];
	private static $translation = [];
	private static $translation_flat = [];
	private static $loaded_modules = [];

	public static function initialize() {
		foreach(Module::get() as $module) {
			if(!$module['is_enabled']) {
				continue;
			}

			Module::setName($module['name']);

			$path = Path::file('language');

			$languages = [];

			if(!file_exists($path)) {
				Module::set('languages', $languages);
				continue;
			}

			foreach(scandir($path) as $language) {
				if(in_array($language, ['.', '..'], true)) continue;

				if(file_extension($language) !== 'json') continue;

				@list($language_key, $language_region) = explode('_', file_name($language), 2);

				if(empty($language_key) || empty($language_region)) {
					continue;
				}

				$languages[$language_key] = [
					'key' => $language_key,
					'region' => $language_region,
					'file_name' => $language
				];

				Module::set('languages', $languages);
			}
		}

		Module::setName(null);

		return true;
	}

	public static function get($key, $language = null) {
		return Module::get('languages')[$language ?? self::current()][$key] ?? null;
	}

	public static function getAll($language = null) {
		return Module::get('languages')[$language ?? self::current()] ?? null;
	}

	public static function has($language, $module = null) {
		return isset(Module::get('languages', $module)[$language]);
	}

	public static function list($module_name = null) {
		return Module::get('languages', $module_name);
	}

	public static function translate($key, $data = null) {
		$module_name = Module::getName();

		if(!in_array($module_name, self::$loaded_modules)) {
			self::$loaded_modules[] = $module_name;
			self::loadTranslations($module_name);
		}

		$translation = self::$translation_flat[$key] ?? $key;

		if(isset($data) && is_numeric($data)) {
			@list($nominative, $singular, $plural) = explode(' | ', $translation, 3);

			if(!empty($nominative) && !empty($singular) && !empty($plural)) {
				switch(numerical_noun_form($data)) {
					case 's': {
						$translation = str_replace('{s}', strval($data), $singular);
						break;
					}
					case 'n': {
						$translation = str_replace('{s}', strval($data), $nominative);
						break;
					}
					case 'p': {
						$translation = str_replace('{s}', strval($data), $plural);
						break;
					}
				}
			}
		}
		else if(isset($data) && is_array($data)) {
			foreach($data as $key => $value) {
				$translation = str_replace('{' . strval($key) . '}', strval($value), $translation);
			}
		}
		else if(isset($data)) {
			$translation = str_replace('{s}', strval($data), $translation);
		}

		if(DEBUG['is_enabled'] && !is_array($translation)) {
			$translation = DEBUG['lang_wrap'] . $translation . DEBUG['lang_wrap'];
		}

		return $translation;
	}

	public static function current() {
		$language_from_cookie = Session::hasCookie(COOKIE_KEY['language']) ? Session::getCookie(COOKIE_KEY['language']) : null;

		if(!empty($language_from_cookie) && Language::has($language_from_cookie)) {
			$language = Session::getCookie(COOKIE_KEY['language']);
		} else {
			$language = Setting::get('main')->language ?? null;
		}

		return $language;
	}

	public static function setCurrent($language) {
		if(!self::has($language)) {
			return false;
		}

		Session::setCookie(COOKIE_KEY['language'], $language);

		return true;
	}

	private static function loadTranslations($module) {
		$path_lang = Path::file('language', $module) . '/' . self::get('file_name');

		if(!is_file($path_lang)) {
			return false;
		}

		$content_lang = file_get_contents($path_lang);

		$content_lang = json_decode($content_lang, true);

		if(empty($content_lang)) {
			return false;
		}

		self::$translation = array_merge(self::$translation, $content_lang);

		self::$translation_flat = self::getFlattenTranslations(self::$translation);

		return true;
	}

	private static function getFlattenTranslations($input_arr, $return_arr = array(), $prev_key = '') {
		foreach ($input_arr as $key => $value) {
			$new_key = $prev_key . $key;

			if (is_array($value) && key($value) !==0 && key($value) !==null) {
				$return_arr = array_merge($return_arr, self::getFlattenTranslations($value, $return_arr, $new_key . '.'));
			}
			else {
				$return_arr[$new_key] = $value;
			}
		}

		return $return_arr;
	}
}
