<?php

namespace Module\Admin\Controller;

class Group extends AdminController {
	public function getAll() {
		$this->view->setData('groups', $this->model->getGroups());

		$this->view->render('group/all');
	}

	public function getAdd() {
		$this->view->setData('routes', $this->model->getRoutes());
		$this->view->setData('users', $this->model->getUsers());

		$this->view->render('group/add');
	}

	public function getEdit() {
		$group_id = $this->route['parameter']['id'];

		$group = $this->model->getGroupById($group_id);

		if(empty($group)) {
			$this->view->error('404');
		}

		$group->routes = $this->model->getGroupRoutesById($group_id);
		$group->users = $this->model->getGroupUsersById($group_id);

		$this->view->setData('group', $group);
		$this->view->setData('routes', $this->model->getRoutes());
		$this->view->setData('users', $this->model->getUsers());

		$this->view->render('group/edit');
	}
}
