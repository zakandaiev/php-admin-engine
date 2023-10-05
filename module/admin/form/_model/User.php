<?php

$group = [
	'type' => 'select',
	'multiple' => true,
	'label' => __('admin.user.user_groups'),
	'placeholder' => __('admin.user.name_placeholder'),
	'foreign' => 'group_user@user_id/group_id',
	'col_class' => 'col-xs-12'
];
$email = [
	'type' => 'email',
	'required' => true,
	'min' => 6,
	'max' => 200,
	'label' => __('admin.user.email'),
	'placeholder' => __('admin.user.email_placeholder'),
	'col_class' => 'col-xs-12 col-md-6 col-lg-3'
];
$password = [
	'type' => 'password',
	'required' => true,
	'min' => 8,
	'max' => 200,
	'modify' => function($pass) {
		return Hash::password($pass);
	},
	'label' => __('admin.user.password'),
	'placeholder' => __('admin.user.password_placeholder'),
	'col_class' => 'col-xs-12 col-md-6 col-lg-3'
];
$name = [
	'type' => 'text',
	'required' => true,
	'min' => 1,
	'max' => 200,
	'regex' => '/^[\w ]+$/iu',
	'label' => __('admin.user.name'),
	'placeholder' => __('admin.user.name_placeholder'),
	'col_class' => 'col-xs-12 col-md-6 col-lg-3'
];
$avatar = [
	'type' => 'file',
	'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'],
	'label' => __('admin.user.avatar'),
	'col_class' => 'col-xs-12 col-md-6 col-lg-3'
];
$setting = [
	'type' => 'textarea',
	'label' => __('admin.user.setting'),
	'placeholder' => __('admin.user.setting_placeholder'),
	'col_class' => 'col-xs-12'
];
$is_enabled = [
	'type' => 'switch',
	'default' => true,
	'label' => __('admin.user.is_enabled'),
	'col_class' => 'col-xs-12'
];
