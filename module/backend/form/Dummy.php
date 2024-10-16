<?php

$allChecks = [
	'type' => 'checkbox|color|date|datetime|email|file|hidden|month|number|password|radio|range|tel|text|time|url + custom: textarea|wysiwyg|select|switch',

	// CHECK
	'required' => true | false,
	'min' => 1, // depends on type, for type number - int, for type date - string
	'max' => 100, // depends on type, for type number - int, for type date - string
	'regex' => '/^[\w\d ]+$/iu', // for any type except file
	'multiple' => true | false, // for select|checkbox|date|datetime|month|file
	'default' => null, // default value
	'range' => true | false, // for date|datetime|month
	'extensions' => ['jpg', 'png'], // for file
	'folder' => 'subfolder', // for file
	'max_size' => 10 * 1024 * 1024, // for file (10MB)

	'rows' => 4, // for textarea
	'step' => 10, // for range slider

	// VALIDATION MESSAGE
	'validation' => [ // message to show when user sent invalid data
		'min' => t('validation.min'),
		'max' => t('validation.max')
	],

	// MODIFY
	'unset_null' => true | false, // remove field from sql query if it nulls
	'modify' => function ($value, $data) {
		debug($value, $data);
		exit;
		return $value; // modify $value and return it
	},
	'foreign' => 'foreign_table_name@primary_key_of_foreign_table/primary_key_of_current_table',
	// or
	'foreign' => function ($value, $data) {
		debug($value, $data);
		exit;
		// execute some sql queries here
	},

	// TRANSLATION
	'translation' => ['language', 'name'], // array of column names to put into translation table (translation table should have _translation suffix in name, if other then create translation with execute_post closure)

	// RENDER - used only in form-builder
	'autofocus' => true | false,
	'label' => 'This is label',
	'label_class' => 'label-class-1 label-class-2 ...',
	// or
	'label_html' => '<label>Some label</label>',
	'placeholder' => '',
	'col_class' => 'col-xs-12 col-lg-6',
	'pattern' => '', // for any type except file

	// DATA ATTRIBUTES - used only in form-builder
	'data-attr-1' => 'attr-1',
	'data-attr-n' => 'attr-n'
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
	'modify_fields' => function ($fields, $data) {
		debug($fields, $data);
		exit;
		return $fields; // modify $fields and return it
	},
	'modify_sql' => function ($sql, $fields, $data) {
		debug($sql, $fields, $data);
		exit;
		return $sql; // modify $sql and return it
	},
	'modify_sql_binding' => function ($sqlBinding, $fields, $data) {
		debug($sqlBinding, $fields, $data);
		exit;
		return $sqlBinding; // modify $sqlBinding and return it
	},
	'execute_pre' => function ($fields, $data) {
		debug($fields, $data);
		exit;
		// execute some hooks or additional sql queries here, for example
		// or do whatever then answer
		// Response::answer('dummy_status', 'dummy_message', 'dummy_data');
	},
	'execute_post' => function ($rowCount, $fields, $data) {
		debug($rowCount, $fields, $data);
		exit;
		// execute some hooks or additional sql queries here, for example
	},
	'submit_message' => 'Saved',
	// or closure
	'submit_message' => function ($fields, $data) {
		debug($fields, $data);
		exit;
		return 'Saved with ID: ' . $data['item_id']; // return message string
	},
	'submit_button' => [
		'text' => 'Submit',
		'class' => 'btn btn_primary',
		'col_class' => 'col-xs-12 text-right',
		'html' => '<div class="col-xs-12 text-right" data-form-row="submit"><button type="submit" class="btn btn_primary">Submit</button></div>'
	],
	'class' => 'form-class-1 form-class-2 ...', // used only in form-builder
	'row_class' => 'row-class-1 row-class-2 ...' // used only in form-builder
];

// or can return an closure
return function ($data, $incomeData) {
	debug($data, $incomeData);
	exit;
	// do whatever then answer
	Response::answer('dummy_status', 'dummy_message', 'dummy_data');
};
