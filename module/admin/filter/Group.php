<?php

return [
	'name' => [
		'column' => 'name',
		'type' => 'text',
		'label' => __('admin.group.name'),
		'placeholder' => __('admin.group.name_placeholder'),
		'col_class' => 'col-xs-12 col-md-6 col-lg-3 col-xxl-12'
	],
	'created' => [
		'column' => 'date_created',
		'type' => 'date',
		'range' => true,
		'label' => __('admin.group.date_created'),
		'placeholder' => __('admin.group.enter_date_created'),
		'col_class' => 'col-xs-12 col-md-6 col-lg-3 col-xxl-12'
	],
	'enabled' => [
		'column' => 'is_enabled',
		'type' => 'radio',
		'show_all_options' => true,
		'classifier' => 'admin.classifier.group.is_enabled',
		'label' => __('admin.group.is_enabled'),
		'col_class' => 'col-xs-12 col-md-6 col-lg-3 col-xxl-12'
	],
	'oid' => [
		'column' => 'id',
		'classifier' => 'admin.classifier.sort',
		'label' => 'ID',
		'selected_label' => true,
		'type' => 'order'
	],
	'oname' => [
		'column' => 'name',
		'classifier' => 'admin.classifier.sort',
		'label' => __('admin.group.name'),
		'selected_label' => true,
		'type' => 'order'
	],
	'oroutes' => [
		'column' => 'count_routes',
		'classifier' => 'admin.classifier.sort',
		'label' => __('admin.group.count_routes'),
		'selected_label' => true,
		'type' => 'order'
	],
	'ousers' => [
		'column' => 'count_users',
		'classifier' => 'admin.classifier.sort',
		'label' => __('admin.group.count_users'),
		'selected_label' => true,
		'type' => 'order'
	],
	'ocreated' => [
		'column' => 'date_created',
		'classifier' => 'admin.classifier.sort',
		'label' => __('admin.group.date_created'),
		'selected_label' => true,
		'type' => 'order'
	],
	'oenabled' => [
		'column' => 'is_enabled',
		'classifier' => 'admin.classifier.sort',
		'label' => __('admin.group.is_enabled'),
		'selected_label' => true,
		'type' => 'order'
	]
];
