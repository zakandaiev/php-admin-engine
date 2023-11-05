<?php

namespace Module\Admin\Controller;

use Engine\Page;

class Dashboard extends AdminController
{
	public function getDashboard()
	{
		$this->page->set('title', __('admin.dashboard.title'));
		$this->view->render('dashboard');
	}
}
