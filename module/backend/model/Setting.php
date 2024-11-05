<?php

namespace module\backend\model;

use engine\database\Model;
use engine\module\Hook;
use engine\module\Setting as ModuleSetting;

class Setting extends Model
{
  public function __construct()
  {
    parent::__construct();

    $this->setTable('setting');
    $this->setPrimaryKey('id');

    $this->setColumn('language', [
      'type' => 'select',
      'required' => true,
      'value' => site('language')
    ]);

    $this->setColumn('enable_registration', [
      'type' => 'boolean',
      'value' => true
    ]);

    $this->setColumn('enable_password_restore', [
      'type' => 'boolean',
      'value' => true
    ]);

    $this->setColumn('moderate_comments', [
      'type' => 'boolean',
      'value' => false
    ]);

    $this->setColumn('no_index_no_follow', [
      'type' => 'boolean',
      'value' => false
    ]);

    $this->setColumn('name', [
      'type' => 'text',
      'required' => true,
      'min' => 2,
      'max' => 256
    ]);

    $this->setColumn('description', [
      'type' => 'textarea',
      'min' => 2,
      'max' => 1024
    ]);

    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];

    $this->setColumn('favicon', [
      'type' => 'file',
      'extensions' => $imageExtensions
    ]);

    $this->setColumn('logo', [
      'type' => 'file',
      'extensions' => $imageExtensions
    ]);

    $this->setColumn('logo_alt', [
      'type' => 'file',
      'extensions' => $imageExtensions
    ]);

    $this->setColumn('placeholder_avatar', [
      'type' => 'file',
      'extensions' => $imageExtensions
    ]);

    $this->setColumn('placeholder_image', [
      'type' => 'file',
      'extensions' => $imageExtensions
    ]);

    $this->setColumn('pagination_limit', [
      'type' => 'number',
      'required' => true,
      'min' => 10,
      'max' => 1000
    ]);

    $columnHookData = Hook::getData('setting.column') ?? [];
    foreach ($columnHookData as $columnName => $columnModel) {
      $this->setColumn($columnName, $columnModel);
    }
  }

  public function editSection()
  {
    if (!$this->hasTable()) {
      return false;
    }

    $this->modifyColumns();

    $this->validate();
    if ($this->hasError()) {
      return false;
    }

    $this->prepareMediaColumns();
    $resultMedia = $this->processMediaColumns();
    if (!$resultMedia) {
      return false;
    }

    foreach ($this->getColumnsToValidate() as $settingName => $setting) {
      ModuleSetting::setProperty($setting['value'], $settingName, $this->itemId);
    }

    return true;
  }
}
