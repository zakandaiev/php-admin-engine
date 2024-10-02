<?php

namespace engine\module;

use engine\util\Path;

class Module
{
  protected static $name;
  protected static $moduleList = [];
  protected static $isHooksLoaded = false;

  public static function initialize()
  {
    self::loadFromFolder();

    return true;
  }

  public static function setName($name)
  {
    self::$name = $name;

    return true;
  }

  public static function getName()
  {
    return self::$name;
  }

  public static function exists($moduleName)
  {
    return isset(self::$moduleList[$moduleName]);
  }

  public static function list()
  {
    return self::$moduleList;
  }

  public static function get($moduleName = null)
  {
    // TODO add support for extended modules
    $moduleName = $moduleName ?? self::getName();

    return self::exists($moduleName) ? self::$moduleList[$moduleName] : null;
  }

  public static function hasProperty($propertyName, $moduleName = null)
  {
    // TODO add support for extended modules
    $moduleName = $moduleName ?? self::getName();

    return isset(self::$moduleList[$moduleName][$propertyName]);
  }

  public static function setProperty($data, $propertyName, $moduleName = null)
  {
    // TODO add support for extended modules
    $moduleName = $moduleName ?? self::getName();

    if (!self::exists($moduleName)) {
      return null;
    }

    self::$moduleList[$moduleName][$propertyName] = $data;

    // TODO change it in module's config.php

    return true;
  }

  public static function getProperty($propertyName, $moduleName = null)
  {
    // TODO add support for extended modules
    $moduleName = $moduleName ?? self::getName();

    return self::hasProperty($propertyName, $moduleName) ? self::$moduleList[$moduleName][$propertyName] : null;
  }

  protected static function loadFromFolder()
  {
    $modulePath = Path::file('module');
    $moduleFolders = is_dir($modulePath) ? scandir($modulePath) : [];

    $modules = [];
    foreach ($moduleFolders as $moduleName) {
      if (in_array($moduleName, ['.', '..'], true)) {
        continue;
      }

      $configPath = "$modulePath/$moduleName/config.php";

      if (!is_file($configPath)) {
        throw new \Exception(sprintf('%s module config is missed.', $moduleName));
      }

      $config = require $configPath;

      if (!is_array($config) || empty($config)) {
        throw new \Exception(sprintf('%s module config is empty.', $moduleName));
      }

      $isConfigValid = isset($config['priority']) && isset($config['version']) && isset($config['isInstalled']) && isset($config['isEnabled']);
      if (!$isConfigValid) {
        throw new \Exception(sprintf('%s module config is invalid.', $moduleName));
      }

      $config['name'] = $config['name'] ?? $moduleName;
      $config['languages'] = [];
      $config['routes'] = [];

      $modules[$config['name']] = $config;
    }

    uasort($modules, function ($module1, $module2) {
      if (isset($module1['priority']) && isset($module2['priority'])) {
        return $module2['priority'] <=> $module1['priority'];
      }
      return 0;
    });

    self::$moduleList = $modules;

    return true;
  }

  public static function loadHooks()
  {
    if (self::$isHooksLoaded) {
      return false;
    }

    self::$isHooksLoaded = true;

    $path = Path::file('module');

    $modules = self::$moduleList;

    uasort($modules, function ($module1, $module2) {
      if (isset($module1['priority']) && isset($module2['priority'])) {
        return $module1['priority'] <=> $module2['priority'];
      }
      return 0;
    });

    foreach ($modules as $module) {
      if (!$module['isEnabled']) {
        continue;
      }

      self::setName($module['name']);

      $hooks = "$path/{$module['name']}/hooks.php";

      if ($module['name'] === 'frontend') {
        $hooks = Path::file('theme') . '/hooks.php';
      }

      if (is_file($hooks)) {
        require $hooks;
      }
    }

    self::setName(null);

    return true;
  }






  // TODO
  public static function update($key, $value, $module = null)
  {
    // $name = $module ?? self::$name;
    // $config_file = Path::file('module') . '/' . $name . '/config.php';

    // if(!is_file($config_file)) {
    // 	return false;
    // }

    // if(is_numeric($value)) {
    // 	$value = $value;
    // } else if(is_string($value)) {
    // 	$value = "'$value'";
    // } else if(is_bool($value)) {
    // 	$value = $value ? 'true' : 'false';
    // } else if(is_null($value)) {
    // 	$value = 'null';
    // } else {
    // 	return false;
    // }

    // $config_content = file_get_contents($config_file);

    // $replacement = "'$key' => $value";

    // if(preg_match('/([\'"]' . $key . '[\'"][\s]*=>)/mi', $config_content)) {
    // 	$pattern = '/([\'"]' . $key . '[\'"][\s]*=>[\s]*[^,\]\n\/#]+)/mi';
    // } else {
    // 	$pattern = '/(return[\s]*(\[|array\())/mi';
    // 	$replacement = "$1\n\t" . $replacement . ",";
    // }

    // $config_content = preg_replace($pattern, $replacement, $config_content);

    // static $is_edited = false;

    // if(file_put_contents($config_file, $config_content, LOCK_EX)) {
    // 	if(!$is_edited) {
    // Log::write('Module: ' . $name. ' edited by user ID: ' . User::get()->id . ' from IP: ' . Request::ip(), 'module');
    // Hook::run('module.update', $name);
    // 	}

    // 	$is_edited = true;

    // 	return true;
    // }

    return false;
  }

  public static function delete($name)
  {
    // Log::write('Module: ' . $name. ' deleted by user ID: ' . User::get()->id . ' from IP: ' . Request::ip(), 'module');

    Hook::run('module.delete', $name);

    // return rmdir_recursive(Path::file('module') . '/' . $name);
  }

  public static function install($name)
  {
    // $path = Path::file('module') . '/' . $name . '/Install';

    // if(!file_exists($path)) {
    // 	return false;
    // }

    // // INSTALL SCRIPT
    // $path_install = $path . '/install.php';

    // if(is_file($path_install)) {
    // 	require $path_install;
    // }

    // Log::write('Module: ' . $name. ' installed by user ID: ' . User::get()->id . ' from IP: ' . Request::ip(), 'module');

    Hook::run('module.install', $name);

    return true;
  }

  public static function uninstall($name)
  {
    // $path = Path::file('module') . '/' . $name . '/Install';

    // if(!file_exists($path)) {
    // 	return false;
    // }

    // // UNINSTALL SCRIPT
    // $path_uninstall = $path . '/uninstall.php';

    // if(is_file($path_uninstall)) {
    // 	require $path_uninstall;
    // }

    // Log::write('Module: ' . $name. ' uninstalled by user ID: ' . User::get()->id . ' from IP: ' . Request::ip(), 'module');

    Hook::run('module.uninstall', $name);

    return true;
  }
}
