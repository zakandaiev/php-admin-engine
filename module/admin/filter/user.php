<?php

return [
	'name' => [
		'column' => 'name',
		'type' => 'text',
		'label' => __('admin.user.name'),
		'placeholder' => __('admin.user.enter_name'),
		'col_class' => 'col-xs-12 col-md-6 col-lg-3 col-xxl-12'
	],
	'created' => [
		'column' => 'date_created',
		'type' => 'date',
		'range' => true,
		'label' => __('admin.user.date_created'),
		'placeholder' => __('admin.user.enter_date_created'),
		'col_class' => 'col-xs-12 col-md-6 col-lg-3 col-xxl-12'
	],
	'auth' => [
		'column' => 'auth_date',
		'type' => 'date',
		'range' => true,
		'label' => __('admin.user.auth_date'),
		'placeholder' => __('admin.user.auth_date'),
		'col_class' => 'col-xs-12 col-md-6 col-lg-3 col-xxl-12'
	],
	'enabled' => [
		'column' => 'is_enabled',
		'type' => 'radio',
		'show_all_options' => true,
		'classifier' => 'admin.classifier.user.is_enabled',
		'label' => __('admin.user.is_enabled'),
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
		'label' => __('admin.user.name'),
		'selected_label' => true,
		'type' => 'order'
	],
	'ogroups' => [
		'column' => 'count_groups',
		'classifier' => 'admin.classifier.sort',
		'label' => __('admin.user.groups_count'),
		'selected_label' => true,
		'type' => 'order'
	],
	'ocreated' => [
		'column' => 'date_created',
		'classifier' => 'admin.classifier.sort',
		'label' => __('admin.user.date_created'),
		'selected_label' => true,
		'type' => 'order'
	],
	'oauth' => [
		'column' => 'auth_date',
		'classifier' => 'admin.classifier.sort',
		'label' => __('admin.user.auth_date'),
		'selected_label' => true,
		'type' => 'order'
	],
	'oenabled' => [
		'column' => 'is_enabled',
		'classifier' => 'admin.classifier.sort',
		'label' => __('admin.user.is_enabled'),
		'selected_label' => true,
		'type' => 'order'
	]
];
