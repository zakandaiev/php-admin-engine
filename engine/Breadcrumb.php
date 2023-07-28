<?php

namespace Engine;

class Breadcrumb {
	private static $crumbs = [];
	private static $options = [
		'render_homepage' => false,
		'homepage_name' => '',
		'homepage_url' => '',
		'separator' => ''
	];

	public static function add($name, $url = null, $key = null) {
		$crumb = new \stdClass();

		$crumb->name = $name;
		$crumb->url = $url;

		if(isset($key)) {
			self::$crumbs[$key] = $crumb;
		}
		else {
			self::$crumbs[] = $crumb;
		}

		return true;
	}

	public static function get($key = null) {
		return isset($key) ? @self::$crumbs[$key] : self::$crumbs;
	}

	public static function has($key) {
		return isset(self::$crumbs[$key]);
	}

	public static function set($key = null, $data = null) {
		if(isset($key)) {
			self::$crumbs[$key] = $data;
			return true;
		}

		if(!is_array($data)) {
			return false;
		}

		self::$crumbs = [];

		foreach($data as $value) {
			if(is_object($value)) {
				self::add($value->name, @$value->url, @$value->key);
				continue;
			}

			$values = explode('@', $value, 3);

			self::add($values[0], @$values[1], @$values[2]);
		}

		return true;
	}

	public static function edit($key, $name = null, $url = null) {
		if(!isset(self::$crumbs[$key])) {
			return false;
		}

		if(!empty($name)) {
			self::$crumbs[$key]->name = $name;
		}

		if(!empty($url)) {
			self::$crumbs[$key]->url = $url;
		}

		return true;
	}

	public static function getOption($key = null) {
		return isset($key) ? @self::$options[$key] : self::$options;
	}

	public static function setOption($key, $data = null) {
		self::$options[$key] = $data;

		return true;
	}
}
