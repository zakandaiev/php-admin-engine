<?php

use engine\module\Hook;

############################# SET DATA #############################
Hook::setData('dummy.key', 'dummy.data');

############################# GET DATA #############################
// debug(Hook::getData('dummy.key'));

############################# REGISTER #############################
Hook::register('dummy.hook', function ($data1, $data2) {
  debug($data1, $data2);
});

############################# RUN #############################
// Hook::run('dummy.hook', 'Hook dummy.hook is running', 'Yeah!');
