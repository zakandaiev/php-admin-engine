<?php

namespace Module\Admin\Controller;

class User extends AdminController {
	public function getAll() {
		$this->view->setData('users', $this->model->getUsers());

		$this->view->render('user/all');
	}

	public function getAdd() {
		$this->view->setData('groups', $this->model->getGroups());

		$this->view->render('user/add');
	}

	public function getEdit() {
		$user_id = $this->route['parameter']['id'];

		$user = $this->model->getUserById($user_id);

		if(empty($user)) {
			$this->view->error('404');
		}

		$user->groups = $this->model->getUserGroups($user_id);

		$this->view->setData('user', $user);
		$this->view->setData('groups', $this->model->getGroups());

		$this->view->render('user/edit');
	}
}
