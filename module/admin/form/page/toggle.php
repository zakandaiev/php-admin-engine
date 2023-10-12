<?php

require Path::file('form') . '/_model/Page.php';

return [
	'table' => 'page',
	'fields' => [
		'is_enabled' => $is_enabled
	],
	'execute_post' => function($rowCount, $fields, $data) {
		Hook::run('page.toggle', $fields);
	}
];
