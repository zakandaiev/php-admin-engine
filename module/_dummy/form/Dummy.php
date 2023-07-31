<?php

$all_checks = [
	'type': 'checkbox|color|date|datetime|email|file|hidden|month|number|password|radio|range|tel|text|time|url + custom: textarea|wysiwyg|select|switch|maska',

	// CHECK
	'required' => true|false,
	'min' => 1, // depends on type, for type number - int, for type date - string
	'max' => 100, // depends on type, for type number - int, for type date - string
	'pattern' => '', // for any type except file
	'multiple' => true|false, // for select|checkbox|date|datetime|month|file
	'range' => true|false, // for date|datetime|month
	'extensions' => ['jpg','png'], // for file
	'folder' => 'subfolder', // for file
	'step' => 10, // for range slider

	// CHECK MESSAGE
	'message' => [ // message to show when user sent invalid data
		'min' => __('validation.min'),
		'max' => __('validation.max')
	],

	// MODIFY
	'unset_null' => true|false, // remove field from sql query if it nulls
	'modify' => function($value, $data) {
		debug($value, $data);exit;
		return $value; // modify $value and return it
	},
	// 'foreign' => 'table_name@primary_key_of_current_object/primary_key_of_foreign_object'
	// 'foreign' => 'user_group@user_id/group_id',
	// 'foreign' => function($field_value, $data) {
	// 	debug($field_value);
	// 	debug($data);
	// 	exit;
	// },

	// RENDER
	'autofocus' => true|false, // used only in form-builder
	'label' => 'This is label', // used only in form-builder
	'label_class' => 'label-class-1 label-class-2 ...', // used only in form-builder
	'label_html' => '<label>Some label</label>', // used only in form-builder
	'placeholder' => '', // used only in form-builder
	'col_class' => 'col-xs-12 col-lg-6', // used only in form-builder
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
	'modify_fields' => function($fields, $data) {
		debug($fields, $data);exit;
		return $fields; // modify $fields and return it
	},
	'modify_sql' => function($sql, $fields, $data) {
		debug($sql, $fields, $data);exit;
		return $sql; // modify $sql and return it
	},
	'modify_sql_binding' => function($sql_binding, $fields, $data) {
		debug($sql_binding, $fields, $data);exit;
		return $sql_binding; // modify $sql_binding and return it
	},
	'execute_pre' => function($fields, $data) {
		debug($fields, $data);exit;
		// execute some hooks or additional sql queries here, for example
	},
	'execute' => function($data) {
		debug($data);
		exit;
	},
	'execute_post' => function($rowCount, $fields, $data) {
		debug($rowCount, $fields, $data);exit;
		// execute some hooks or additional sql queries here, for example
	},
	'submit_message' => 'Saved',
	// or closure
	'submit_message' => function($fields, $data) {
		debug($rowCount, $fields, $data);exit;
		return 'Saved with ID: ' . $data['item_id']; // return message string
	},
	'submit_button' => [
		'text' => 'Submit',
		'class' => 'btn btn_primary',
		'col_class' => 'col-xs-12 text-right',
		'html' => '<div class="col-xs-12 text-right" data-form-row="submit"><button type="submit" class="btn btn_primary">Submit</button></div>'
	],
	'class' => 'form-class-1 form-class-2 ...' // used only in form-builder
	'row_class' => 'row-class-1 row-class-2 ...' // used only in form-builder
];

// or can return an closure
return function($data, $income_data) {
	debug($data, $income_data);exit;
	// do whatever then answer
	Server::answer('dummy_data', 'dummy_status', 'dummy_message');
};