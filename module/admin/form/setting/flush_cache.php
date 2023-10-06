<?php

return [
	'table' => 'setting',
	'fields' => [],
	'execute_pre' => function($fields, $data) {
		Cache::flush();

		Server::answer(null, 'success', __('admin.setting.submit_flush_cache'));
	}
];
