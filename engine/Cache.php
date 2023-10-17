<?php

namespace Engine;

class Cache {
	const CACHE_KEY = [
		'data' => 'data',
		'expires' => 'expires'
	];

	public static function set($key, $data = null, $lifetime = null) {
		$path = Path::file('cache');

		if(!file_exists($path)) {
			mkdir($path, 0755, true);
		}

		$key = md5($key);

		$path = $path . '/' . $key . '.' . trim(CACHE['extension'], '.');

		$content[self::CACHE_KEY['data']] = $data;
		$content[self::CACHE_KEY['expires']] = time() + intval($lifetime ?? LIFETIME['cache']);

		$content = serialize($content);

		try {
			file_put_contents($path, $content);
		}
		catch(\Exception $error) {
			throw new \Exception(sprintf('Cache error: %s.', $error->getMessage()));
		}

		$user_id = @User::get()->id ?? 'unlogged';
		$user_ip = Request::ip();
		Log::write("Cache key: $key created by user ID: $user_id from IP: $user_ip", 'cache');

		return true;
	}

	public static function get($key) {
		$path = Path::file('cache') . '/' . md5($key) . '.' . trim(CACHE['extension'], '.');

		if(is_file($path)) {
			$content = unserialize(file_get_contents($path));

			if(time() <= $content[self::CACHE_KEY['expires']]) {
				return $content[self::CACHE_KEY['data']];
			}
		}

		return false;
	}

	public static function delete($key) {
		$key = md5($key);

		$path = Path::file('cache') . '/' . $key . '.' . trim(CACHE['extension'], '.');

		if(!is_file($path)) {
			return false;
		}

		unlink($path);

		$user_id = @User::get()->id ?? 'unlogged';
		$user_ip = Request::ip();
		Log::write("Cache key: $key deleted by user ID: $user_id from IP: $user_ip", 'cache');

		return true;
	}

	public static function flush() {
		$path = Path::file('cache');

		if(!file_exists($path)) {
			return false;
		}

		foreach(scandir($path) as $file) {
			if(in_array($file, ['.', '..'], true)) continue;

			if(file_extension($file) !== trim(CACHE['extension'], '.')) continue;

			unlink($path . '/' . $file);
		}

		$user_id = @User::get()->id ?? 'unlogged';
		$user_ip = Request::ip();
		Log::write("Cache: flushed by user ID: $user_id from IP: $user_ip", 'cache');

		Hook::run('cache.flush');

		return true;
	}
}
