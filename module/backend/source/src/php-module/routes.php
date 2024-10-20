<?php

use engine\router\Router;

############################# DASHBOARD #############################
Router::register('get', ['/backend', '/backend/dashboard'], 'Dashboard@getDashboard', 'dashboard', ['isPublic' => true]);

############################# GROUP #############################
Router::register('get', '/backend/group', 'Group@getList', 'group-list');
Router::register('get', '/backend/group/add', 'Group@getAdd', 'group-add');
Router::register('get', '/backend/group/edit/$id', 'Group@getEdit', 'group-edit');
Router::register('get', '/backend/group/edit/$id/translation/add/$language', 'Group@getTranslationAdd', 'group-translation-add');
Router::register('get', '/backend/group/edit/$id/translation/edit/$language', 'Group@getTranslationEdit', 'group-translation-edit');
