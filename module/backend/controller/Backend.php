<?php

namespace module\backend\controller;

use engine\router\Controller;
use engine\http\Response;
use engine\router\Route;

class Backend extends Controller
{
  protected $backendModel;

  public function __construct()
  {
    parent::__construct();

    class_alias('\\module\\backend\\builder\\Form', 'BuilderForm');
    class_alias('\\module\\backend\\builder\\Filter', 'BuilderFilter');
    class_alias('\\module\\backend\\builder\\Table', 'BuilderTable');

    $this->checkAuth();

    // TODO
    // $this->backendModel = $this->loadModel('Backend', 'backend');

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
    //  return false;
    // }

    // // GET USER NOTIFICATIONS
    // $this->user->notifications_count = $this->backendModel->getUserNotificationsCount($this->user->id);
    // $this->user->notifications = $this->backendModel->getUserNotifications($this->user->id);
  }

  public function checkAuth()
  {
    if ($this->user->get('isAuthorized') !== true) {
      Response::redirect(Route::link('login'));
    }

    return true;
  }
}
