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

// TODO
// $custom_fields = [
// 	'type' => 'text',
// 	'foreign' => function($value, $data) {
// 		updateCutomFields($value, $data);
// 	}
// ];

// function updateCutomFields($field_value, $data) {
// 	$fields = [];

// 	Module::setName('public');

// 	foreach(\Module\Admin\Model\Page::getInstance()->getPageCustomFieldSets($data->form_data['item_id']) as $fieldset) {
// 		$form_name = 'CustomFields/' . file_name($fieldset);

// 		Form::check($form_name);

// 		$fields = array_merge($fields, Form::processFields($form_name));
// 	}

// 	Module::setName('admin');

// 	$binding = ['page_id' => $data->form_data['item_id'], 'language' => $data->fields['language']];

// 	foreach($fields as $name => $value) {
// 		$binding['name'] = slug($name, '_');
// 		unset($binding['value']);

// 		$check_exist = new Statement('SELECT id FROM {custom_field} WHERE name = :name AND page_id = :page_id AND language = :language');
// 		$check_exist = $check_exist->execute($binding)->fetch();

// 		if($check_exist) {
// 			$sql = 'UPDATE {custom_field} SET value = :value WHERE name = :name AND page_id = :page_id AND language = :language';
// 		} else {
// 			$sql = 'INSERT INTO {custom_field} (name, value, page_id, language) VALUES (:name, :value, :page_id, :language)';
// 		}

// 		$binding['value'] = $value;

// 		$statement = new Statement($sql);
// 		$statement->execute($binding);
// 	}

// 	return true;
// }