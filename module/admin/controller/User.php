<?php

namespace Module\Admin\Controller;

class User extends AdminController {
	public function getAll() {
		$data['users'] = $this->model->getUsers();

		$this->view->setData($data);
		$this->view->render('user/all');
	}

	public function getAdd() {
		$data['groups'] = $this->model->getGroups();

		$this->view->setData($data);
		$this->view->render('user/add');
	}

	public function getEdit() {
		$user_id = $this->route['parameter']['id'];

		$data['user'] = $this->model->getUserById($user_id);

		if(empty($data['user'])) {
			$this->view->error('404');
		}

		$data['groups'] = $this->model->getGroups();
		$data['user']->groups = $this->model->getUserGroups($user_id);

		$this->view->setData($data);
		$this->view->render('user/edit');
	}
}
