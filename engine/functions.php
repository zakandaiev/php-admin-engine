<?php

############################# PHP POLYFILL #############################
if(!function_exists('str_contains')) {
	function str_contains(string $haystack, string $needle):bool {
		return '' === $needle || false !== strpos($haystack, $needle);
	}
}

if(!function_exists('str_starts_with')) {
	function str_starts_with($haystack, $needle) {
		$length = strlen($needle ?? '');
		return substr($haystack, 0, $length) === $needle;
	}
}

if(!function_exists('is_json')) {
	function is_json($string) {
		if(!is_string($string)) return false;
		return is_object(@json_decode($string));
	}
}

############################# DEBUG #############################
function debug(...$data) {
	foreach($data as $key => $item) {
		if($key === 0) echo '<hr>';

		echo '<pre>';

		var_dump($item);

		echo '</pre>';

		if(isset($data[$key + 1])) {
			echo '<br>';
		}
		else {
			echo '<hr>';
		}
	}
}

function debug_trace($level = null) {
	debug($level ? (isset(debug_backtrace()[$level]) ? debug_backtrace()[$level] : debug_backtrace()) : debug_backtrace());
}

############################# FILE #############################
function file_name($path) {
	return pathinfo($path, PATHINFO_FILENAME);
}

function file_extension($path) {
	return pathinfo($path, PATHINFO_EXTENSION);
}

function file_size($path, $precision = 2) {
	$size = 0;

	if(is_file($path)) {
		$size = filesize($path);
	}
	else {
		foreach(glob_recursive($path . '/*.*') as $file) {
			$size += filesize($file);
		}
	}

	$units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
	for($i = 0; $size > 1024; $i++) $size /= 1024;

	return round($size, 2) . ' ' . $units[$i];
}

function glob_recursive($pattern, $flags = 0) {
	$files = glob($pattern, $flags);

	foreach(glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
		$files = array_merge($files, glob_recursive($dir . '/' . basename($pattern), $flags));
	}

	return $files;
}

function rmdir_recursive($path) {
	if(is_dir($path)) {
		$files = array_diff(scandir($path), array('.', '..'));

		foreach($files as $file) {
			rmdir_recursive(realpath($path) . '/' . $file);
		}

		return rmdir($path);
	}
	else if(is_file($path)) {
		return unlink($path);
	}

	return false;
}

############################# IMAGE #############################
function svg($file, $is_asset = true, $module = null) {
	$dir = $is_asset ? (Path::file('asset', $module) . '/img') : ROOT_DIR;
	$file_name = str_ireplace('.svg', '', trim($file ?? '', '/'));
	$path_to_svg = "$dir/$file_name.svg";

	if(!is_file($path_to_svg) && Module::get('extends')) {
		return svg($file, $is_asset, Module::get('extends'));
	}

	if(!is_file($path_to_svg)) {
		return "<!-- SVG not found: $path_to_svg -->";
	}
	else {
		return file_get_contents($path_to_svg);
	}
}

function images($json, $attributes = '') {
	$output = '';
	$images = json_decode($json) ?? [];

	foreach($images as $image) {
		$output .= '<img src="' . Request::$base . '/' . $image . '" ' . $attributes . '>';
	}

	return $output;
}

function placeholder_image($path) {
	if(is_file(ROOT_DIR . '/' . $path)) {
		return $path;
	}

	return site(__FUNCTION__);
}

function placeholder_avatar($path) {
	if(is_file(ROOT_DIR . '/' . $path)) {
		return $path;
	}

	return site(__FUNCTION__);
}

############################# DATE #############################
function format_date($date = null, $format = 'd.m.Y') {
	$timestamp = $date ?? time();
	$timestamp = is_numeric($timestamp) ? $timestamp : strtotime($timestamp);
	return date($format, $timestamp);
}

