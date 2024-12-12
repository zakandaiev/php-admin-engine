<?php

namespace module\backend\model;

use engine\database\Model;
use engine\http\Request;
use engine\i18n\I18n;
use engine\module\Hook;
use engine\module\Module;
use engine\util\File;
use engine\util\Path;

class Translation extends Model
{
  public function __construct()
  {
    parent::__construct();

    $this->setTable('translation');

    $this->setColumn('module', [
      'type' => 'select',
      'required' => true,
      'options' => function () {
        return $this->getModuleOptions();
      },
      'value' => Request::get('module')
    ]);

    $this->setColumn('language', [
      'type' => 'text',
      'required' => true,
      'min' => 2,
      'max' => 2,
      'regex' => '/[a-z]{2}/'
    ]);

    $this->setColumn('region', [
      'type' => 'text',
      'required' => true,
      'min' => 2,
      'max' => 2,
      'regex' => '/[a-z]{2}/'
    ]);

    $this->setColumn('icon', [
      'type' => 'file',
      'required' => true,
      'extensions' => ['png'],
      'folder' => Path::resolve('../', Path::file('asset', null, true), 'img', 'i18n')
    ]);

    $this->setColumn('translation', [
      'type' => 'textarea'
    ]);
  }

  public function add()
  {
    $this->modifyColumns();

    $this->validate();
    if ($this->hasError()) {
      return false;
    }

    $this->prepareMediaColumns();

    $module = $this->getColumnValue('module');
    $language = $this->getColumnValue('language');
    $region = $this->getColumnValue('region');
    $icon = $this->getColumnProperty('icon', 'upload');

    if (I18n::has($language, $module)) {
      $this->setError('language', I18n::translate('translation.add.language_already_exists'));
      return false;
    }

    if ($icon) {
      $icon->setName($language);
    }

    $resultMedia = $this->processMediaColumns();
    if (!$resultMedia) {
      return false;
    }

    $translationFolder = Path::file('i18n', $module);
    $translationFile = "$language-$region.json";
    $translationPath = Path::resolve($translationFolder, $translationFile);
    $translationContent = "{\n  \"hello\": \"world\"\n}\n";

    File::createFile($translationPath, $translationContent);

    Hook::run('translation.add', $translationFile, $module);

    return ['module' => $module, 'language' => $language];
  }

  public function edit()
  {
    $this->modifyColumns();

    $this->validate();
    if ($this->hasError()) {
      return false;
    }

    list($module, $filename) = explode('@', $this->getItemId() ?? '', 2);
    if (empty($module) || empty($filename)) {
      return false;
    }

    $translationFolder = Path::file('i18n', $module);
    $translationFile = "$filename.json";
    $translationPath = Path::resolve($translationFolder, $translationFile);
    $translationContent = $this->getColumnValue('translation');

    File::createFile($translationPath, trim($translationContent ?? ''));

    Hook::run('translation.edit', $translationFile, $module);

    return true;
  }

  public function delete()
  {
    $this->modifyColumns();

    $this->validate();
    if ($this->hasError()) {
      return false;
    }

    list($module, $filename) = explode('@', $this->getItemId() ?? '', 2);
    if (empty($module) || empty($filename)) {
      return false;
    }

    $translationFolder = Path::file('i18n', $module);
    $translationFile = "$filename.json";
    $translationPath = Path::resolve($translationFolder, $translationFile);

    File::delete($translationPath);

    Hook::run('translation.delete', $translationFile, $module);

    return true;
  }

  public function getModuleTranslationContent($module, $language)
  {
    return File::getContent(@Module::getProperty('languages', $module)[$language]['filePath']);
  }

  public function getModuleOptions()
  {
    $moduleOptions = array_map(function ($module) {
      $u = new \stdClass();

      $u->text = $module['name'];
      $u->value = $module['name'];

      return $u;
    }, Module::list());

    return $moduleOptions;
  }
}
