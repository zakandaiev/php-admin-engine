<?php

namespace Engine;

class View {
	protected static $data = [];

	public function render($template, $is_required = true) {
		$module_name = Module::getName();
		$module_extends = Module::get('extends');

		if($module_extends) {
			Module::setName($module_extends);
			Template::load('functions', false, $module_extends);
			Module::setName($module_name);
		}

		Template::load('functions', false);

		Template::load($template, $is_required, $module_name);
	}

	public function error($code) {
		http_response_code($code);

		$this->render('error/' . $code);

		exit;
	}

	// TODO -> transform for keys
	public static function getData() {
		return self::$data;
	}

	// public static function hasData($key) {
	// 	return isset(self::$data[$key]);
	// }

	public static function setData($data) {
		self::$data = $data + self::$data;
	}
}
