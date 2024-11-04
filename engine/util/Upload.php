<?php

namespace engine\util;

use engine\Config;
use engine\auth\User;
use engine\util\File;
use engine\util\Hash;
use engine\util\Path;

class Upload
{
  protected $folder;
  protected $file = [];

  protected static $configFolder;
  protected static $configMaxSize;
  protected static $configExtensions;

  public function __construct($data, $folder = null)
  {
    $this->folder = $folder ? Path::resolve(self::getFolder(), $folder) : self::getFolder();
    $this->formatFile($data);

    return $this;
  }

  public function set($key, $value = null)
  {
    $this->file[$key] = $value;

    return true;
  }

  public function has($key)
  {
    return isset($this->file[$key]);
  }

  public function get($key = null)
  {
    return isset($key) ? @$this->file[$key] : $this->file;
  }

  public function setName($name)
  {
    if (empty($this->file)) {
      return false;
    }

    $oldName = $this->file['name'];
    $newName = $name . '.' . $this->file['extension'];

    $this->file['name'] = $newName;
    $this->file['path'] = str_replace($oldName, $newName, $this->file['path']);
    $this->file['pathFull'] = str_replace($oldName, $newName, $this->file['pathFull']);

    return true;
  }

  public function execute()
  {
    $this->uploadFiles();
    $this->logFiles();

    return $this;
  }

  public static function getFolder()
  {
    if (empty(self::$configFolder)) {
      self::$configFolder = Config::getProperty('folder', 'upload');
    }

    return self::$configFolder;
  }

  public static function getMaxSize()
  {
    if (empty(self::$configMaxSize) && Config::hasProperty('maxSize', 'upload')) {
      self::$configMaxSize = Config::getProperty('maxSize', 'upload');
    } else if (empty(self::$configMaxSize)) {
      $amount = ini_get('upload_max_filesize');

      if (is_int($amount)) {
        self::$configMaxSize = $amount;
      } else {
        $units = ['', 'K', 'M', 'G'];
        preg_match('/(\d+)\s?([KMG]?)/', ini_get('upload_max_filesize'), $matches);
        [$_, $nr, $unit] = $matches;
        $exp = array_search($unit, $units);

        self::$configMaxSize = intval($nr) * pow(1024, $exp);
      }
    }

    return self::$configMaxSize;
  }

  public static function getExtensions()
  {
    if (empty(self::$configExtensions)) {
      self::$configExtensions = Config::getProperty('extensions', 'upload');
    }

    return self::$configExtensions;
  }

  protected function formatFile($data)
  {
    if (empty($data)) {
      return false;
    }

    $extension = File::getExtension($data['name']);
    $name = time() . '-' . (User::get('isAuthorized') ? User::get('id') : 'uu') . '-' . Hash::token(4) . '.' . $extension;
    $path = Path::resolve($this->folder, $name);
    $pathFull = Path::resolve(ROOT_DIR, $path);


    $this->file = [
      'extension' => $extension,
      'name' => $name,
      'path' => $path,
      'pathFull' => $pathFull,
      'nameOriginal' => $data['name'],
      'size' => $data['size'],
      'tmp_name' => $data['tmp_name'],
      'status' => false,
      'message' => null,
    ];

    return true;
  }

  protected function uploadFiles()
  {
    if (empty($this->file)) {
      return false;
    }

    try {
      File::createDir(Path::resolve(ROOT_DIR, $this->folder));

      move_uploaded_file($this->file['tmp_name'], $this->file['pathFull']);
    } catch (\Exception $e) {
      $this->file['status'] = false;
      $this->file['message'] = $e->getMessage();

      return false;
    }

    $this->file['status'] = true;

    return true;
  }

  protected function logFiles()
  {
    // TODO
    // if (empty($this->result['files']) || !$this->result['status']) {
    //   return false;
    // }

    // $user_id = @User::get()->id ?? 'unlogged';
    // $user_ip = Request::ip();

    // foreach ($this->result['files'] as $file) {
    //   Log::write(Path::url() . "/$file uploaded by user ID: $user_id from IP: $user_ip", 'upload');
    //   Hook::run('upload', $file);
    // }

    return true;
  }
}
