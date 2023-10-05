<?php

require Path::file('form') . '/_model/Setting.php';

return [
	'table' => 'setting',
	'fields' => [
		'language' => $language,
		'enable_registration' => $enable_registration,
		'enable_password_restore' => $enable_password_restore,
		'moderate_comments' => $moderate_comments,
		'pagination_limit' => $pagination_limit
	],
	'execute_pre' => function($fields, $data) {
		foreach($fields as $field) {
			Setting::update('engine', $field['name'], $field['value']);
		}

		Server::answer(null, 'success', __('admin.setting.submit_success'));
	}
];
