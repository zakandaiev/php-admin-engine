<?php

namespace Module\Admin\Controller;

class Dashboard extends AdminController {
	public function getDashboard() {
		// TODO
		// $this->page->title = __('Dashboard');
		$this->view->render('dashboard');
	}
}
