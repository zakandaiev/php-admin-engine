<?php

namespace Engine;

class Engine {
	const PHP_MIN = '7.4.0';
	const NAME = 'PHP Admin Engine';
	const VERSION = '1.0.0';
	const AUTHOR = 'Zakandaiev';
	const AUTHOR_URL = 'https://zakandaiev.com';

	private static $time_start;

	public static function start() {
		if(DEBUG['is_enabled']) {
			ini_set('display_errors', '1');
			ini_set('display_startup_errors', '1');
			error_reporting(E_ALL);

			self::$time_start = hrtime(true);
		}

		class_alias('\\Engine\\Engine', 'Engine');
		class_alias('\\Engine\\Hash', 'Hash');
		class_alias('\\Engine\\Path', 'Path');
		class_alias('\\Engine\\Session', 'Session');
		class_alias('\\Engine\\Request', 'Request');
		class_alias('\\Engine\\Server', 'Server');
		class_alias('\\Engine\\Database', 'Database');
		class_alias('\\Engine\\Statement', 'Statement');
		class_alias('\\Engine\\Filter', 'Filter');
		class_alias('\\Engine\\Setting', 'Setting');
		class_alias('\\Engine\\User', 'User');
		class_alias('\\Engine\\Module', 'Module');
		class_alias('\\Engine\\Language', 'Language');
		class_alias('\\Engine\\Hook', 'Hook');
		class_alias('\\Engine\\Router', 'Router');
		class_alias('\\Engine\\Route', 'Route');
		class_alias('\\Engine\\Controller', 'Controller');
		class_alias('\\Engine\\Model', 'Model');
		class_alias('\\Engine\\View', 'View');
		class_alias('\\Engine\\Form', 'Form');
		class_alias('\\Engine\\Asset', 'Asset');
		class_alias('\\Engine\\Theme', 'Theme');
		class_alias('\\Engine\\Template', 'Template');
		class_alias('\\Engine\\Page', 'Page');
		class_alias('\\Engine\\Cache', 'Cache');
		class_alias('\\Engine\\Log', 'Log');
		// class_alias('\\Engine\\Mail', 'Mail');
		class_alias('\\Engine\\Upload', 'Upload');
		class_alias('\\Engine\\Breadcrumb', 'Breadcrumb');
		class_alias('\\Engine\\Pagination', 'Pagination');
		// class_alias('\\Engine\\Menu', 'Menu');
		// class_alias('\\Engine\\Notification', 'Notification');
		// class_alias('\\Engine\\Sitemap', 'Sitemap');

		// Order matters
		Session::initialize();
		Request::initialize();
		Database::initialize();
		Setting::initialize();
		Module::initialize();
		Language::initialize();
		Router::initialize();
	}

	public static function stop() {
		Database::finalize();

		if(DEBUG['is_enabled']) {
			$time_end = hrtime(true);
			$time_result = $time_end - self::$time_start;
			$time_result /= 1e+6; // convert ns to ms

			echo "\n<!-- Execution time: $time_result ms -->";
		}
	}
}
