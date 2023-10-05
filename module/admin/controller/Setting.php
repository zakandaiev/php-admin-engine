<?php

namespace Module\Admin\Controller;

use Engine\Cache;
use Engine\Form;
use Engine\Module;
use Engine\Optimization;
use Engine\Path;
use Engine\Server;
use Engine\Asset;
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

	public function postSection() {
		$section = $this->route['parameter']['section'];
		$form_name = 'Setting/' . ucfirst($section);

		Form::check($form_name);

		$fields = Form::processFields($form_name);

		if($section === 'optimization') {
			if($fields['group_css'] === true && !site('group_css')) {
				$fields['group_css'] = $this->launchOptimization('css');
			} else if($fields['group_css'] === true && is_string(site('group_css'))) {
				$fields['group_css'] = site('group_css');
			}

			if($fields['group_js'] === true && !site('group_js')) {
				$fields['group_js'] = $this->launchOptimization('js');
			} else if($fields['group_js'] === true && is_string(site('group_js'))) {
				$fields['group_js'] = site('group_js');
			}
		}

		foreach($fields as $name => $value) {
			\Engine\Setting::update($section, $name, $value);
		}

		Server::answer(null, 'success', __('Settings saved'));
	}

	private function launchOptimization($type) {
		$files = [];
		$modules = [];

		foreach(Module::list() as $module) {
			if(!$module['is_enabled'] || $module['extends'] !== 'public' || $module['name'] === 'public') {
				continue;
			}

			$modules[] = $module['name'];
		}

		$modules[] = 'public';

		foreach($modules as $module) {
			Module::setName($module);

			$functions = Path::file('view') . '/functions.php';

			if(!is_file($functions)) {
				continue;
			}

			require_once $functions;

			foreach(Asset::getContainer($type) as $file) {
				if($file['module'] !== $module) {
					continue;
				}

				$files[] = Path::file('asset') . '/' . $file['file'];
			}
		}

		if(empty($files)) {
			return false;
		}

		$dest = Path::file('asset', 'public') . '/' . $type;

		return Optimization::{strtolower($type ?? '')}($files, $dest);
	}

	public function postFlushCache() {
		Cache::flush();

		Server::answer(null, 'success', __('Cache successfully flushed'));
	}
}
