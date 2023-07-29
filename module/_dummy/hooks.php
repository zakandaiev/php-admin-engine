<?php

############################# SET DATA #############################
Hook::setData('dummy_key', 'dummy_data');

############################# GET DATA #############################
debug(Hook::getData('dummy_key'));

############################# REGISTER #############################
Hook::register('dummy_hook', function($data1, $data2) {
	debug($data1, $data2);
});

############################# RUN #############################
Hook::run('dummy_hook', 'Hook dummy_hook is running', 'Yeah!');
