<?php

$all_checks = [
	'type': 'checkbox|color|date|datetime|email|file|hidden|month|number|password|radio|range|reset|tel|text|time|url + custom: textarea|wysiwyg|select|switch|maska',
	'required' => true|false,
	'min' => 1, // depends on type, for type number - int, for type date - string
	'max' => 100, // depends on type, for type number - int, for type date - string
	'pattern' => '',
	'placeholder' => '',
	'rows' => 4, // for textarea
	'multiple' => true|false, // for select|checkbox|date|datetime|month|file
	'range' => true|false, // for date|datetime|month
	'extensions' => ['jpg','png'], // for file
	'folder' => 'subfolder', // for file
	'regexp' => '/^[0-9]+$/u', // for any type except file

	'message' => [ // message to show when user sent invalid data
		'min' => __('validation.min'),
		'max' => __('validation.max')
	],

	'unset_null' => true|false, // remove field from sql query if it nulls




	// 'CHECKNAME_message' => 'Error message',
	'required_message' => __('This field is required'),

	'required' => true,
	'unset_null' => true,

	'html' => true,
	'json' => true,
	'int' => true,
	'float' => true,
	'email' => true,
	'ip' => true,
	'mac' => true,
	'url' => true,
	'date' => true,
	'date_not_future' => true,

	'min' => 1,
	'max' => 100,
	'minlength' => 1,
	'maxlength' => 100,
	'regexp' => '/^[0-9]+$/u',
	'regexp2' => '/^[a-z]+$/u',
	'regexp3' => '/^[A-Z]+$/u',

	'file' => true,
	'folder' => '',
	'multiple' => true,
	'extensions' => ['jpg','png'],

	// 'foreign' => 'table_name@primary_key_of_current_object/primary_key_of_foreign_object'
	'foreign' => 'user_group@user_id/group_id',

	'foreign' => function($field_value, $data) {
		debug($field_value);
		debug($data);
		exit;
	},

	'modify' => function($field) {
		// modify $field and return it
		return $field;
	},
];

$title = [
	'required' => true,
	'minlength' => 1,
	'maxlength' => 200
];

return [
	'table' => 'page',
	'fields' => [
		'title' => $title,
		'content' => [],
		'date_created' => [
			'date' => true
		],
		'is_enabled' => [
			'boolean' => true
		]
	],
	'modify_fields' => function($data) {
		// modify $data object and return it
		return $data;
	},
	'modify_sql' => function($data) {
		// modify $data object and return it
		return $data;
	},
	'execute_pre' => function($data) {
		debug($data);
		exit;
	},
	'execute' => function($data) {
		debug($data);
		exit;
	},
	'execute_post' => function($data) {
		debug($data);
		exit;
	},
	'submit' => 'Saved',
];

// or can return an closure
return function() {
	Server::answer('dummy_data', 'dummy_status', 'dummy_message');
};
