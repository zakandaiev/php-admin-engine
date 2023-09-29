<?php

require Path::file('form') . '/_model/Group.php';

return [
	'table' => 'group',
	'fields' => [
		'is_enabled' => $is_enabled
	],
	'execute_post' => function($rowCount, $fields, $data) {
		Hook::run('group.toggle', $data['sql_binding']);
	}
];
