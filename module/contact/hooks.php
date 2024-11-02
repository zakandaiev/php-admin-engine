<?php

use engine\module\Hook;

############################# EXTEND BACKEND SIDEBAR #############################
Hook::run('sidebar.append.after', 'backend.setting.frontend', [
  'id' => 'contact.setting.contact',
  'text' => t('sidebar.contact'),
  'name' => 'setting.section',
  'parameter' => ['section' => 'contact'],
  'module' => 'backend'
]);

############################# EXTEND SETTING MODEL #############################
Hook::run('setting.column.add', 'address', [
  'type' => 'text',
  'min' => 2,
  'max' => 256,
  'value' => site('address')
]);
Hook::run('setting.column.add', 'coordinate_x', [
  'type' => 'text',
  'min' => 4,
  'max' => 16,
  'className' => 'col-xs-12 col-md-6 col-lg-3'
]);
Hook::run('setting.column.add', 'coordinate_y', [
  'type' => 'text',
  'min' => 4,
  'max' => 16,
  'className' => 'col-xs-12 col-md-6 col-lg-3'
]);
Hook::run('setting.column.add', 'work_hours', [
  'type' => 'text',
  'value' => site('work_hours')
]);
Hook::run('setting.column.add', 'email', [
  'type' => 'email',
  'min' => 6,
  'max' => 256
]);
Hook::run('setting.column.add', 'phones', [
  'type' => 'table',
  'model' => 'table'
]);
