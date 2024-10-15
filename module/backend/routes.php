<?php

use engine\router\Router;

############################# DASHBOARD #############################
Router::register('get', ['/backend', '/backend/dashboard'], 'Dashboard@getDashboard', 'dashboard', ['isPublic' => true]);

############################# GROUP #############################
Router::register('get', '/backend/group', 'Group@getList', 'group-list');
Router::register('get', '/backend/group/add', 'Group@getAdd', 'group-add');
Router::register('get', '/backend/group/edit/$id', 'Group@getEdit', 'group-edit');
