<?php

namespace Engine;

class View
{
	protected static $data = [];

	public function render($template, $is_required = true)
	{
		$module_name = Module::getName();
		$module_extends = Module::get('extends');

		if ($module_extends) {
			Module::setName($module_extends);
			Template::load('functions', false, $module_extends);
			Module::setName($module_name);
		}

		Template::load('functions', false);

		Template::load($template, $is_required, $module_name);

		exit;
	}

	public function error($code)
	{
		http_response_code($code);

		$this->render('error/' . $code);

		exit;
	}

	public static function getData($key = null)
	{
		return isset($key) ? @self::$data[$key] : self::$data;
	}

	public static function hasData($key)
	{
		return isset(self::$data[$key]);
	}

	public static function setData($key, $data = null)
	{
		if (is_string($key)) {
			self::$data[$key] = $data;
		} else {
			self::$data = array_merge(self::$data, $key);
		}

		return true;
	}
}
