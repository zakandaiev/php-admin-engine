<?php

namespace Module\Admin\Controller;

use Engine\Template;

class Setting extends AdminController
{
	public function getSection()
	{
		$section = $this->route['parameter']['section'];
		$template = 'setting/' . $section;

		if (!Template::has($template)) {
			$this->view->error('404');
		}

		$this->view->setData('section', $section);
		$this->view->setData('setting', $this->setting->engine);

		$this->view->render('setting/' . $section);
	}
}
