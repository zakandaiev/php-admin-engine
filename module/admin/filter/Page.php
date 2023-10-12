<?php

return [
	'title' => [
		'column' => 'name',
		'type' => 'text',
		'label' => __('admin.page.name'),
		'placeholder' => __('admin.page.name_placeholder'),
		'col_class' => 'col-xs-12 col-md-6 col-lg-3 col-xxl-12'
	],
	'author' => [
		'column' => 'author',
		'type' => 'select',
		'label' => __('admin.page.author'),
		'placeholder' => __('admin.page.author_placeholder'),
		'col_class' => 'col-xs-12 col-md-6 col-lg-3 col-xxl-12'
	],
	'created' => [
		'column' => 'date_created',
		'type' => 'date',
		'range' => true,
		'label' => __('admin.page.date_created'),
		'placeholder' => __('admin.page.enter_date_created'),
		'col_class' => 'col-xs-12 col-md-6 col-lg-3 col-xxl-12'
	],
	'enabled' => [
		'column' => 'is_enabled',
		'type' => 'radio',
		'show_all_options' => true,
		'classifier' => 'admin.classifier.page.is_enabled',
		'label' => __('admin.page.is_enabled'),
		'col_class' => 'col-xs-12 col-md-6 col-lg-3 col-xxl-12'
	],
	'otitle' => [
		'column' => 'title',
		'classifier' => 'admin.classifier.sort',
		'label' => __('admin.page.title'),
		'selected_label' => true,
		'type' => 'order'
	],
	'otranslations' => [
		'column' => 'translations',
		'classifier' => 'admin.classifier.sort',
		'label' => __('admin.page.translations'),
		'selected_label' => true,
		'type' => 'order'
	],
	'oauthor' => [
		'column' => 'author',
		'classifier' => 'admin.classifier.sort',
		'label' => __('admin.page.author'),
		'selected_label' => true,
		'type' => 'order'
	],
	'ocreated' => [
		'column' => 'date_created',
		'classifier' => 'admin.classifier.sort',
		'label' => __('admin.page.date_created'),
		'selected_label' => true,
		'type' => 'order'
	],
	'oenabled' => [
		'column' => 'is_enabled',
		'classifier' => 'admin.classifier.sort',
		'label' => __('admin.page.is_enabled'),
		'selected_label' => true,
		'type' => 'order'
	]
];
