<?php

############################# AUTH #############################
Router::register('get', '/admin/login', 'Auth@getLogin', ['is_public' => true]);
Router::register('get', '/admin/logout', 'Auth@getLogout', ['is_public' => true]);
Router::register('get', '/admin/reset-password', 'Auth@getRestore', ['is_public' => true]);

############################# DASHBOARD #############################
Router::register('get', '/admin', function() {
	Server::redirect('/admin/dashboard');
});
Router::register('get', '/admin/dashboard', 'Dashboard@getDashboard', ['is_public' => true]);

// ############################# PROFILE #############################
// Router::register('get', '/admin/profile', 'Profile@getProfile', ['is_public' => true]);
// Router::register('get', '/admin/profile/edit', 'Profile@getEdit', ['is_public' => true]);
// Router::register('get', '/admin/profile/$id', 'Profile@getProfile');

// Router::register('post', '/admin/profile/notification', 'Profile@postNotification');
// Router::register('post', '/admin/profile/$id/notification', 'Profile@postNotification');

// ############################# CONTACT #############################
// Router::register('get', '/admin/contact', 'Contact@getAll');

############################# PAGE #############################
Router::register('get', '/admin/page', 'Page@getAll');
Router::register('get', '/admin/page/category/$id', 'Page@getCategory');
Router::register('get', '/admin/page/add', 'Page@getAdd');
Router::register('get', '/admin/page/edit/$id', 'Page@getEdit');
Router::register('get', '/admin/page/edit/$id/translation/add/$language', 'Page@getAddTranslation');
Router::register('get', '/admin/page/edit/$id/translation/edit/$language', 'Page@getEdit');

// ############################# COMMENT #############################
// Router::register('get', '/admin/comment', 'Comment@getAll');
// Router::register('get', '/admin/comment/edit/$id', 'Comment@getEdit');

// ############################# MENU #############################
// Router::register('get', '/admin/menu', 'Menu@getAll');
// Router::register('get', '/admin/menu/$id', 'Menu@getEdit');

// ############################# TRANSLATION #############################
// Router::register('get', '/admin/translation', 'Translation@getAll');
// Router::register('get', '/admin/translation/$module/add', 'Translation@getAdd');
// Router::register('get', '/admin/translation/$module/$language', 'Translation@getEdit');

// Router::register('post', '/admin/translation/$module/add', 'Translation@postAdd');
// Router::register('post', '/admin/translation/$module/$language', 'Translation@postEdit');

############################# USER #############################
Router::register('get', '/admin/user', 'User@getAll');
Router::register('get', '/admin/user/add', 'User@getAdd');
Router::register('get', '/admin/user/edit/$id', 'User@getEdit');

// ############################# GROUP #############################
Router::register('get', '/admin/group', 'Group@getAll');
Router::register('get', '/admin/group/add', 'Group@getAdd');
Router::register('get', '/admin/group/edit/$id', 'Group@getEdit');
Router::register('get', '/admin/group/edit/$id/translation/add/$language', 'Group@getAddTranslation');
Router::register('get', '/admin/group/edit/$id/translation/edit/$language', 'Group@getEdit');

// ############################# SETTING #############################
Router::register('get', '/admin/setting/$section', 'Setting@getSection');

// ############################# LOG #############################
// Router::register('get', '/admin/log', 'Log@getAll');
// Router::register('get', '/admin/log/$id', 'Log@get');

// ############################# MODULE #############################
// Router::register('get', '/admin/module', 'Module@getAll');
// Router::register('get', '/admin/module/edit/$name', 'Module@getEdit');

// Router::register('post', '/admin/module/edit/$name', 'Module@postEdit');
// Router::register('post', '/admin/module/delete/$name', 'Module@postDelete');
// Router::register('post', '/admin/module/toggle/$name', 'Module@postToggle');

// ############################# UPLOAD #############################
// Router::register('get', '/admin/upload', 'Upload@get');
// Router::register('post', '/admin/upload', 'Upload@post');
// Router::register('delete', '/admin/upload', 'Upload@delete');
