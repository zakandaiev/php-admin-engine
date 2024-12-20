<?php

use engine\router\Router;

############################# AUTH #############################
Router::register('get', '/backend/login', 'Auth@getLogin', 'login', ['is_public' => true]);
Router::register('get', '/backend/logout', 'Auth@getLogout', 'logout', ['is_public' => true]);
Router::register('get', '/backend/registration', 'Auth@getRegistration', 'registration', ['is_public' => true]);
Router::register('get', '/backend/reset-password', 'Auth@getRestore', 'reset-password', ['is_public' => true]);

############################# DASHBOARD #############################
Router::register('get', ['/backend', '/backend/dashboard'], 'Dashboard@getDashboard', 'dashboard', ['isPublic' => true]);






############################# TRANSLATION #############################
Router::register('get', '/backend/translation', 'Translation@getList', 'translation.list');
Router::register('get', '/backend/translation/add', 'Translation@getAdd', 'translation.add');
Router::register('get', '/backend/translation/edit/$module/$language', 'Translation@getEdit', 'translation.edit');

############################# FEEDBACK #############################
Router::register('get', '/backend/feedback', 'Feedback@getList', 'feedback.list');
Router::register('get', '/backend/feedback/reply/$id', 'Feedback@getReply', 'feedback.reply');

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

############################# SETTING #############################
Router::register('get', '/backend/setting/$section', 'Setting@getSection', 'setting.section');

############################# UPLOAD #############################
Router::register('post', '/backend/upload/$section', 'Upload@getSection', 'upload.section');
