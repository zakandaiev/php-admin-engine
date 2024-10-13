<?php

namespace module\backend\controller;

use engine\router\Controller;

class Backend extends Controller
{
  protected $backendModel;

  public function __construct()
  {
    parent::__construct();

    // TODO
    // $this->backendModel = $this->loadModel('Backend', 'backend');

    // TODO
    // class_alias('\\Module\\Admin\\Builder\\FormBuilder', 'FormBuilder');
    // class_alias('\\Module\\Admin\\Builder\\FilterBuilder', 'FilterBuilder');
    // class_alias('\\Module\\Admin\\Builder\\InterfaceBuilder', 'InterfaceBuilder');

    // TODO - make as function
    // if(!$this->user->get()->authorized) {
    // 	Server::redirect('/admin/login');
    // }

    // // CHECK USER FOR ROUTE ACCESS
    // $is_user_enabled = false;

    // $this->user->access_all = $this->backendModel->getUserAccessAll($this->user->id);
    // $this->user->groups = $this->backendModel->getUserGroups($this->user->id);
    // $this->user->routes = $this->backendModel->getUserRoutes($this->user->id);

    // if((isset($this->route['isPublic']) && $this->route['isPublic'] === true) || $this->user->access_all) {
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
    // $this->user->notifications_count = $this->backendModel->getUserNotificationsCount($this->user->id);
    // $this->user->notifications = $this->backendModel->getUserNotifications($this->user->id);
  }
}
