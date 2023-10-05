<?php

############################# UPLOAD #############################
Route::get('/admin/upload', 'Upload@get');
Route::post('/admin/upload', 'Upload@post');
Route::delete('/admin/upload', 'Upload@delete');

############################# AUTH #############################
Route::get('/admin/login', 'Auth@getLogin', ['is_public' => true]);
Route::get('/admin/logout', 'Auth@getLogout', ['is_public' => true]);
Route::get('/admin/reset-password', 'Auth@getRestore', ['is_public' => true]);

############################# DASHBOARD #############################
Route::get('/admin', function() {
	Server::redirect('/admin/dashboard');
});
Route::get('/admin/dashboard', 'Dashboard@getDashboard', ['is_public' => true]);

// ############################# PROFILE #############################
// Route::get('/admin/profile', 'Profile@getProfile', ['is_public' => true]);
// Route::get('/admin/profile/edit', 'Profile@getEdit', ['is_public' => true]);
// Route::get('/admin/profile/$id', 'Profile@getProfile');

// Route::post('/admin/profile/notification', 'Profile@postNotification');
// Route::post('/admin/profile/$id/notification', 'Profile@postNotification');

// ############################# CONTACT #############################
// Route::get('/admin/contact', 'Contact@getAll');

############################# PAGE #############################
Route::get('/admin/page', 'Page@getAll');
Route::get('/admin/page/category/$id', 'Page@getCategory');
Route::get('/admin/page/add', 'Page@getAdd');
Route::get('/admin/page/edit/$id', 'Page@getEdit');
Route::get('/admin/page/edit/$id/translation/add/$language', 'Page@getAddTranslation');
Route::get('/admin/page/edit/$id/translation/edit/$language', 'Page@getEdit');

// ############################# COMMENT #############################
// Route::get('/admin/comment', 'Comment@getAll');
// Route::get('/admin/comment/edit/$id', 'Comment@getEdit');

// ############################# MENU #############################
// Route::get('/admin/menu', 'Menu@getAll');
// Route::get('/admin/menu/$id', 'Menu@getEdit');

// ############################# TRANSLATION #############################
// Route::get('/admin/translation', 'Translation@getAll');
// Route::get('/admin/translation/$module/add', 'Translation@getAdd');
// Route::get('/admin/translation/$module/$language', 'Translation@getEdit');

// Route::post('/admin/translation/$module/add', 'Translation@postAdd');
// Route::post('/admin/translation/$module/$language', 'Translation@postEdit');

############################# USER #############################
Route::get('/admin/user', 'User@getAll');
Route::get('/admin/user/add', 'User@getAdd');
Route::get('/admin/user/edit/$id', 'User@getEdit');

// ############################# GROUP #############################
Route::get('/admin/group', 'Group@getAll');
Route::get('/admin/group/add', 'Group@getAdd');
Route::get('/admin/group/edit/$id', 'Group@getEdit');

// ############################# SETTING #############################
// Route::get('/admin/setting/$section', 'Setting@getSection');

// Route::post('/admin/setting/$section', 'Setting@postSection');
// Route::post('/admin/setting/optimization/flush-cache', 'Setting@postFlushCache');

// ############################# LOG #############################
// Route::get('/admin/log', 'Log@getAll');
// Route::get('/admin/log/$id', 'Log@get');

// ############################# MODULE #############################
// Route::get('/admin/module', 'Module@getAll');
// Route::get('/admin/module/edit/$name', 'Module@getEdit');

// Route::post('/admin/module/edit/$name', 'Module@postEdit');
// Route::post('/admin/module/delete/$name', 'Module@postDelete');
// Route::post('/admin/module/toggle/$name', 'Module@postToggle');
