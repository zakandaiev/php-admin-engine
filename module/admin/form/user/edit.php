<?php

require Path::file('form') . '/_model/User.php';

$password['required'] = false;
$password['unset_null'] = true;

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
	'execute_post' => function($rowCount, $fields, $data) {
		Hook::run('user.' . $data['action'], $data['sql_binding']);
	}
];
