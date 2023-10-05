<?php

require Path::file('form') . '/_model/Setting.php';

return [
	'table' => 'setting',
	'fields' => [
		'group_css' => $group_css,
		'group_js' => $group_js,
		'cache_db' => $cache_db
	],
	'execute_pre' => function($fields, $data) {
		foreach($fields as $field) {
			Setting::update('engine', $field['name'], $field['value']);
		}

		Server::answer(null, 'success', __('admin.setting.submit_success'));
	}
];
