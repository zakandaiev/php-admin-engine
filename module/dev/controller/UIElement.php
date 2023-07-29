<?php

namespace Module\Dev\Controller;

use Engine\Path;
use Module\Admin\Controller\AdminController;

class UIElement extends AdminController {
	public function getSection() {
		$section = $this->route['parameter']['section'];

		$data['section'] = $section;

		$view = 'ui/' . $section;
		$path = Path::file('view') . "/$view.php";

		if(!is_file($path)) {
			$this->view->error('404');
		}

		$this->view->setData($data);
		$this->view->render($view);
	}
}
