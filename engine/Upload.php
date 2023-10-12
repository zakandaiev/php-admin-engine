<?php

namespace Engine;

class Upload {
	private $files = [];
	private $files_to_upload = [];
	private $folder;
	private $extensions = [];

	public $result = [];

	public function __construct($files, $custom_folder = '', $extensions = []) {
		$this->result = [
			'status' => true,
			'message' => null,
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

		$this->extensions = is_array($extensions) && !empty($extensions) ? $extensions : UPLOAD['extensions'];

		$this->initFiles();
		$this->uploadFiles();
		$this->logFiles();

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

	private function initFiles() {
		if(empty($this->files)) {
			return false;
		}

		foreach($this->files as $file) {
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
	
			if($file['size'] > self::getMaxSize()) {
				$this->result['status'] = false;
				$this->result['message'] = __('engine.file.size_of_x_is_too_large', $file['name_original']);
	
				break;
			}
	
			$this->files_to_upload[] = $file;
		}

		return true;
	}

	private function uploadFiles() {
		if(empty($this->files_to_upload) || !$this->result['status']) {
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

		foreach($this->files_to_upload as $file) {
			try {
				move_uploaded_file($file['tmp_name'], $file['path_destination_full']);

				$this->result['files'][] = $file['path_destination'];
			}
			catch(\Exception $e) {
				$this->result['status'] = false;
				$this->result['message'] = DEBUG['is_enabled'] ? $e->getMessage() : __('engine.file.unknown_error');
	
				break;
			}
		}

		return true;
	}

	private function logFiles() {
		if(empty($this->result['files']) || !$this->result['status']) {
			return false;
		}

		$user_id = @User::get()->id ?? 'unlogged';
		$user_ip = Request::$ip;

		foreach($this->result['files'] as $file) {
			Log::write(Path::url() . "/$file uploaded by user ID: $user_id from IP: $user_ip", 'upload');
			Hook::run('upload', $file);
		}

		return true;
	}
}
