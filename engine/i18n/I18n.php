<?php

namespace engine\i18n;

use engine\Config;
use engine\module\Module;
use engine\module\Setting;
use engine\http\Cookie;
use engine\util\File;
use engine\util\Path;
use engine\util\Text;

class I18n
{
  protected static $cookieKey;
  protected static $langWrap;
  protected static $isDebug;
  protected static $translation = [];
  protected static $translationFlat = [];
  protected static $loadedModules = [];

  public static function initialize()
  {
    self::loadVariables();
    self::loadFromFolder();

    return true;
  }

  public static function list($moduleName = null)
  {
    $moduleExtends = Module::getProperty('extends');

    $languagesCurrentModule = Module::getProperty('languages', $moduleName);
    $languagesExtendedModule = Module::getProperty('languages', $moduleExtends);

    return array_merge($languagesCurrentModule, $languagesExtendedModule);
  }

  public static function has($languageKey, $moduleName = null)
  {
    $moduleName = $moduleName ?? Module::getName();
    $moduleExtends = Module::getProperty('extends');

    if (isset(Module::getProperty('languages', $moduleName)[$languageKey])) {
      return true;
    }

    return isset(Module::getProperty('languages', $moduleExtends)[$languageKey]);
  }

  public static function get($languageKey = null, $moduleName = null)
  {
    $languageKey = self::getCurrent($moduleName);
    $moduleExtends = Module::getProperty('extends');

    if (self::has($languageKey, $moduleName)) {
      return Module::getProperty('languages', $moduleName)[$languageKey] ?? Module::getProperty('languages', $moduleExtends)[$languageKey];
    }

    return null;
  }

  public static function hasProperty($propertyName, $languageKey = null, $moduleName = null)
  {
    $languageKey = self::getCurrent($moduleName);
    $moduleExtends = Module::getProperty('extends');

    if (isset(Module::getProperty('languages', $moduleName)[$languageKey][$propertyName])) {
      return true;
    }

    return isset(Module::getProperty('languages', $moduleExtends)[$languageKey][$propertyName]);
  }

  public static function getProperty($propertyName, $languageKey = null, $moduleName = null)
  {
    $languageKey = self::getCurrent($moduleName);
    $moduleExtends = Module::getProperty('extends');

    if (self::hasProperty($propertyName, $languageKey, $moduleName)) {
      return Module::getProperty('languages', $moduleName)[$languageKey][$propertyName] ?? Module::getProperty('languages', $moduleExtends)[$languageKey][$propertyName];
    }

    return null;
  }

  public static function getCurrent($moduleName = null)
  {
    $languageDefault = Setting::getProperty('language', 'engine');
    $languageFromCookie = Cookie::has(self::$cookieKey) ? Cookie::get(self::$cookieKey) : null;

    if (!empty($languageFromCookie) && self::has($languageFromCookie, $moduleName)) {
      $language = $languageFromCookie;
    } else {
      $language = $languageDefault;
    }

    return $language;
  }

  public static function setCurrent($languageKey, $moduleName = null)
  {
    if (!self::has($languageKey, $moduleName)) {
      return false;
    }

    Cookie::set(self::$cookieKey, $languageKey);

    return true;
  }

  public static function translate($key, $data = null)
  {
    $moduleExtends = Module::getProperty('extends');
    if (!in_array($moduleExtends, self::$loadedModules)) {
      self::$loadedModules[] = $moduleExtends;
      self::loadTranslations($moduleExtends);
    };

    $moduleName = Module::getName();
    if (!in_array($moduleName, self::$loadedModules)) {
      self::$loadedModules[] = $moduleName;
      self::loadTranslations($moduleName);
    };

    $translation = self::$translationFlat[$key] ?? $key;

    if (isset($data) && is_numeric($data)) {
      $pluralValues = explode(' | ', $translation, 3);

      if (count($pluralValues) === 3) {
        return str_replace('{v}', $data, Text::pluralValue($data, $pluralValues) ?? '');
      } else {
        $translation = str_replace('{v}', $data, $translation);
      }
    } else if (isset($data) && is_array($data)) {
      foreach ($data as $key => $value) {
        if (is_bool($value)) {
          $value = $value === true ? 'true' : 'false';
        } else if (!is_scalar($value)) {
          $value = json_encode($value);
        }

        $translation = str_replace('{' . $key . '}', $value, $translation);
      }
    } else if (isset($data)) {
      $translation = str_replace('{v}', $data, $translation);
    }

    if (self::$isDebug && is_string($translation)) {
      $translation =  Text::html(self::$langWrap . $translation . self::$langWrap);
    }

    return $translation;
  }

  protected static function loadVariables()
  {
    self::$cookieKey = Config::getProperty('languageKey', 'cookie');
    self::$langWrap = Config::getProperty('langWrap', 'debug') ?? '';
    self::$isDebug = Config::getProperty('isEnabled', 'debug') ?? false;

    return true;
  }

  protected static function loadFromFolder()
  {
    foreach (Module::list() as $module) {
      if (!$module['isEnabled']) {
        continue;
      }

      Module::setName($module['name']);

      $path = Path::file('i18n');

      $languages = [];

      if (!file_exists($path)) {
        Module::setProperty($languages, 'languages');
        continue;
      }

      foreach (scandir($path) as $language) {
        if (in_array($language, ['.', '..'], true)) {
          continue;
        }

        if (File::getExtension($language) !== 'json') {
          continue;
        }

        @list($languageKey, $languageRegion) = explode('-', File::getName($language), 2);

        if (empty($languageKey) || empty($languageRegion)) {
          continue;
        }

        $languages[$languageKey] = [
          'module' => $module['name'],
          'key' => $languageKey,
          'region' => $languageRegion,
          'fileName' => $language,
          'filePath' => "$path/$language",
        ];
      }

      Module::setProperty($languages, 'languages');
    }

    Module::setName(null);

    return true;
  }

  protected static function loadTranslations($module)
  {
    $pathToLanguageFile = Path::resolve(Path::file('i18n', $module), self::getProperty('fileName'));

    if (!is_file($pathToLanguageFile)) {
      return false;
    }

    $content = file_get_contents($pathToLanguageFile);
    $content = json_decode($content, true);

    if (empty($content)) {
      return false;
    }

    self::$translation = array_merge(self::$translation, $content);
    self::$translationFlat = self::getFlattenTranslations(self::$translation);

    return true;
  }

  protected static function getFlattenTranslations($inputArr, $returnArr = array(), $prevKey = '')
  {
    foreach ($inputArr as $key => $value) {
      $newKey = $prevKey . $key;

      if (is_array($value) && key($value) !== 0 && key($value) !== null) {
        $returnArr = array_merge($returnArr, self::getFlattenTranslations($value, $returnArr, $newKey . '.'));
      } else {
        $returnArr[$newKey] = $value;
      }
    }

    return $returnArr;
  }
}
