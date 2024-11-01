<?php

namespace module\backend\controller;

use module\backend\controller\Backend;

class User extends Backend
{
  public function getList()
  {
    $this->view->setData('users', $this->model->getUsers());

    $this->view->render('user/list');
  }

  public function getAdd()
  {
    $this->view->render('user/add');
  }

  public function getEdit()
  {
    $userId = $this->route['parameter']['id'];
    $user = $this->model->getUserById($userId);

    if (empty($user)) {
      $this->view->error('404');
      return false;
    }

    $user->group_id = $this->model->getUserGroups($userId);

    $this->view->setData('user', $user);

    $this->view->render('user/edit');
  }
}
