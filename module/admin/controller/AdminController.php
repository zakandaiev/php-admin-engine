<?php

namespace Module\Admin\Controller;

use Engine\Controller;
use Engine\Server;

class AdminController extends Controller
{
	protected $model_admin;

	public function __construct()
	{
		parent::__construct();

		$this->model_admin = $this->loadModel('AdminModel', 'admin');

		class_alias('\\Module\\Admin\\Builder\\Form', 'FormBuilder');
		class_alias('\\Module\\Admin\\Builder\\Filter', 'FilterBuilder');
		class_alias('\\Module\\Admin\\Builder\\Interface', 'InterfaceBuilder');

		// TODO - make as function
		// if(!$this->user->get()->authorized) {
		// 	Server::redirect('/admin/login');
		// }

		// // CHECK USER FOR ROUTE ACCESS
		// $is_user_enabled = false;

		// $this->user->access_all = $this->model_admin->getUserAccessAll($this->user->id);
		// $this->user->groups = $this->model_admin->getUserGroups($this->user->id);
		// $this->user->routes = $this->model_admin->getUserRoutes($this->user->id);

		// if((isset($this->route['is_public']) && $this->route['is_public'] === true) || $this->user->access_all) {
		// 	$is_user_enabled = true;
		// } else {
		// 	foreach($this->user->routes as $route) {
		// 		list($method, $uri) = explode('@', $route);

		// 		if($this->route['method'] === $method && $this->route['uri'] === $uri) {
		// 			$is_user_enabled = true;
		// 			break;
		// 		}
		// 	}
		// }

		// if(!$is_user_enabled) {
		// 	$this->view->error('404');
		// }

		// // GET USER NOTIFICATIONS
		// $this->user->notifications_count = $this->model_admin->getUserNotificationsCount($this->user->id);
		// $this->user->notifications = $this->model_admin->getUserNotifications($this->user->id);
	}
}
