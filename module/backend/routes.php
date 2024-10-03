<?php

use engine\router\Router;
use engine\http\Response;

############################# DASHBOARD #############################
Router::register('get', '/backend', function () {
  // TODO Router -> redirect by route name
  // Response::redirect('/backend/dashboard');
});
Router::register('get', '/backend/dashboard', 'Dashboard@getDashboard', 'dashboard', ['isPublic' => true]);
