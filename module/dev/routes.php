<?php

use engine\router\Router;

############################# LOG #############################
Router::register('get', '/dev/log', 'Log@getList', 'log.list');
Router::register('get', '/dev/log/$file', 'Log@getView', 'log.view');

############################# UI #############################
Router::register('get', '/dev/ui', 'UI@getHome', 'ui.home');
Router::register('get', '/dev/ui/$section', 'UI@getSection', 'ui.section');
