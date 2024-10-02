<?php

use engine\router\Router;

/*
  SYNTAX:
    Router::register('$method', '/$url/$dynamicVariable', '$controllerName@$controllerMethod|$closure', $name, $options);
      where:
      - [required] $method: 'get|post|put|patch|delete|options|any'
      - [required] $url: should start with slash, can contain dynamic variables in every part
      - $dynamicVariable: dynamic variable stored in controller at $this->route['parameter']
      - [required if not closure] $controllerName: controller's name
      - [required if not closure] $controllerMethod: controller's method that will be called
      - [required if not controller] $closure: function closure that runs instantly instead of controller
      - $name: route's name
      - $options: array of options stored in controller at $this->route['option']
*/

############################# DIVIDE SECTION #############################
Router::register('get', '/_dummy', 'Dummy@getHome', 'home');

Router::register('get', '/_dummy/guide', 'Dummy@getGuide', 'guide', ['dummyTestKey' => 'dummyTestValue']);

Router::register('get', '/_dummy/user/$uid/payment/$pid', function ($parameter, $option, $route) {
  echo '<h1>Parameters:</h1>';
  debug($parameter);

  echo '<br><h1>Options:</h1>';
  debug($option);

  echo '<br><h1>Route:</h1>';
  debug($route);
}, 'user-payment', ['dummyKey' => 'dummyValue']);

Router::register('post', '/_dummy/post-example', 'Dummy@postExample', 'post-example');

Router::register('put', '/_dummy/put-example', 'Dummy@putExample', 'put-example');
