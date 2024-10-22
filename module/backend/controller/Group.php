<?php

namespace module\backend\controller;

use module\backend\controller\Backend;
use Engine\Language;
use Engine\Server;

class Group extends Backend
{
  public function getList()
  {
    $this->view->setData('groups', $this->model->getGroups());

    $this->view->render('group/list');
  }

  public function getAdd()
  {
    $this->view->setData('routes', $this->model->getRoutes());
    $this->view->setData('users', $this->model->getUsers());

    $this->view->render('group/add');
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
  }

  public function getAddTranslation()
  {
    $groupId = $this->route['parameter']['id'];
    $translationLanguage = $this->route['parameter']['language'];

    if (!Language::has($translationLanguage)) {
      Server::redirect(site('url_language') . '/admin/group');
    }

    $group = $this->model->getGroupById($groupId);

    if (empty($group)) {
      Server::redirect(site('url_language') . '/admin/group');
    }

    $translation = [
      'groupId' => $groupId,
      'language' => $translationLanguage,
      'name' => $group->name
    ];

    if ($this->model->createTranslation('group_translation', $translation)) {
      Server::redirect(site('url_language') . '/admin/group/edit/' . $groupId . '/translation/edit/' . $translationLanguage);
    } else {
      Server::redirect(site('url_language') . '/admin/group');
    }
  }

  public function getEditTranslation()
  {
    $isTranslation = false;
    $translationLanguage = null;

    if (isset($this->route['parameter']['language'])) {
      $isTranslation = true;
      $translationLanguage = $this->route['parameter']['language'];
    }

    $groupId = $this->route['parameter']['id'];

    $group = $this->model->getGroupById($groupId);

    if (empty($group)) {
      $this->view->error('404');
    }

    $group->routes = $this->model->getGroupRoutesById($groupId);
    $group->users = $this->model->getGroupUsersById($groupId);

    if ($isTranslation) {
      $this->view->setData('group_origin', $group);
      $this->view->setData('group', $this->model->getGroupById($groupId, $translationLanguage));
    } else {
      $this->view->setData('group', $group);
      $this->view->setData('routes', $this->model->getRoutes());
      $this->view->setData('users', $this->model->getUsers());
    }
    $this->view->setData('isTranslation', $isTranslation);

    $this->view->render('group/edit');
  }
}
