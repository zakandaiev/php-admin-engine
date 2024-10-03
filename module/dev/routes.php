<?php

use engine\router\Router;

############################# UI #############################
Router::register('get', '/dev/ui', 'UI@getHome', 'ui-home');
Router::register('get', '/dev/ui/$section', 'UI@getSection', 'ui-section');
