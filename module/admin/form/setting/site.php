<?php

require Path::file('form') . '/_model/Setting.php';

return [
	'table' => 'setting',
	'fields' => [
		'logo_admin' => $logo_admin,
		'logo_alt_admin' => $logo_alt_admin,
		'logo_public' => $logo_public,
		'logo_alt_public' => $logo_alt_public,
		'placeholder_avatar_admin' => $placeholder_avatar_admin,
		'placeholder_image_admin' => $placeholder_image_admin,
		'placeholder_avatar_public' => $placeholder_avatar_public,
		'placeholder_image_public' => $placeholder_image_public,
		'favicon_admin' => $favicon_admin,
		'favicon_public' => $favicon_public,
		'name' => $name,
		'description' => $description,
		'analytics_gtag' => $analytics_gtag,
		'no_index_no_follow' => $no_index_no_follow
	],
	'execute_pre' => function ($fields, $data) {
		foreach ($fields as $field) {
			if (in_array($field['name'], ['name', 'description', 'address', 'hours'])) {
				$value = Setting::get('engine')->{$field['name']} ?? new \stdClass();
				$value->{site('language_current')} = $field['value'];
				$field['value'] = $value;
			}

			Setting::update('engine', $field['name'], $field['value']);
		}

		Server::answer(null, 'success', __('admin.setting.submit_success'));
	}
];
