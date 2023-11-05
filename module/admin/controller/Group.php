<?php

namespace Module\Admin\Controller;

use Engine\Language;
use Engine\Server;

class Group extends AdminController
{
	public function getAll()
	{
		$this->view->setData('groups', $this->model->getGroups());

		$this->view->render('group/all');
	}

	public function getAdd()
	{
		$this->view->setData('routes', $this->model->getRoutes());
		$this->view->setData('users', $this->model->getUsers());

		$this->view->render('group/add');
	}

	public function getEdit()
	{
		$is_translation = false;
		$translation_language = null;

		if (isset($this->route['parameter']['language'])) {
			$is_translation = true;
			$translation_language = $this->route['parameter']['language'];
		}

		$group_id = $this->route['parameter']['id'];

		$group = $this->model->getGroupById($group_id);

		if (empty($group)) {
			$this->view->error('404');
		}

		$group->routes = $this->model->getGroupRoutesById($group_id);
		$group->users = $this->model->getGroupUsersById($group_id);

		if ($is_translation) {
			$this->view->setData('group_origin', $group);
			$this->view->setData('group', $this->model->getGroupById($group_id, $translation_language));
		} else {
			$this->view->setData('group', $group);
			$this->view->setData('routes', $this->model->getRoutes());
			$this->view->setData('users', $this->model->getUsers());
		}
		$this->view->setData('is_translation', $is_translation);

		$this->view->render('group/edit');
	}

	public function getAddTranslation()
	{
		$group_id = $this->route['parameter']['id'];
		$translation_language = $this->route['parameter']['language'];

		if (!Language::has($translation_language)) {
			Server::redirect(site('url_language') . '/admin/group');
		}

		$group = $this->model->getGroupById($group_id);

		if (empty($group)) {
			Server::redirect(site('url_language') . '/admin/group');
		}

		$translation = [
			'group_id' => $group_id,
			'language' => $translation_language,
			'name' => $group->name
		];

		if ($this->model->createTranslation('group_translation', $translation)) {
			Server::redirect(site('url_language') . '/admin/group/edit/' . $group_id . '/translation/edit/' . $translation_language);
		} else {
			Server::redirect(site('url_language') . '/admin/group');
		}
	}
}
