<?php

namespace Engine;

class Route
{
	protected static $data = [];

	public static function get($key = null)
	{
		return isset($key) ? @self::$data[$key] : self::$data;
	}

	public static function set($key, $data = null)
	{
		self::$data[$key] = $data;

		return true;
	}

	public static function compare($path1, $path2)
	{
		$path1 = trim($path1 ?? '', '/');
		$path2 = trim($path2 ?? '', '/');

		$path1 = explode('?', $path1)[0];
		$path2 = explode('?', $path2)[0];

		$path_parts1 = explode('/', $path1);
		$path_parts2 = explode('/', $path2);

		$parts_from = $path_parts1;
		$parts_to = $path_parts2;

		if (count($path_parts2) > count($path_parts1)) {
			$parts_from = $path_parts2;
			$parts_to = $path_parts1;
		}

		foreach ($parts_from as $key => $part_from) {
			$part_to = @$parts_to[$key];

			if ($part_from === '**' || $part_to === '**') {
				return true;
			}

			if ($part_from !== $part_to && $part_from !== '*' && $part_to !== '*') {
				return false;
			}
		}

		return true;
	}

	public static function isActive($path)
	{
		return self::compare($path, Request::uri());
	}
}
