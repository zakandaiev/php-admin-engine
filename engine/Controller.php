<?php

namespace Engine;

abstract class Controller
{
	protected $module;
	protected $modules;
	protected $route;
	protected $setting;

	protected $user;
	protected $page;
	protected $view;
	protected $model;

	public function __construct()
	{
		$this->module = Module::get('all');
		$this->modules = Module::get();
		$this->route = Route::get();
		$this->setting = Setting::get();

		$this->user = new User();
		$this->page = new Page();
		$this->view = new View();
		$this->model = $this->loadModel($this->route['controller']);
	}

	protected function loadModel($model_name, $module = null)
	{
		$model = Path::class('model', $module) . '\\' . ucfirst($model_name);

		if (class_exists($model)) {
			return new $model;
		}

		return null;
	}

	public function get404()
	{
		$this->view->error('404');

		return true;
	}
}
