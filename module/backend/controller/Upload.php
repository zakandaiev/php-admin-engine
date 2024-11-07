<?php

namespace module\backend\controller;

use module\backend\controller\Backend;
use engine\http\Request;
use engine\http\Response;
use engine\i18n\I18n;
use engine\util\File;
use engine\util\Upload as UploadImage;

class Upload extends Backend
{
  public function getSection()
  {
    $section = $this->route['parameter']['section'];
    $method = 'upload' . ucfirst($section);

    if (!method_exists($this, $method)) {
      Response::answer('error', 'Not found', null, 404);
      return false;
    }

    $this->$method();
  }

  protected function uploadWysiwyg()
  {
    $file = Request::files('image');
    if (empty($file) || empty($file['tmp_name'])) {
      Response::answer('error', I18n::translate('form.unknown_error'), null, 400);
      return false;
    }

    $fileExtension = strtolower(File::getExtension($file['name']));
    if (!in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
      Response::answer('error', I18n::translate('upload.error.extension', $fileExtension), null, 400);
      return false;
    }

    $uploadClass = new UploadImage($file);
    $uploadPath = $uploadClass->get('path');
    $uploadClass->execute();

    Response::answer('success', null, $uploadPath);
  }
}
