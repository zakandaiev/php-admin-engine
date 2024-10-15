<?php

use engine\Config;
use engine\Engine;
use engine\http\Request;
use engine\i18n\I18n;
use engine\module\Module;
use engine\module\Setting;
use engine\theme\Asset;
use engine\util\Date;
use engine\util\Debug;
use engine\util\File;
use engine\util\Hash;
use engine\util\Path;
use engine\util\Text;

############################# PHP POLYFILL #############################
if (!function_exists('str_contains')) {
  function str_contains(string $haystack, string $needle): bool
  {
    return '' === $needle || false !== strpos($haystack, $needle);
  }
}

if (!function_exists('str_starts_with')) {
  function str_starts_with($haystack, $needle)
  {
    $length = strlen($needle ?? '');

    return substr($haystack, 0, $length) === $needle;
  }
}

############################# DATE #############################
function dateFormat($date = null, $format = 'd.m.Y')
{
  return Date::format($date, $format);
}

function dateWhen($date = null, $format = 'd.m.Y')
{
  return Date::when($date, $format);
}

function dateLeft($date)
{
  return Date::left($date);
}

############################# DEBUG #############################
function debug(...$data)
{
  return Debug::dd(...$data);
}

function debugTrace($level = null)
{
  return Debug::trace($level);
}

############################# FILE #############################
function getFileDirectory($path)
{
  return File::getDirectory($path);
}

function getFileName($path)
{
  return File::getName($path);
}

function getFileBaseName($path)
{
  return File::getBaseName($path);
}

function getFileExtension($path)
{
  return File::getExtension($path);
}

function getFileSize($path, $precision = 2)
{
  return File::getSize($path);
}

function createDir($directory, $permissions = 0755, $recursive = true)
{
  return File::createDir($directory, $permissions, $recursive);
}

function createFile($path, $content = PHP_EOL, $flags = 0)
{
  return File::createFile($path, $content, $flags);
}

function globRecursive($pattern, $flags = 0)
{
  return File::globRecursive($pattern, $flags);
}

function rmdirRecursive($path)
{
  return File::rmdirRecursive($path);
}

############################# HASH #############################
function generateToken($length = 16)
{
  return Hash::token($length);
}

function generatePassword($password)
{
  return Hash::token($password);
}

function passwordVerify($password, $hash)
{
  return Hash::passwordVerify($password, $hash);
}

############################# PATH #############################
function getPathClass($className = '', $module = null)
{
  return Path::class($className, $module);
}

function getPathFile($section = '', $module = null)
{
  return Path::class($section, $module);
}

function getPathUrl($section = '', $module = null)
{
  return Path::url($section, $module);
}

function resolvePath(...$args)
{
  return Path::resolve(...$args);
}

function resolveUrl(...$args)
{
  return Path::resolveUrl(...$args);
}

############################# TEXT #############################
function html($text = '')
{
  return Text::html($text);
}

function url($url = '')
{
  return Text::url($url);
}

function tel($tel = '')
{
  return Text::tel($tel);
}

function cyrToLat($text = '')
{
  return Text::cyrToLat($text);
}

function slug($text = '', $delimiter = '-')
{
  return Text::slug($text, $delimiter);
}

function word($text = '')
{
  return Text::word($text);
}

function excerpt($text = '', $maxchar = 100, $end = "...")
{
  return Text::excerpt($text, $maxchar, $end);
}

function pluralValue($number, $values = [])
{
  return Text::pluralValue($number, $values);
}

############################# LANGUAGE #############################
function t($key, $data = null)
{
  return I18n::translate($key, $data);
}

function lang($key = null, $language = null, $mixed = null)
{
  $value = I18n::getProperty('key', $language);
  $language = $language ?? I18n::getCurrent();

  switch ($key) {
    case 'region': {
        $value = I18n::getProperty('region', $language);
        break;
      }
    case 'locale': {
        $value = I18n::getProperty('key', $language);
        $region = I18n::getProperty('region', $language);
        if (!empty($region)) {
          $value .= ($mixed ?? '-') . $region;
        }
        break;
      }
    case 'icon': {
        $value = Path::resolveUrl('img', 'flag', $language . '.' . ($mixed ?? 'png'));
        break;
      }
    case 'icon_url': {
        $value = Path::resolveUrl(Asset::url(), 'img', 'flag', $language . '.' . ($mixed ?? 'png'));
        break;
      }
  }

  return $value;
}

