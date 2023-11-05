<?php

namespace Engine;

use \PDO;
use \PDOException;
use \Exception;

class Database
{
	protected static $connection;
	protected static $is_connected = false;

	public static function initialize()
	{
		if (!self::$connection instanceof PDO) {
			self::$connection = self::connect();
		}

		return true;
	}

	public static function finalize()
	{
		self::$connection = null;

		return true;
	}

	public static function connection()
	{
		return self::$connection;
	}

	public static function is_connected()
	{
		return self::$is_connected;
	}

	protected static function connect()
	{
		if (!defined('DATABASE')) {
			throw new Exception('Database config is missed');
		}

		if (empty(DATABASE)) {
			throw new Exception('Database config is empty');
		}

		extract(DATABASE);

		if (!isset($host) || !isset($name) || !isset($username) || !isset($password) || !isset($charset) || !isset($prefix)) {
			throw new Exception('Database config is invalid');
		}

		$dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $host, $name, $charset);

		try {
			$connection = new PDO($dsn, $username, $password, @$options);

			self::$is_connected = true;
		} catch (PDOException $error) {
			throw new Exception($error->getMessage());
		}

		return $connection ?? null;
	}
}
