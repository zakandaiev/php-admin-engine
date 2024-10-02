<?php

namespace module\_dummy\controller;

use engine\router\Controller;
use engine\http\Response;

class Dummy extends Controller
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getHome()
  {
    $this->view->render('home');
  }

  public function getGuide()
  {
    $this->view->setData('dataFromControllerString', 'dummyString');
    $this->view->setData('dataFromControllerRouteOptions', $this->route['option']);

    $this->view->render('guide');
  }

  public function postExample()
  {
    Response::answer(null, 'success', __FUNCTION__);
  }

  public function putExample()
  {
    Response::answer(null, 'success', __FUNCTION__);
  }
}
