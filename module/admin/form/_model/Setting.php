<?php

$language = [
	'type' => 'select',
	'required' => true,
	'label' => __('admin.setting.language_label'),
	'col_class' => 'col-xs-12'
];

$enable_registration = [
	'type' => 'switch',
	'default' => true,
	'label' => __('admin.setting.enable_registration_label'),
	'col_class' => 'col-xs-12'
];

$enable_password_restore = [
	'type' => 'switch',
	'default' => true,
	'label' => __('admin.setting.enable_password_restore_label'),
	'col_class' => 'col-xs-12'
];

$moderate_comments = [
	'type' => 'switch',
	'default' => false,
	'label' => __('admin.setting.moderate_comments_label'),
	'col_class' => 'col-xs-12'
];

$pagination_limit = [
	'type' => 'number',
	'default' => 10,
	'min' => 1,
	'label' => __('admin.setting.pagination_limit_label'),
	'placeholder' => __('admin.setting.pagination_limit_placeholder'),
	'col_class' => 'col-xs-12'
];

$logo_admin = [
	'type' => 'file',
	'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'],
	'label' => __('admin.setting.logo_admin_label'),
	'col_class' => 'col-xs-12 col-md-6 col-lg-3'
];

$logo_alt_admin = [
	'type' => 'file',
	'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'],
	'label' => __('admin.setting.logo_alt_admin_label'),
	'col_class' => 'col-xs-12 col-md-6 col-lg-3'
];

$logo_public = [
	'type' => 'file',
	'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'],
	'label' => __('admin.setting.logo_public_label'),
	'col_class' => 'col-xs-12 col-md-6 col-lg-3'
];

$logo_alt_public = [
	'type' => 'file',
	'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'],
	'label' => __('admin.setting.logo_alt_public_label'),
	'col_class' => 'col-xs-12 col-md-6 col-lg-3'
];

$placeholder_avatar_admin = [
	'type' => 'file',
	'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'],
	'label' => __('admin.setting.placeholder_avatar_admin_label'),
	'col_class' => 'col-xs-12 col-md-6 col-lg-3'
];

$placeholder_image_admin = [
	'type' => 'file',
	'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'],
	'label' => __('admin.setting.placeholder_image_admin_label'),
	'col_class' => 'col-xs-12 col-md-6 col-lg-3'
];

$placeholder_avatar_public = [
	'type' => 'file',
	'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'],
	'label' => __('admin.setting.placeholder_avatar_public_label'),
	'col_class' => 'col-xs-12 col-md-6 col-lg-3'
];

$placeholder_image_public = [
	'type' => 'file',
	'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'],
	'label' => __('admin.setting.placeholder_image_public_label'),
	'col_class' => 'col-xs-12 col-md-6 col-lg-3'
];

$favicon_admin = [
	'type' => 'file',
	'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'],
	'label' => __('admin.setting.favicon_admin_label'),
	'col_class' => 'col-xs-12 col-md-6 col-lg-3'
];

$favicon_public = [
	'type' => 'file',
	'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'],
	'label' => __('admin.setting.favicon_public_label'),
	'col_class' => 'col-xs-12 col-md-6 col-lg-3'
];

$name = [
	'type' => 'text',
	'required' => true,
	'min' => 1,
	'max' => 200,
	'label' => __('admin.setting.name_label'),
	'placeholder' => __('admin.setting.name_placeholder'),
	'col_class' => 'col-xs-12'
];

$description = [
	'type' => 'text',
	'max' => 1000,
	'label' => __('admin.setting.description_label'),
	'placeholder' => __('admin.setting.description_placeholder'),
	'col_class' => 'col-xs-12'
];

$analytics_gtag = [
	'type' => 'text',
	'max' => 30,
	'label' => __('admin.setting.analytics_gtag_label'),
	'placeholder' => __('admin.setting.analytics_gtag_placeholder'),
	'col_class' => 'col-xs-12'
];

