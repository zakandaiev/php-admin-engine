<?php

namespace Engine;

abstract class Controller {
	protected $module;
	protected $route;

	protected $view;
	protected $model;

	protected $setting;

	protected $user;

	protected $page;

	public function __construct() {
		$this->module = Module::get('all');
		$this->modules = Module::get();
		$this->route = Router::$route;

		$this->view = new View();
		$this->model = $this->loadModel($this->route['controller']);

		$this->setting = Setting::get();
		$this->page = new Page();
		$this->user = new User();
	}

	protected function loadModel($model_name, $module = null) {
		$model = Path::class('model', $module) . '\\' . ucfirst($model_name);

		if(class_exists($model)) {
			return new $model;
		}

		return null;
	}

	public function get404() {
		$this->view->error('404');

		return true;
	}
}
