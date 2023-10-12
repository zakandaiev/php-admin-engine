<?php

$language = [
	'type' => 'hidden',
	'required' => true,
	'default' => site('language')
];

$author = [
	'type' => 'select',
	'default' => User::get()->id,
	'required' => true,
	'label' => __('admin.page.author'),
	'placeholder' => __('admin.page.author_placeholder')
];

$category = [
	'type' => 'select',
	'multiple' => true,
	'foreign' => 'page_category@page_id/category_id',
	'label' => __('admin.page.category'),
	'placeholder' => __('admin.page.category_placeholder')
];

$url = [
	'type' => 'text',
	'required' => true,
	'min' => 1,
	'max' => 300,
	'regex' => '/^[a-z0-9\-]+$/',
	'label' => __('admin.page.url'),
	'placeholder' => __('admin.page.url_placeholder')
];

$template = [
	'type' => 'select',
	'required' => false,
	'min' => 1,
	'max' => 100,
	'regex' => '/^[a-z0-9\-\_]+$/',
	'label' => __('admin.page.template'),
	'placeholder' => __('admin.page.template_placeholder')
];

$date_publish = [
	'type' => 'datetime',
	'required' => true,
	'default' => format_date(null, 'Y-m-d H:i'),
	'label' => __('admin.page.date_publish'),
	'placeholder' => __('admin.page.date_publish_placeholder'),
	'data-position' => 'top right'
];

$is_category = [
	'type' => 'switch',
	'default' => Request::has('is-category'),
	'label' => __('admin.page.is_category')
];

$allow_comment = [
	'type' => 'switch',
	'default' => true,
	'label' => __('admin.page.allow_comment')
];

$hide_comments = [
	'type' => 'switch',
	'default' => false,
	'label' => __('admin.page.hide_comments')
];

$is_enabled = [
	'type' => 'switch',
	'default' => true,
	'label' => __('admin.page.is_enabled')
];

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
	'foreign' => 'page_tag@page_id/tag_id',
	'label' => __('admin.page.tags'),
	'placeholder' => __('admin.page.enter_tags')
];

$content = [
	'type' => 'wysiwyg',
	'label' => __('admin.page.content'),
	'placeholder' => __('admin.page.content_placeholder')
];

$image = [
	'type' => 'file',
	'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']
];

$seo_description = [
	'type' => 'textarea',
	'max' => 1000,
	'label' => __('admin.page.seo_description'),
	'placeholder' => __('admin.page.seo_description_placeholder')
];
$seo_keywords = [
	'type' => 'textarea',
	'max' => 1000,
	'label' => __('admin.page.seo_keywords'),
	'placeholder' => __('admin.page.seo_keywords_placeholder')
];
$seo_image = [
	'type' => 'file',
	'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'],
	'label' => __('admin.page.seo_image')
];
$no_index_no_follow = [
	'type' => 'switch',
	'default' => false,
	'label' => __('admin.page.set_no_index_no_follow')
];

// $custom_fields = [];
