<?php

/*
	SYNTAX:
		Route::$method('/$url/$dynamic_variable', '$controller_name@$controller_method|$closure', $options);
			where:
			- [required] $method: 'get|post|put|patch|delete|options|any'
			- [required] $url: should start with slash, can contain dynamic variables in every part
			- $dynamic_variable: dynamic variable read from url that could used in controller method with $this->route['parameter']
			- [required if not closure] $controller_name: controller's name
			- [required if not closure] $controller_method: controller's method that should be called
			- [required if not controller] $closure: function closure that runs instantly instead of controller
			- $options: array of options that could be read and used in controller method with $this->route['option']
*/

############################# DIVIDE SECTION #############################
Route::get('/user/$id/payments', 'User@getUserPayments', ['dummy_option' => 'dummy_data']);

Route::get('/closure-example', function($data) {
	debug($data);
});

Route::post('/post-example', 'Api@getPost');

Route::put('/put-example', 'Api@getPut');
