<?php

$allData = [
	'type' => 'checkbox|date|number|radio|range|text|select|switch|order',
	'column' => ['title', 'excerpt'] | 'name', // string or array of columns to filter in
	'multiple' => true | false, // for number|select|checkbox|date|datetime|month|file
	'default' => null, // default value
	'range' => true | false, // for date|datetime|month
	'step' => 10, // for range slider

	// RENDER - used only in filter-builder
	'classifier' => 'module.classifier.key',
	// or
	'classifier' => function ($value) {
		debug($value);
		exit;
		return $value; // modify $value and return it
	},
	'show_all_options' => true | false,
	'label' => 'This is label',
	'label_class' => 'label-class-1 label-class-2 ...',
	// or
	'label_html' => '<label>Some label</label>',
	'selected_label' => true | false | 'string if not isset label key',
	'placeholder' => '',
	'col_class' => 'col-xs-12 col-lg-6',
	'pattern' => '',

	// DATA ATTRIBUTES - used only in form-builder
	'data-attr-1' => 'attr-1',
	'data-attr-n' => 'attr-n'
];


return [
	// will init to $_GET['alias']
	'alias' => [
		'column' => ['title', 'excerpt', 'content'],
		'type' => 'text',
		'default' => 'default',
		'data-test' => 'test',
		'label' => t('user.name'),
		'placeholder' => t('user.name'),
		'col_class' => 'col-xs-12 col-md-6'
	],
];
