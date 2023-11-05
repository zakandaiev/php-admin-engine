<?php

require Path::file('form') . '/_model/User.php';

return [
	'table' => 'user',
	'fields' => [
		'is_enabled' => $is_enabled
	],
	'execute_post' => function ($rowCount, $fields, $data) {
		Hook::run('user.toggle', $fields);
	}
];
