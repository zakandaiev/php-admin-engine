<?php

namespace Engine;

class Upload {
	private $files = [];
	private $folder;
	private $extensions = [];

	public $result = [];

	public function __construct($files, $custom_folder = '', $extensions = []) {
		$this->result = [
			'status' => false,
			'message' => __('engine.file.unknown_error'),
			'files' => []
		];
		
		$this->files = isset($files['tmp_name']) ? [$files] : $files;

		if(!is_array($this->files)) {
			return $this;
		}

		$custom_folder = trim($custom_folder ?? '', '/');
		if(!empty($custom_folder)) {
			$this->folder = UPLOAD['folder'] . '/' . $custom_folder;
		}
		else {
			$this->folder = UPLOAD['folder'];
		}

		$this->extensions = !empty($extensions) && is_array($extensions) ? $extensions : UPLOAD['extensions'];

		foreach($this->files as $file) {
			$this->file($file);
		}

		return $this;
	}

	public static function getMaxSize() {
		if(isset(UPLOAD['max_size']) && is_int(UPLOAD['max_size'])) {
			return UPLOAD['max_size'];
		}

		$amount = ini_get('upload_max_filesize');

		if(is_int($amount)) {
			return $amount;
		}

		$units = ['', 'K', 'M', 'G'];
		preg_match('/(\d+)\s?([KMG]?)/', ini_get('upload_max_filesize'), $matches);
		[$_, $nr, $unit] = $matches;
		$exp = array_search($unit, $units);

		return (int) $nr * pow(1024, $exp);
	}

	private function file($file) {
		if(empty($file)) {
			return $this;
		}

		$name = Hash::token(8);
		$name_original = $file['name'];

		$name_prepend = time();
		if(User::get()->authorized) {
			$name_prepend .= '_' . User::get()->id .'_';
		}
		else {
			$name_prepend .= '_uu_';
		}

		$size = $file['size'];
		$extension = strtolower(file_extension($file['name']));

		$path_file = $this->folder . '/' . $name_prepend . $name . '.' . $extension;
		$path_full = ROOT_DIR . '/' . $path_file;

		try {
			if(!file_exists(ROOT_DIR . '/' . $this->folder)) {
				mkdir(ROOT_DIR . '/' . $this->folder, 0755, true);
			}

			if(!in_array($extension, $this->extensions)) {
				$this->result['status'] = false;
				$this->result['message'] = __('engine.file.extension_x_is_forbidden', $extension);

				return $this;
			}

			if($size > self::getMaxSize()) {
				$this->result['status'] = false;
				$this->result['message'] = __('engine.file.size_of_x_is_too_large', $name_original);

				return $this;
			}

			move_uploaded_file($file['tmp_name'], $path_full);

			$this->result['status'] = true;
			$this->result['files'][] = $path_file;
		}
		catch(\Exception $e) {
			$this->result = [
				'status' => false,
				'message' => $e->getMessage()
			];

			return $this;
		}

		$user_id = @User::get()->id ?? 'unlogged';
		$user_ip = Request::$ip;
		Log::write(Path::url() . "/$path_file uploaded by user ID: $user_id from IP: $user_ip", 'upload');
		Hook::run('upload', $path_file);
	}
}
