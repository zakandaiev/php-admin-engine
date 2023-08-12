<?php

$is_enabled = [
	'boolean' => true,
	'modify' => function($field) {
		debug($field);exit;
		return !$field;
	}
];

return [
	'table' => 'user',
	'fields' => [
		'is_enabled' => $is_enabled
	],
	'execute_post' => function($data) {
		Hook::run('user.toggle', $data);
	}
];
