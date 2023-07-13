<?php

namespace Module\Public\Controller;

use Engine\View;

class Controller extends \Engine\Controller {
	public function __construct() {
		parent::__construct();

		// TODO
		// View::setData(['page_model' => \Module\Public\Model\Page::getInstance()]);

		// class_alias('\\Module\\public\\Model\\Page', 'PageModel');
	}
}
