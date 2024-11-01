<?php

namespace module\backend\model;

use engine\database\Model;
use engine\module\Setting as ModuleSetting;

class Setting extends Model
{
  public function __construct($columnData = null, $columnKeysToValidate = null)
  {
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
      'min' => 10,
      'max' => 1000
    ]);

    parent::__construct($columnData, $columnKeysToValidate);
  }

  public function editSection()
  {
    if (empty($this->table) || empty($this->column)) {
      return false;
    }

    $this->validate();
    if ($this->hasError()) {
      return false;
    }

    foreach ($this->getColumn() as $settingName => $setting) {
      ModuleSetting::setProperty($setting['value'], $settingName, $this->itemId);
    }

    return true;
  }
}
