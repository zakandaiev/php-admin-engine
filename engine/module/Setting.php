<?php

namespace engine\module;

use engine\module\Module;
use engine\database\Query;

class Setting
{
  protected static $setting;

  public static function initialize()
  {
    self::$setting = self::loadFromDatabase();

    return true;
  }

  public static function exists($moduleName)
  {
    return isset(self::$setting->{$moduleName});
  }

  public static function list()
  {
    return self::$setting;
  }

  public static function get($moduleName = null)
  {
    $moduleName = $moduleName ?? Module::getName();

    return self::exists($moduleName) ? self::$setting->{$moduleName} : null;
  }

  public static function hasProperty($propertyName, $moduleName = null)
  {
    $moduleName = $moduleName ?? Module::getName();

    return property_exists(self::$setting->{$moduleName} ?? new \stdClass, $propertyName);
  }

  public static function setProperty($data, $propertyName, $moduleName = null)
  {
    $moduleName = $moduleName ?? Module::getName();

    if (!self::exists($moduleName)) {
      return false;
    }

    $currentLanguage = site('language_current');
    $siteLanguage = site('language');

    $oldPropertyValue = self::$setting->{$moduleName}->{$propertyName};
    $newPropertyValue = $data;

    if (
      is_object($oldPropertyValue)
      && (
        property_exists($oldPropertyValue, $currentLanguage)
        || property_exists($oldPropertyValue, $siteLanguage)
      )
    ) {
      $oldPropertyValue->$currentLanguage = $data;
      $newPropertyValue = $oldPropertyValue;
    }

    self::$setting->{$moduleName}->{$propertyName} = $newPropertyValue;

    self::update($moduleName, $propertyName, $newPropertyValue);

    return true;
  }

  public static function getProperty($propertyName, $moduleName = null)
  {
    $moduleName = $moduleName ?? Module::getName();

    return self::hasProperty($propertyName, $moduleName) ? self::$setting->{$moduleName}->{$propertyName} : null;
  }

  public static function update($module, $name, $value = null)
  {
    if (is_bool($value)) {
      $value = $value ? 'true' : 'false';
    }

    $params = ['module' => $module, 'name' => $name, 'value' => $value];

    $statement = new Query('UPDATE {setting} SET value = :value WHERE module = :module AND name = :name');

    $statement->execute($params);

    // TODO
    // $user_id = @User::get()->id ?? 'unlogged';
    // $user_ip = Request::ip();
    // Log::write("Setting: $name changed by user ID: $user_id from IP: $user_ip", 'setting');

    // Hook::run('setting.update', $params);
    // Hook::run('setting.update.' . $name, $params);

    return true;
  }

  protected static function loadFromDatabase()
  {
    $setting = [];

    $settings = new Query('SELECT * FROM {setting}');
    $settings = $settings->execute()->fetchAll();

    foreach ($settings as $row) {
      $value = $row->value;

      if ($value === 'true') {
        $value = true;
      } else if ($value === 'false') {
        $value = false;
      } else if (is_numeric($value)) {
        $value = stringToNumber($value);
      } else if (isJson($value)) {
        $value = json_decode($value);
      }

      $setting[$row->module][$row->name] = $value;
    }

    return json_decode(json_encode($setting, JSON_UNESCAPED_UNICODE));
  }
}
