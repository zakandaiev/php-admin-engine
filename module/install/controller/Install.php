<?php

namespace Module\Install\Controller;

use Engine\Module;
use Engine\Path;
use Engine\Request;
use Engine\Server;

class Install extends \Engine\Controller {
	public function getInstallModule() {
		// TODO
		// $this->modules ...
		// $module = Module::getAll($this->route['parameter']['name']);

		// if(empty($module)) {
		// 	Module::setName('admin');
		// 	$this->view->error('404');
		// }

		// $install = Module::install($module['name']);

		// if($install === true) {
		// 	Server::answer(null, 'success');
		// }

		// Server::answer(null, 'error', $install);
	}

	public function getUninstallModule() {
		// TODO
		// $this->modules ...
		// $module = Module::getAll($this->route['parameter']['name']);

		// if(empty($module)) {
		// 	Module::setName('admin');
		// 	$this->view->error('404');
		// }

		// $uninstall = Module::uninstall($module['name']);

		// if($uninstall === true) {
		// 	Server::answer(null, 'success');
		// }

		// Server::answer(null, 'error', $uninstall);
	}
}
