<?php

$language = [
	'type' => 'hidden',
	'required' => true,
	'default' => site('language')
];
$name = [
	'type' => 'text',
	'required' => true,
	'min' => 1,
	'max' => 200,
	'regex' => '/^[\w ]+$/iu',
	'label' => __('admin.group.name'),
	'placeholder' => __('admin.group.name_placeholder'),
	'col_class' => 'col-xs-12'
];
$routes = [
	'type' => 'select',
	'multiple' => true,
	'data-addable' => '/(any|delete|get|options|patch|post|put)@\/[0-9a-z\/\*\$\-\_]+/g',
	'label' => __('admin.group.routes'),
	'placeholder' => __('admin.group.routes_placeholder'),
	'foreign' => 'group_route@group_id/route',
	'col_class' => 'col-xs-12'
];
$users = [
	'type' => 'select',
	'multiple' => true,
	'label' => __('admin.group.users'),
	'placeholder' => __('admin.group.users_placeholder'),
	'foreign' => 'group_user@group_id/user_id',
	'col_class' => 'col-xs-12'
];
$is_enabled = [
	'type' => 'switch',
	'default' => true,
	'label' => __('admin.group.is_enabled'),
	'col_class' => 'col-xs-12'
];
$access_all = [
	'type' => 'switch',
	'default' => false,
	'label' => __('admin.group.access_all'),
	'col_class' => 'col-xs-12'
];
