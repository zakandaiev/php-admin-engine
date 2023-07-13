<?php

############################# PAGE #############################
Route::get('/', 'Page@getPage');
Route::get('/$url', 'Page@getPage');

############################# TAG #############################
Route::get('/tag/$url', 'Page@getTag');

############################# AUTHOR #############################
Route::get('/author/$id', 'Page@getAuthor');
