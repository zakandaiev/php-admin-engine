<?php

require Path::file('form') . '/_model/Setting.php';

return [
	'table' => 'setting',
	'fields' => [
		'address' => $address,
		'coordinate_x' => $coordinate_x,
		'coordinate_y' => $coordinate_y,
		'hours' => $hours,
		'email' => $email,
		'phones' => $phones
	],
	'execute_pre' => function($fields, $data) {
		foreach($fields as $field) {
			if(in_array($field['name'], ['name', 'description', 'address', 'hours'])) {
				$value = Setting::get('engine')->{$field['name']} ?? new \stdClass();
				$value->{site('language_current')} = $field['value'];
				$field['value'] = $value;
			}

			Setting::update('engine', $field['name'], $field['value']);
		}

		Server::answer(null, 'success', __('admin.setting.submit_success'));
	}
];