############################# ENGINE & SITE DATA #############################
function getEngineProperty($key)
{
  if ($key === 'php_min') {
    return Engine::PHP_MIN;
  } else if ($key === 'name') {
    return Engine::NAME;
  } else if ($key === 'version') {
    return Engine::VERSION;
  } else if ($key === 'author') {
    return Engine::AUTHOR;
  } else if ($key === 'author_url') {
    return Engine::AUTHOR_URL;
  } else if ($key === 'repository_url') {
    return Engine::REPOSITORY_URL;
  } else if ($key === 'website_url') {
    return Engine::WEBSITE_URL;
  }

  return null;
}

function isModuleExists($moduleName)
{
  return Module::exists($moduleName);
}

function getModuleList()
{
  return Module::list();
}

function getModuleConfig($moduleName = null)
{
  return Module::get($moduleName);
}

function hasModuleProperty($propertyName, $moduleName = null)
{
  return Module::hasProperty($propertyName, $moduleName);
}

function getModuleProperty($propertyName, $moduleName = null)
{
  return Module::getProperty($propertyName, $moduleName);
}

function site($key, $module = null)
{
  $moduleExtends = Module::getProperty('extends');
  $value = null;

  if (Setting::hasProperty($key)) {
    $value = Setting::getProperty($key);
  } else if (Setting::hasProperty($key, $moduleExtends)) {
    $value = Setting::getProperty($key, $moduleExtends);
  } else if (Setting::hasProperty($key, 'engine')) {
    $value = Setting::getProperty($key, 'engine');
  }

  if (isset($module)) {
    $value = Setting::getProperty($key, $module);
  }

  if (is_object($value) && property_exists($value, site('language_current'))) {
    $value = @$value->{site('language_current')};
  } else if (is_object($value) && property_exists($value, site('language'))) {
    $value = @$value->{site('language')};
  }

  switch ($key) {
    case 'charset': {
        $value = preg_replace('/([a-z]+)-?/i', '$1-', Config::getProperty('charset', 'database') ?? '');
        $value = rtrim($value, '-');
        break;
      }
    case 'languages': {
        $value = I18n::list();
        break;
      }
    case 'language_current': {
        $value = I18n::getCurrent();
        break;
      }
    case 'url': {
        $value = Request::base();
        break;
      }
    case 'permalink': {
        $value = Request::url();
        break;
      }
    case 'permalink_full': {
        $value = Request::urlFull();
        break;
      }
    case 'uri': {
        $value = Request::uri();
        break;
      }
    case 'uri_full': {
        $value = Request::uriFull();
        break;
      }
    case 'uri_no_language': {
        $uri = Request::uri();
        $uriParts = Request::uriParts();
        $language = $uriParts[0];

        if (I18n::has($language)) {
          array_shift($uriParts);
          $uri = Path::resolveUrl(...$uriParts);
        }

        $value = '/' . $uri;
        break;
      }
    case 'uri_full_no_language': {
        $query = http_build_query(Request::get());
        $query = !empty($query) ? "?$query" : '';

        $value = site('uri_no_language') . $query;
        break;
      }
    case 'version': {
        $value = getModuleProperty('version') ?? getEngineProperty('version');
        break;
      }
  }

  return $value;
}

############################# MISC #############################
function isJson($string)
{
  if (!is_string($string)) {
    return false;
  }

  $decodeResult = @json_decode($string);

  return is_object($decodeResult) || is_array($decodeResult);
}

function isClosure($i)
{
  return $i instanceof \Closure;
}

function isNumInRange($number, $min, $max)
{
  if ($number >= $min && $number < $max) {
    return true;
  }

  return false;
}

function stringToNumber($string)
{
  if (is_numeric($string)) {
    return strpos($string, '.') !== false ? (float)$string : (int)$string;
  }

  return null;
}

function getLinkFilter($key, $value = 1)
{
  $query = Request::get();

  $query[$key] = $value;

  $query = http_build_query($query);
  $query = !empty($query) ? "?$query" : '';

  return site('permalink') . $query;
}

function getLinkUnfilter($key)
{
  $query = Request::get();

  unset($query[$key]);

  $query = http_build_query($query);
  $query = !empty($query) ? "?$query" : '';

  return site('permalink') . $query;
}

function getLinkSort($key)
{
  $query = Request::get();

  foreach ($query as $k => $v) {
    if ($v === 'asc' || $v === 'desc') {
      unset($query[$k]);
    }
  }

  $value = Request::get($key) === 'asc' ? 'desc' : (Request::get($key) === 'desc' ? '' : 'asc');

  if (!empty($value)) {
    $query[$key] = $value;
  }

  $query = http_build_query($query);
  $query = !empty($query) ? "?$query" : '';

  return site('permalink') . $query;
}
