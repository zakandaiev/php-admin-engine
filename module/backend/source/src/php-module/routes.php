<?php

use engine\router\Router;

############################# AUTH #############################
Router::register('get', '/backend/login', 'Auth@getLogin', 'login', ['is_public' => true]);
Router::register('get', '/backend/logout', 'Auth@getLogout', 'logout', ['is_public' => true]);
Router::register('get', '/backend/registration', 'Auth@getRegistration', 'registration', ['is_public' => true]);
Router::register('get', '/backend/reset-password', 'Auth@getRestore', 'reset-password', ['is_public' => true]);

############################# DASHBOARD #############################
Router::register('get', ['/backend', '/backend/dashboard'], 'Dashboard@getDashboard', 'dashboard', ['isPublic' => true]);

############################# GROUP #############################
Router::register('get', '/backend/group', 'Group@getList', 'group.list');
Router::register('get', '/backend/group/add', 'Group@getAdd', 'group.add');
Router::register('get', '/backend/group/edit/$id', 'Group@getEdit', 'group.edit');
Router::register('get', '/backend/group/edit/$id/translation/add/$language', 'Group@getTranslationAdd', 'group.translation.add');
Router::register('get', '/backend/group/edit/$id/translation/edit/$language', 'Group@getTranslationEdit', 'group.translation.edit');

############################# USER #############################
Router::register('get', '/backend/user', 'User@getList', 'user.list');
Router::register('get', '/backend/user/add', 'User@getAdd', 'user.add');
Router::register('get', '/backend/user/edit/$id', 'User@getEdit', 'user.edit');