$no_index_no_follow = [
	'type' => 'switch',
	'default' => true,
	'label' => __('admin.setting.no_index_no_follow_label'),
	'col_class' => 'col-xs-12'
];

$address = [
	'type' => 'text',
	'max' => 200,
	'label' => __('admin.setting.address_label'),
	'placeholder' => __('admin.setting.address_placeholder'),
	'col_class' => 'col-xs-12'
];

$coordinate_x = [
	'type' => 'number',
	'step' => 'any',
	'label' => __('admin.setting.coordinate_x_label'),
	'placeholder' => __('admin.setting.coordinate_x_placeholder'),
	'col_class' => 'col-xs-12 col-md-6'
];

$coordinate_y = [
	'type' => 'number',
	'step' => 'any',
	'label' => __('admin.setting.coordinate_y_label'),
	'placeholder' => __('admin.setting.coordinate_y_placeholder'),
	'col_class' => 'col-xs-12 col-md-6'
];

$hours = [
	'type' => 'text',
	'max' => 200,
	'label' => __('admin.setting.hours_label'),
	'placeholder' => __('admin.setting.hours_placeholder'),
	'col_class' => 'col-xs-12'
];

$email = [
	'type' => 'email',
	'min' => 6,
	'max' => 200,
	'label' => __('admin.setting.email_label'),
	'placeholder' => __('admin.setting.email_placeholder'),
	'col_class' => 'col-xs-12'
];

$phones = [
	'type' => 'text',
	'label' => __('admin.setting.phones_label'),
	'placeholder' => __('admin.setting.phones_placeholder'),
	'col_class' => 'col-xs-12'
];

$group_css_label = '<span class="d-flex flex-column gap-1 ml-1">';
$group_css_label .= '<span class="cursor-pointer">' . __('admin.setting.group_css_label') . '</span>';
if (site('group_css') != 'false' && !empty(site('group_css'))) {
	$file_url = Path::url('asset', 'public') . '/css/' . site('group_css') . '.css';
	$file_path = Path::file('asset', 'public') . '/css/' . site('group_css') . '.css';
	$group_css_label .= '<a class="font-size-12" href="' . $file_url . '" target="_blank">' . __('admin.setting.group_css_size') . ': ' . file_size($file_path) . '</a>';
}
$group_css_label .= '</span>';
$group_css = [
	'type' => 'switch',
	'default' => false,
	'label_html' => $group_css_label,
	'col_class' => 'col-xs-12'
];

$group_js_label = '<span class="d-flex flex-column gap-1 ml-1">';
$group_js_label .= '<span class="cursor-pointer">' . __('admin.setting.group_js_label') . '</span>';
if (site('group_js') != 'false' && !empty(site('group_js'))) {
	$file_url = Path::url('asset', 'public') . '/css/' . site('group_js') . '.css';
	$file_path = Path::file('asset', 'public') . '/css/' . site('group_js') . '.css';
	$group_js_label .= '<a class="font-size-12" href="' . $file_url . '" target="_blank">' . __('admin.setting.group_js_size') . ': ' . file_size($file_path) . '</a>';
}
$group_js_label .= '</span>';
$group_js = [
	'type' => 'switch',
	'default' => false,
	'label_html' => $group_js_label,
	'col_class' => 'col-xs-12'
];

$cache_db_label = '<span class="d-flex flex-column gap-1 ml-1">';
$cache_db_label .= '<span class="cursor-pointer">' . __('admin.setting.cache_db_label') . '</span>';
if (site('cache_db') == 'true') {
	$cache_db_label .= '<span class="color-link cursor-pointer font-size-12" data-action="' . Form::add('setting/flush_cache') . '" data-remove="this" data-confirm="' . __('admin.setting.flush_cache_confirm_title') . '?">' . __('admin.setting.cache_size') . ': ' . file_size(Path::file('cache')) . '</ы>';
}
$cache_db_label .= '</span>';
$cache_db = [
	'type' => 'switch',
	'default' => false,
	'label_html' => $cache_db_label,
	'col_class' => 'col-xs-12'
];
