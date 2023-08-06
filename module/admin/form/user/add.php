<?php

require Path::file('form') . '/_model/User.php';

return [
	'table' => 'user',
	'fields' => [
		'group' => $group,
		'email' => $email,
		'password' => $password,
		'name' => $name,
		'avatar' => $avatar,
		'setting' => $setting,
		'is_enabled' => $is_enabled
	],
	'execute_post' => function($data) {
		// Hook::run('user.' . $data->form_data['action'], $data);
	}
];
