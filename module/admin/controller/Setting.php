<?php

namespace Module\Admin\Controller;

use Engine\Template;

class Setting extends AdminController {
	public function getSection() {
		$section = $this->route['parameter']['section'];
		$template = 'setting/' . $section;

		if(!Template::has($template)) {
			$this->view->error('404');
		}

		$data['section'] = $section;
		$data['setting'] = \Engine\Setting::get('engine');

		$this->view->setData($data);
		$this->view->render('setting/' . $section);
	}
}
