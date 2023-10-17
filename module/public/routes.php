<?php

############################# PAGE #############################
Router::register('get', '/', 'Page@getPage');
Router::register('get', '/$url', 'Page@getPage');

############################# AUTHOR #############################
Router::register('get', '/author/$id', 'Page@getAuthor');