function date_when($date = null, $format = 'd.m.Y') {
	$timestamp = $date ?? time();
	$timestamp = is_numeric($date) ? $date : strtotime($date ?? time());

	$date_day = date('d.m.Y', $timestamp);
	$today = date('d.m.Y');
	$yesterday = date('d.m.Y', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')));

	if($date_day === $today) {
		$date = __('engine.date.today_at', date('H:i', $timestamp));
	}
	if($yesterday === $date_day) {
		$date = __('engine.date.yesterday_at', date('H:i', $timestamp));
	}
	else {
		$date = format_date($timestamp, $format);
	}

	return $date;
}

function date_left($date) {
	$now = time();
	$then = is_numeric($date) ? $date : strtotime($date ?? time());

	if($then - $now < 0) {
		return __('engine.date.left.expired');
	}

	$difference = abs($then - $now);
	$left = [];

	$month = floor($difference / 2592000);
	if(0 < $month) {
		$left['month'] = __('engine.date.left.month', $month);
	}

	$days = floor($difference / 86400) % 30;
	if(0 < $days) {
		$left['days'] = __('engine.date.left.days', $days);
	}

	$hours = floor($difference / 3600) % 24;
	if(0 < $hours) {
		$left['hours'] = __('engine.date.left.hours', $hours);
	}

	$minutes = floor($difference / 60) % 60;
	if(0 < $minutes) {
		$left['minutes'] = __('engine.date.left.minutes', $minutes);
	}

	if(0 < count($left)) {
		$datediff = implode(' ', $left);
		return $datediff;
	}

	return __('engine.date.left.few_seconds');
}

############################# TEXT #############################
function html($text = ''){
	return htmlspecialchars($text ?? '');
}

function url($url = '') {
	return urlencode($url ?? '');
}

function tel($tel = '') {
	return preg_replace('/[^\d+]+/m', '', $tel ?? '');
}

function cyr_to_lat($text) {
	$gost = [
		'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
		'е' => 'e', 'ё' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
		'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
		'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
		'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'tz', 'ч' => 'ch',
		'ш' => 'sh', 'щ' => 'sch', 'ы' => 'y', 'э' => 'e', 'ю' => 'iu',
		'я' => 'ia',
		'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D',
		'Е' => 'E', 'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I',
		'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N',
		'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
		'У' => 'U', 'Ф' => 'F', 'Х' => 'Kh', 'Ц' => 'Tz', 'Ч' => 'Ch',
		'Ш' => 'Sh', 'Щ' => 'Sch', 'Ы' => 'Y', 'Э' => 'E', 'Ю' => 'Iu',
		'Я' => 'Ia',
		'ь' => '', 'Ь' => '', 'ъ' => '', 'Ъ' => '',
		'ї' => 'yi',
		'і' => 'i',
		'ґ' => 'g',
		'є' => 'e',
		'Ї' => 'Yi',
		'І' => 'I',
		'Ґ' => 'G',
		'Є' => 'E'
	];

	return strtr($text, $gost);
}

function slug($text, $delimiter = '-') {
	$slug = cyr_to_lat($text);
	$slug = preg_replace('/[^A-Za-z0-9' . $delimiter . ']+/', $delimiter, $slug);
	$slug = preg_replace('/[' . $delimiter . ']+/', $delimiter, $slug);
	$slug = trim($slug ?? '', $delimiter);
	$slug = strtolower($slug ?? '');

	return $slug;
}

function word($text) {
	$word = preg_replace('/[^\p{L}\d ]+/iu', '', $text);
	$word = preg_replace('/[\s]+/', ' ', $word);
	$word = trim($word ?? '');

	return $word;
}

function excerpt($text, $maxchar, $end = "...") {
	if(strlen($text ?? '') > $maxchar) {
		$words = preg_split('/\s/', $text);
		$output = '';
		$i = 0;
		while(1) {
			$length = strlen($output)+strlen($words[$i]);
			if($length > $maxchar) {
				break;
			}
			else {
				$output .= ' ' . $words[$i];
				++$i;
			}
		}
		$output .= $end;
	}
	else {
		$output = $text;
	}
	return $output;
}

############################# LANGUAGE #############################
function __($key, $data = null) {
	return Language::translate($key, $data);
}

function lang($key, $language = null, $mixed = null) {
	$value = '';
	$language = $language ?? Language::current();

	switch($key) {
		case 'key':
		case 'name': {
			$value = Language::get('key', $language);
			break;
		}
		case 'region': {
			$value = Language::get('region', $language);
			break;
		}
		case 'locale': {
			$value = Language::get('key', $language);
			$region = Language::get('region', $language);
			if(!empty($region)) {
				$value .= ($mixed ?? '-') . $region;
			}
			break;
		}
		case 'icon': {
			$value = 'img/flag/' . $language . '.' . ($mixed ?? 'png');
			break;
		}
	}

	return $value;
}

############################# SITE #############################
function site($key, $module = 'engine') {
	$value = @Setting::get($module)->$key;

	if($value === 'true') {
		$value = true;
	}
	if($value === 'false') {
		$value = false;
	}

	switch($key) {
		case 'name':
		case 'description':
		case 'address':
		case 'hours': {
			$value = isset($value->{site('language_current')}) ? $value->{site('language_current')} : $value->{site('language')};
			break;
		}
		case 'charset': {
			$value = preg_replace('/([a-z]+)-?/i', '$1-', DATABASE['charset'] ?? '');
			$value = rtrim($value, '-');
			break;
		}
		case 'language_current': {
			$value = Language::current();
			break;
		}
		case 'language_current_region': {
			$value = Language::get('region');
			break;
		}
		case 'url': {
			$value = Request::$base;
			break;
		}
		case 'url_language': {
			$value = Request::$base . '/' . site('language_current');

			break;
		}
		case 'uri': {
			$value = Request::$uri;

			break;
		}
		case 'uri_no_language': {
			$uri = Request::$uri;
			$uri_parts = Request::$uri_parts;
			$language = $uri_parts[0];

			if(Language::has($language)) {
				array_shift($uri_parts);
				$uri = '/' . implode('/', $uri_parts);
			}

			$value = $uri;

			break;
		}
		case 'permalink': {
			$value = Request::$url;
			break;
		}
		case 'languages': {
			$value = Language::get();
			break;
		}
		case 'version': {
			$value = Module::get('version') ?? Engine::VERSION;
			break;
		}
		case 'favicon':
		case 'logo':
		case 'logo_alt':
		case 'placeholder_image':
		case 'placeholder_avatar': {
			$main_modules = ['admin', 'public'];

			$module = in_array(Module::get('name'), $main_modules) ? Module::get('name') : Module::get('extends');
			$module = in_array($module, $main_modules) ? $module : 'public';

			$value = @Setting::get('engine')->{"{$key}_{$module}"};

			break;
		}
	}

	if(is_object($value)) {
		$array_value = (array)$value;
		$intersects = array_intersect_key($array_value, Language::get());

		if(empty($intersects)) {
			return $value;
		}

		return @$array_value[site('language')];
	}

	return $value;
}

############################# HELPERS #############################
function is_closure($i) {
	return $i instanceof \Closure;
}

function link_filter($key, $value = 1) {
	$query = Request::$get;

	$query[$key] = $value;

	$query = http_build_query($query);

	$query = !empty($query) ? '?' . $query : '';

	return site('permalink') . $query;
}

function link_unfilter($key) {
	$query = Request::$get;

	unset($query[$key]);

	$query = http_build_query($query);

	$query = !empty($query) ? '?' . $query : '';

	return site('permalink') . $query;
}

function link_sort($key) {
	$query = Request::$get;

	foreach($query as $k => $v) {
		if($v === 'asc' || $v === 'desc') {
			unset($query[$k]);
		}
	}

	$value = Request::get($key) === 'asc' ? 'desc' : (Request::get($key) === 'desc' ? '' : 'asc');

	if(!empty($value)) {
		$query[$key] = $value;
	}

	$query = http_build_query($query);

	$query = !empty($query) ? '?' . $query : '';

	return site('permalink') . $query;
}

function numerical_noun_form($number) {
	// return 'n' - nominative (комментарий)
	// return 's' - singular (комментария)
	// return 'p' - plural (комментариев)

	$number = intval($number);
	$number_ratio = ($number % 100) / 10;

	if($number > 10 && ($number_ratio >= 1 && $number_ratio <= 2)) {
		return 'p';
	}

	switch($number % 10) {
		case 1: return 'n';
		case 2: case 3: case 4: return 's';
	}

	return 'p';
}

function is_num_in_range($number, $min, $max) {
	if($number >= $min && $number < $max) {
		return true;
	}

	return false;
}
