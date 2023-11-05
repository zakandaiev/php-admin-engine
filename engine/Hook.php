<?php

namespace Engine;

class Hook
{
	protected static $actions = [];
	protected static $data = [];

	public static function register($name, $function)
	{
		if (is_string($name) && !empty($name) && is_closure($function)) {
			self::$actions[$name][] = $function;

			return true;
		}

		return false;
	}

	public static function run()
	{
		$arguments_num = func_num_args();

		if ($arguments_num < 1) {
			return false;
		}

		$arguments = func_get_args();
		$name = array_shift($arguments);

		if (isset(self::$actions[$name]) && !empty(self::$actions[$name])) {
			foreach (self::$actions[$name] as $hook) {
				call_user_func_array($hook, $arguments);
			}

			return true;
		}

		return false;
	}

	public static function has($name)
	{
		if (isset(self::$actions[$name])) {
			return true;
		}

		return false;
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
		self::$data[$key] = $data;

		return true;
	}
}
