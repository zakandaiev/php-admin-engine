<?php

namespace Engine;

class Theme {
	const PART_DIR = 'part';
	const PART_MASK = [
		'header'	=> 'header-%s',
		'footer'	=> 'footer-%s',
		'sidebar'	=> 'sidebar-%s',
		'widget'	=> 'widget-%s',
		'menu'	=> 'menu-%s',
		'breadcrumb'	=> 'breadcrumb-%s',
		'pagination'	=> 'pagination-%s'
	];

	protected static $theme_page_templates = [];

	public static function header($name = '', $data = []) {
		self::loadTemplate(__FUNCTION__, $name, $data);
	}

	public static function footer($name = '', $data = []) {
		self::loadTemplate(__FUNCTION__, $name, $data);
	}

	public static function sidebar($name = '', $data = []) {
		self::loadTemplate(__FUNCTION__, $name, $data);
	}

	public static function widget($name = '', $data = []) {
		self::loadTemplate(__FUNCTION__, $name, $data);
	}

	public static function breadcrumb($name = '', $data = []) {
		$data['items'] = Page::get(__FUNCTION__)->items;
		$data['options'] = Page::get(__FUNCTION__)->options;

		self::loadTemplate(__FUNCTION__, $name, $data);
	}

	// TODO
	// public static function menu($name, $data = []) {
	// 	$data[__FUNCTION__] = Menu::get($name);

	// 	self::loadTemplate(__FUNCTION__, $name, $data);
	// }

	public static function pagination($name = '', $data = []) {
		$data[__FUNCTION__] = Pagination::getInstance();

		self::loadTemplate(__FUNCTION__, $name, $data);
	}

	protected static function loadTemplate($type, $name = '', $data = []) {
		$file = self::detectNameFile($name, $type);
		$file = self::PART_DIR . '/' . $file;

		View::setData($data);
		Template::load($file, true);
	}

	public static function block($name, $data = []) {
		$file = self::PART_DIR . '/' . $name;

		View::setData($data);
		Template::load($file, true);
	}

	public static function pageTemplates() {
		if(!empty(self::$theme_page_templates)) {
			return self::$theme_page_templates;
		}

		$path = Path::file('theme');

		foreach(glob($path . '/*.php') as $template) {
			$template_name = file_name($template);

			if($template_name === 'functions' || $template_name === 'home' || $template_name === 'page' || $template_name === 'category') {
				continue;
			}

			self::$theme_page_templates[] = $template_name;
		}

		return self::$theme_page_templates;
	}

	protected static function detectNameFile($name, $function) {
		if(empty($name)) {
			return $function;
		}
		else if($name[0] === '/') {
			return sprintf(str_replace('-', '', self::PART_MASK[$function]), $name);
		}
		else {
			return sprintf(self::PART_MASK[$function], $name);
		}
	}
}
