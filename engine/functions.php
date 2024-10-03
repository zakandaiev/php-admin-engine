<?php

use engine\Config;
use engine\Engine;
use engine\http\Request;
use engine\i18n\I18n;
use engine\module\Module;
use engine\module\Setting;
use engine\theme\Asset;

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

############################# DEBUG #############################
function debug(...$data)
{
  foreach ($data as $key => $item) {
    if ($key === 0) {
      echo '<hr>';
    }

    echo '<pre style="
      display: block;
      width: 100%;
      overflow: auto;
      margin: 0;
      padding: 1em;
      background: #1b1b1b;
      color: #fff;
      font-size: 1em;
      font-family: SFMono-Regular, Consolas, Liberation Mono, Menlo, monospace;
      font-weight: 400;
      line-height: 1.4;
      border-radius: 0.5em;
    ">';

    var_dump($item);

    echo '</pre>';

    if (isset($data[$key + 1])) {
      echo '<br>';
    } else {
      echo '<hr>';
    }
  }
}

function debugTrace($level = null)
{
  debug($level ? (isset(debug_backtrace()[$level]) ? debug_backtrace()[$level] : debug_backtrace()) : debug_backtrace());
}

############################# FILE #############################
function getFileDirectory($path)
{
  return pathinfo($path, PATHINFO_DIRNAME);
}

function getFileName($path)
{
  return pathinfo($path, PATHINFO_FILENAME);
}

function getFileBaseName($path)
{
  return pathinfo($path, PATHINFO_BASENAME);
}

function getFileExtension($path)
{
  return pathinfo($path, PATHINFO_EXTENSION);
}

function getFileSize($path, $precision = 2)
{
  $size = 0;

  if (is_file($path)) {
    $size = filesize($path);
  } else {
    foreach (globRecursive($path . '/*.*') as $file) {
      $size += filesize($file);
    }
  }

  $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
  for ($i = 0; $size > 1024; $i++) $size /= 1024;

  return round($size, 2) . ' ' . $units[$i];
}

function createDirIfNotExist($directory, $permissions = 0755, $recursive = true)
{
  if (!file_exists($directory)) {
    return mkdir($directory, $permissions, $recursive);
  }

  return false;
}

function createFile($path, $content = PHP_EOL, $flags = 0)
{
  $directory = getFileDirectory($path);

  createDirIfNotExist($directory);

  return file_put_contents($path, $content, $flags);
}

function globRecursive($pattern, $flags = 0)
{
  $files = glob($pattern, $flags);

  foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
    $files = array_merge($files, globRecursive($dir . '/' . basename($pattern), $flags));
  }

  return $files;
}

function rmdirRecursive($path)
{
  if (is_dir($path)) {
    $files = array_diff(scandir($path), array('.', '..'));

    foreach ($files as $file) {
      rmdirRecursive(realpath($path) . '/' . $file);
    }

    return rmdir($path);
  } else if (is_file($path)) {
    return unlink($path);
  }

  return false;
}

############################# TEXT #############################
function html($text = '')
{
  return htmlspecialchars($text ?? '');
}

function url($url = '')
{
  return urlencode($url ?? '');
}

function tel($tel = '')
{
  return preg_replace('/[^\d+]+/m', '', $tel ?? '');
}

function cyrToLat($text)
{
  $replacement = [
    'а' => 'a',
    'б' => 'b',
    'в' => 'v',
    'г' => 'g',
    'д' => 'd',
    'е' => 'e',
    'ё' => 'e',
    'ж' => 'zh',
    'з' => 'z',
    'и' => 'i',
    'й' => 'y',
    'к' => 'k',
    'л' => 'l',
    'м' => 'm',
    'н' => 'n',
    'о' => 'o',
    'п' => 'p',
    'р' => 'r',
    'с' => 's',
    'т' => 't',
    'у' => 'u',
    'ф' => 'f',
    'х' => 'kh',
    'ц' => 'tz',
    'ч' => 'ch',
    'ш' => 'sh',
    'щ' => 'sch',
    'ы' => 'y',
    'э' => 'e',
    'ю' => 'iu',
    'я' => 'ia',
    'А' => 'A',
    'Б' => 'B',
    'В' => 'V',
    'Г' => 'G',
    'Д' => 'D',
    'Е' => 'E',
    'Ё' => 'E',
    'Ж' => 'Zh',
    'З' => 'Z',
    'И' => 'I',
    'Й' => 'Y',
    'К' => 'K',
    'Л' => 'L',
    'М' => 'M',
    'Н' => 'N',
    'О' => 'O',
    'П' => 'P',
    'Р' => 'R',
    'С' => 'S',
    'Т' => 'T',
    'У' => 'U',
    'Ф' => 'F',
    'Х' => 'Kh',
    'Ц' => 'Tz',
    'Ч' => 'Ch',
    'Ш' => 'Sh',
    'Щ' => 'Sch',
    'Ы' => 'Y',
    'Э' => 'E',
    'Ю' => 'Iu',
    'Я' => 'Ia',
    'ь' => '',
    'Ь' => '',
    'ъ' => '',
    'Ъ' => '',
    'ї' => 'yi',
    'і' => 'i',
    'ґ' => 'g',
    'є' => 'e',
    'Ї' => 'Yi',
    'І' => 'I',
    'Ґ' => 'G',
    'Є' => 'E'
  ];

  return strtr($text, $replacement);
}

