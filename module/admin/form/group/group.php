<?php

require Path::file('form') . '/_model/Group.php';

return [
	'table' => 'group',
	'fields' => [
		'language' => $language,
		'name' => $name,
		'routes' => $routes,
		'users' => $users,
		'access_all' => $access_all,
		'is_enabled' => $is_enabled
	],
	'translation' => ['language', 'name'],
	'execute_post' => function($rowCount, $fields, $data) {
		Hook::run('group.' . $data['action'], $data['sql_binding']);
	}
];
