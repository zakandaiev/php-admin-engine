<?php

############################# PAGE #############################
$url = [
	'required' => true,
	'minlength' => 1,
	'maxlength' => 200,
	'regexp' => '/^[a-z0-9\-]+$/',
	'required_message' => __('URL slug is required'),
	'minlength_message' => __('URL slug is too short'),
	'maxlength_message' => __('URL slug is too long'),
	'regexp_message' => __('URL slug should consist only small latin letters, numbers or dashes')
];
$author = [
	'required' => true,
	'int' => true,
	'required_message' => __('Author is required'),
	'int_message' => __('Author format is invalid')
];
$template = [
	'maxlength' => 200,
	'regexp' => '/^[a-z0-9_\-]*$/',
	'maxlength_message' => __('Template is too long'),
	'regexp_message' => __('Template should consist only small latin letters, numbers, underscores or dashes')
];
$date_publish = [
	'date' => true
];
$is_category = [
	'boolean' => true
];
$no_index_no_follow = [
	'type' => 'switch',
	'label' => __('admin.page.set_no_index_no_follow')
];
$allow_comment = [
	'boolean' => true
];
$hide_comments = [
	'boolean' => true
];
$is_enabled = [
	'boolean' => true
];
$category = [
	'foreign' => 'page_category@page_id/category_id'
];

############################# TRANSLATION #############################
$title = [
	'type' => 'text',
	'autofocus' => true,
	'required' => true,
	'min' => 1,
	'max' => 300,
	'label' => __('admin.page.title'),
	'placeholder' => __('admin.page.enter_title')
];
$excerpt = [
	'type' => 'textarea',
	'max' => 1000,
	'label' => __('admin.page.excerpt'),
	'placeholder' => __('admin.page.enter_excerpt')
];
$tags = [
	'type' => 'select',
	'multiple' => true,
	'data-addable' => 'tag',
	'label' => __('admin.page.tags'),
	'placeholder' => __('admin.page.enter_tags')
];
$content = [
	'type' => 'wysiwyg'
];
// $image = [
// 	'file' => true
// ];
// $seo_description = [];
// $seo_keywords = [];
// $seo_image = [
// 	'file' => true
// ];
// $custom_fields = [];
