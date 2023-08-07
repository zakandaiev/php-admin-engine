<?php

return [
	'name' => [
		'column' => 'name',
		'type' => 'text',
		'label' => __('admin.user.name'),
		'placeholder' => __('admin.user.enter_name'),
		'col_class' => 'col-xs-12 col-md-6 col-lg-3'
	],
	'created' => [
		'column' => 'date_created',
		'type' => 'date',
		'data-range' => true,
		'label' => __('admin.user.date_created'),
		'placeholder' => __('admin.user.enter_date_created'),
		'col_class' => 'col-xs-12 col-md-6 col-lg-3'
	],
	'enabed' => [
		'column' => 'is_enabed',
		'type' => 'radio',
		'label' => __('admin.user.is_enabed'),
		'col_class' => 'col-xs-12 col-md-6 col-lg-3'
	]
];
