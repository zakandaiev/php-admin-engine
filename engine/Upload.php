<?php

namespace Engine;

class Upload {
	protected $files = [];
	protected $folder;
	protected $extensions = [];
	protected $result = [];

	protected static $max_size;

	public function __construct($files, $custom_folder = '', $extensions = []) {
		$this->result = [
			'status' => true,
			'message' => null,
			'files' => [],
			'count' => 0
		];

		$custom_folder = trim($custom_folder ?? '', '/');
		$this->folder = !empty($custom_folder) ? UPLOAD['folder'] . '/' . $custom_folder : UPLOAD['folder'];

		$this->extensions = is_array($extensions) && !empty($extensions) ? $extensions : UPLOAD['extensions'];

		$this->formatFiles($files);

		return $this;
	}

	public function get($key = null) {
		return isset($key) ? @$this->result[$key] : $this->result;
	}

	public function execute() {
		$this->uploadFiles();
		$this->logFiles();

		return $this;
	}

	public static function getMaxSize() {
		if(!empty(self::$max_size)) {
			return self::$max_size;
		}

		if(isset(UPLOAD['max_size']) && is_int(UPLOAD['max_size'])) {
			self::$max_size = UPLOAD['max_size'];

			return self::$max_size;
		}

		$amount = ini_get('upload_max_filesize');
		if(is_int($amount)) {
			self::$max_size = $amount;

			return $amount;
		}

		$units = ['', 'K', 'M', 'G'];
		preg_match('/(\d+)\s?([KMG]?)/', ini_get('upload_max_filesize'), $matches);
		[$_, $nr, $unit] = $matches;
		$exp = array_search($unit, $units);

		self::$max_size = intval($nr) * pow(1024, $exp);

		return self::$max_size;
	}

	protected function formatFiles($files) {
		$files = !is_array($files) ? [$files] : $files;

		if(empty($files)) {
			return false;
		}

		foreach($files as $file) {
			if(empty($file) || @$file['error'] || !isset($file['tmp_name']) || !isset($file['name']) || !isset($file['size'])) {
				continue;
			}

			$file['name_original'] = $file['name'];
			$file['name'] = time() . '_' . (User::get()->authorized ? User::get()->id : 'uu') . '_' . Hash::token(8);
			$file['extension'] = strtolower(file_extension($file['name_original']));
			$file['path_destination'] = $this->folder . '/' . $file['name'] . '.' . $file['extension'];
			$file['path_destination_full'] = ROOT_DIR . '/' . $file['path_destination'];

			if(!in_array($file['extension'], $this->extensions)) {
				$this->result['status'] = false;
				$this->result['message'] = __('engine.file.extension_x_is_forbidden', $file['extension']);

				break;
			}

			if($file['size'] > ($file['max_size'] ?? self::getMaxSize())) {
				$this->result['status'] = false;
				$this->result['message'] = __('engine.file.size_of_x_is_too_large', $file['name_original']);

				break;
			}

			$this->files[] = $file;
			$this->result['files'][] = $file['path_destination'];
		}

		return true;
	}

	protected function uploadFiles() {
		if(empty($this->files) || !$this->result['status']) {
			return false;
		}

		try {
			if(!file_exists(ROOT_DIR . '/' . $this->folder)) {
				mkdir(ROOT_DIR . '/' . $this->folder, 0755, true);
			}
		}
		catch(\Exception $e) {
			$this->result['status'] = false;
			$this->result['message'] = DEBUG['is_enabled'] ? $e->getMessage() : __('engine.file.unknown_error');

			return false;
		}

		foreach($this->files as $file) {
			try {
				move_uploaded_file($file['tmp_name'], $file['path_destination_full']);

				$this->result['count'] = $this->result['count'] + 1;
			}
			catch(\Exception $e) {
				$this->result['status'] = false;
				$this->result['message'] = DEBUG['is_enabled'] ? $e->getMessage() : __('engine.file.unknown_error');

				break;
			}
		}

		return true;
	}

	protected function logFiles() {
		if(empty($this->result['files']) || !$this->result['status']) {
			return false;
		}

		$user_id = @User::get()->id ?? 'unlogged';
		$user_ip = Request::ip();

		foreach($this->result['files'] as $file) {
			Log::write(Path::url() . "/$file uploaded by user ID: $user_id from IP: $user_ip", 'upload');
			Hook::run('upload', $file);
		}

		return true;
	}
}
