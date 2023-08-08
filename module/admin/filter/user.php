<?php

return [
	'name' => [
		'column' => 'name',
		'type' => 'text',
		'label' => __('admin.user.name'),
		'placeholder' => __('admin.user.enter_name'),
		'col_class' => 'col-xs-12 col-md-6 col-lg-3 col-xxl-12'
	],
	'name_select' => [
		'column' => 'name',
		'type' => 'select',
		'label' => __('admin.user.name'),
		'placeholder' => __('admin.user.enter_name'),
		'col_class' => 'col-xs-12 col-md-6 col-lg-3 col-xxl-12'
	],
	'created' => [
		'column' => 'date_created',
		'type' => 'date',
		'data-range' => true,
		'label' => __('admin.user.date_created'),
		'placeholder' => __('admin.user.enter_date_created'),
		'col_class' => 'col-xs-12 col-md-6 col-lg-3 col-xxl-12'
	],
	'enabled' => [
		'column' => 'is_enabled',
		'type' => 'radio',
		'label' => __('admin.user.is_enabled'),
		'col_class' => 'col-xs-12 col-md-6 col-lg-3 col-xxl-12'
	],
	'oname' => [
		'column' => 'name',
		'type' => 'order'
	],
	'ocreated' => [
		'column' => 'date_created',
		'type' => 'order'
	],
	'oedited' => [
		'column' => 'date_edited',
		'type' => 'order'
	],
];
