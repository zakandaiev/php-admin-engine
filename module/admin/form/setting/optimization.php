<?php

require Path::file('form') . '/_model/Setting.php';

return [
	'table' => 'setting',
	'fields' => [
		'group_css' => $group_css,
		'group_js' => $group_js,
		'cache_db' => $cache_db
	],
	'execute_pre' => function ($fields, $data) {
		$group_css = $fields['group_css']['value'] ?? false;
		$group_js = $fields['group_js']['value'] ?? false;

		if ($group_css === true && !site('group_css')) {
			$fields['group_css']['value'] = launchOptimization('css');
		} else if ($group_css === true && is_string(site('group_css'))) {
			$fields['group_css']['value'] = site('group_css');
		}

		if ($group_js === true && !site('group_js')) {
			$fields['group_js']['value'] = launchOptimization('js');
		} else if ($group_js === true && is_string(site('group_js'))) {
			$fields['group_js']['value'] = site('group_js');
		}

		foreach ($fields as $field) {
			Setting::update('engine', $field['name'], $field['value']);
		}

		Server::answer(null, 'success', __('admin.setting.submit_success'));
	}
];

function launchOptimization($type)
{
	$files = [];
	$modules = [];

	foreach (Module::get() as $module) {
		if (!$module['is_enabled'] || $module['extends'] !== 'public' || $module['name'] === 'public') {
			continue;
		}

		$modules[] = $module['name'];
	}

	$modules[] = 'public';

	foreach ($modules as $module) {
		Module::setName($module);

		$functions = Path::file('view') . '/functions.php';

		if (!is_file($functions)) {
			continue;
		}

		require_once $functions;

		foreach (Asset::getContainer($type) as $file) {
			if ($file['module'] !== $module) {
				continue;
			}

			$files[] = Path::file('asset') . '/' . $file['file'];
		}
	}

	if (empty($files)) {
		return false;
	}

	$dest = Path::file('asset', 'public') . '/' . $type;

	return Optimization::{strtolower($type ?? '')}($files, $dest);
}
