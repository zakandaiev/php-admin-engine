<?php

namespace module\backend\controller;

use engine\router\Controller;
use engine\http\Response;
use engine\module\Setting;
use engine\router\Route;

class Auth extends Controller
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getLogin()
  {
    $this->checkAuth();

    $this->view->render('auth/login');

    return true;
  }

  public function getLogout()
  {
    $this->user->unauthorize();

    Response::redirect(Route::link('login'));

    return true;
  }

  public function getRegistration()
  {
    $this->checkAuth();

    if (Setting::getProperty('enable_registration', 'engine') !== true) {
      $this->view->error('404');
      return false;
    }

    $this->view->render('auth/registration');

    return true;
  }

  public function getRestore()
  {
    $this->checkAuth();

    if (Setting::getProperty('enable_password_restore', 'engine') !== true) {
      $this->view->error('404');
      return false;
    }

    $this->view->render('auth/reset-password');

    return true;
  }

  protected function checkAuth()
  {
    if ($this->user->get('isAuthorized')) {
      Response::redirect(Route::link('dashboard'));
    }

    return true;
  }
}
