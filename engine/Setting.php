<?php

namespace Engine;

class Setting {
	private static $setting;

	public static function initialize() {
		self::$setting = self::load();

		return true;
	}

	public static function get($module = null) {
		return $module ? (self::$setting->{$module} ?? new \stdClass()) : self::$setting;
	}

	public static function update($module, $name, $value = null) {
		if(is_bool($value)) {
			$value = $value ? 'true' : 'false';
		}

		$params = ['module' => $module, 'name' => $name, 'value' => $value];

		$statement = new Statement('UPDATE {setting} SET value = :value WHERE module = :module AND name = :name');

		$statement->execute($params);

		// TODO
		// Log::write('Setting: ' . $name . ' changed by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'setting');
		// Hook::run('setting_update', $params);
		// Hook::run('setting_update_' . $name, $params);

		return true;
	}

	public static function load() {
		$setting = [];

		$settings = new Statement('SELECT * FROM {setting}');
		$settings = $settings->execute()->fetchAll();

		foreach($settings as $row) {
			$setting[$row->module][$row->name] = is_json($row->value) ? json_decode($row->value) : $row->value;
		}

		return json_decode(json_encode($setting));
	}
}
