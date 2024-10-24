<?php

namespace module\backend\controller;

use module\backend\controller\Backend;
use engine\http\Response;
use engine\router\Route;

class Group extends Backend
{
  public function getList()
  {
    $this->view->setData('groups', $this->model->getGroups());

    $this->view->render('group/list');

    return true;
  }

  public function getAdd()
  {
    $this->view->setData('routes', $this->model->getRoutes());
    $this->view->setData('users', $this->model->getUsers());

    $this->view->render('group/add');

    return true;
  }

  public function getEdit()
  {
    $groupId = $this->route['parameter']['id'];
    $group = $this->model->getGroupById($groupId);

    if (empty($group)) {
      $this->view->error('404');
      return false;
    }

    $group->route = $this->model->getGroupRoutesById($groupId);
    $group->user_id = $this->model->getGroupUsersById($groupId);

    $this->view->setData('group', $group);
    $this->view->setData('routes', $this->model->getRoutes());
    $this->view->setData('users', $this->model->getUsers());

    $this->view->render('group/edit');

    return true;
  }

  public function getTranslationAdd()
  {
    $language = $this->route['parameter']['language'];
    $groupId = $this->route['parameter']['id'];

    $routeDestination = Route::link('group.translation.edit', ['id' => $groupId, 'language' => $language]);
    $routeFallback = Route::link('group.list');

    $group = $this->model->getGroupById($groupId);
    if (empty($group)) {
      Response::redirect($routeFallback);
      return false;
    }

    $translationResult = $this->model->insertIntoTable('group_translation', ['group_id' => $groupId, 'language' => $language, 'name' => $group->name]);
    if (!$translationResult) {
      Response::redirect($routeFallback);
      return false;
    }

    Response::redirect($routeDestination);

    return true;
  }

  public function getTranslationEdit()
  {
    $language = $this->route['parameter']['language'];
    $groupId = $this->route['parameter']['id'];
    $group = $this->model->getGroupById($groupId, $language);

    if (empty($group)) {
      $this->view->error('404');
      return false;
    }

    $this->view->setData('group', $group);
    $this->view->setData('isTranslation', true);

    $this->view->render('group/translation-edit');

    return true;
  }
}
