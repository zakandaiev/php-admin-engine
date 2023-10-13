<?php

############################# PAGE #############################
Route::get('/', 'Page@getPage');
Route::get('/$url', 'Page@getPage');

############################# AUTHOR #############################
Route::get('/author/$id', 'Page@getAuthor');
