<?php

// $group = [
// 	'foreign' => 'user_group@user_id/group_id'
// ];
$email = [
	'type' => 'email',
	'required' => true,
	'min' => 6,
	'max' => 200,
	'label' => __('admin.user.email'),
	'placeholder' => __('admin.user.enter_email'),
	'col_class' => 'col-xs-12 col-md-6'
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
	'placeholder' => __('admin.user.enter_password'),
	'col_class' => 'col-xs-12 col-md-6'
];
$name = [
	'type' => 'text',
	'required' => true,
	'min' => 1,
	'max' => 200,
	'regexp' => '/^[\w ]+$/iu',
	'label' => __('admin.user.name'),
	'placeholder' => __('admin.user.enter_name'),
	'col_class' => 'col-xs-12 col-md-6'
];
$avatar = [
	'type' => 'file',
	'extensions' => ['jpg','jpeg','png','svg'],
	'label' => __('admin.user.avatar'),
	'placeholder' => __('admin.user.enter_avatar'),
	'col_class' => 'col-xs-12 col-md-6'
];
$setting = [
	'type' => 'textarea',
	'label' => __('admin.user.setting'),
	'placeholder' => __('admin.user.enter_setting'),
	'col_class' => 'col-xs-12 col-md-6'
];
$is_enabled = [
	'type' => 'switch',
	'label' => __('admin.user.is_enabled'),
	'col_class' => 'col-xs-12 col-md-6'
];