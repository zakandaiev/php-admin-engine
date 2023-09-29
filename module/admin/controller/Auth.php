<?php

namespace Module\Admin\Controller;

use Engine\Server;
use Engine\User;

class Auth extends \Engine\Controller {
	public function __construct() {
		parent::__construct();
	}

	public function getLogin() {
		$this->checkAuth();
		$this->page->set('title', __('admin.auth.login'));
		$this->view->render('auth/login');
	}

	public function getLogout() {
		User::unauthorize();
		$this->page->set('title', __('admin.auth.logout'));
		Server::redirect('/admin/login');
	}

	public function getRestore() {
		$this->checkAuth();

		if(!site('enable_password_restore')) {
			$this->view->error('404');
		}

		$this->page->set('title', __('admin.auth.reset_password'));
		$this->view->render('auth/reset-password');
	}

	private function checkAuth() {
		if($this->user->get()->authorized) {
			Server::redirect('/admin');
		}
	}
}