function slug($text, $delimiter = '-')
{
  $slug = cyrToLat($text);
  $slug = preg_replace('/[^A-Za-z0-9' . $delimiter . ' ]+/', '', $slug);
  $slug = trim($slug);
  $slug = preg_replace('/\s+/', $delimiter, $slug);
  $slug = strtolower($slug ?? '');

  return $slug;
}

function word($text)
{
  $word = preg_replace('/[^\p{L}\d ]+/iu', '', $text);
  $word = preg_replace('/\s+/', ' ', $word);
  $word = trim($word);

  return $word;
}

function excerpt($text, $maxchar, $end = "...")
{
  if (strlen($text ?? '') > $maxchar) {
    $words = preg_split('/\s/', $text);
    $output = '';
    $i = 0;
    while (1) {
      $length = strlen($output) + strlen($words[$i]);
      if ($length > $maxchar) {
        break;
      } else {
        $output .= ' ' . $words[$i];
        ++$i;
      }
    }
    $output .= $end;
  } else {
    $output = $text;
  }
  return $output;
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

############################# DATE #############################
function formatDate($date = null, $format = 'd.m.Y')
{
  $timestamp = $date ?? time();
  $timestamp = is_numeric($timestamp) ? $timestamp : strtotime($timestamp);
  return date($format, $timestamp);
}

function dateWhen($date = null, $format = 'd.m.Y')
{
  $timestamp = $date ?? time();
  $timestamp = is_numeric($date) ? $date : strtotime($date ?? time());

  $date_day = date('d.m.Y', $timestamp);
  $today = date('d.m.Y');
  $yesterday = date('d.m.Y', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')));

  if ($date_day === $today) {
    $date = t('engine.date.today_at', date('H:i', $timestamp));
  } else if ($yesterday === $date_day) {
    $date = t('engine.date.yesterday_at', date('H:i', $timestamp));
  } else {
    $date = formatDate($timestamp, $format);
  }

  return $date;
}

function dateLeft($date)
{
  $now = time();
  $then = is_numeric($date) ? $date : strtotime($date ?? time());

  if ($then - $now < 0) {
    return t('engine.date.left.expired');
  }

  $difference = abs($then - $now);
  $left = [];

  $month = floor($difference / 2592000);
  if (0 < $month) {
    $left['month'] = t('engine.date.left.month', $month);
  }

  $days = floor($difference / 86400) % 30;
  if (0 < $days) {
    $left['days'] = t('engine.date.left.days', $days);
  }

  $hours = floor($difference / 3600) % 24;
  if (0 < $hours) {
    $left['hours'] = t('engine.date.left.hours', $hours);
  }

  $minutes = floor($difference / 60) % 60;
  if (0 < $minutes) {
    $left['minutes'] = t('engine.date.left.minutes', $minutes);
  }

  if (0 < count($left)) {
    $datediff = implode(' ', $left);
    return $datediff;
  }

  return t('engine.date.left.few_seconds');
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
    case 'permalink': {
        $value = Request::url();
        break;
      }
    case 'uri': {
        $value = Request::uri();
        break;
      }
    case 'url': {
        $value = Request::base();
        break;
      }
    case 'url_language': {
        $value = Request::base() . '/' . site('language_current');
        break;
      }
    case 'uri_no_language': {
        $uri = Request::uri();
        $uri_parts = Request::uriParts();
        $language = $uri_parts[0];

        if (I18n::has($language)) {
          array_shift($uri_parts);
          $uri = '/' . implode('/', $uri_parts);
        }

        $value = $uri;
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

  $query = !empty($query) ? '?' . $query : '';

  return site('permalink') . $query;
}

function getLinkUnfilter($key)
{
  $query = Request::get();

  unset($query[$key]);

  $query = http_build_query($query);

  $query = !empty($query) ? '?' . $query : '';

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

  $query = !empty($query) ? '?' . $query : '';

  return site('permalink') . $query;
}

function getNumericalNounForm($number)
{
  // return 'n' - nominative (комментарий)
  // return 's' - singular (комментария)
  // return 'p' - plural (комментариев)

  $number = intval($number);
  $number_ratio = ($number % 100) / 10;

  if ($number > 10 && ($number_ratio >= 1 && $number_ratio <= 2)) {
    return 'p';
  }

  switch ($number % 10) {
    case 1:
      return 'n';
    case 2:
    case 3:
    case 4:
      return 's';
  }

  return 'p';
}
