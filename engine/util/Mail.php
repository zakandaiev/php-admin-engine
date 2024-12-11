<?php

namespace engine\util;

use engine\Engine;
use engine\database\Query;
use engine\module\Setting;
use engine\i18n\I18n;
use engine\http\Request;
use engine\util\Log;
use engine\util\Path;

class Mail
{
  protected $fileName;
  protected $from;
  protected $to;
  protected $subject;
  protected $message;
  protected $isForced;

  public function __construct($fileName, $data = [], $options = [])
  {
    $mailData = $this->load($fileName, $data);
    if (!$mailData) {
      return $this;
    }

    $this->fileName = $fileName;
    $this->from = $options['from'] ?? $mailData['from'] ?? Setting::getProperty('email', 'contact');
    $this->to = $options['to'] ?? $mailData['to'] ?? null;
    $this->subject = $options['subject'] ?? $mailData['subject'] ?? null;
    $this->message = $options['message'] ?? $mailData['message'] ?? null;
    $this->isForced = $options['isForced'] ?? false;
  }

  public function send()
  {
    if (empty($this->fileName) || empty($this->from) || empty($this->to) || empty($this->subject) || empty($this->message)) {
      return false;
    }

    // TODO
    // if (!$this->isForced) {
    //   $sql = 'SELECT * FROM {user} WHERE email = :email ORDER BY date_created DESC LIMIT 1';
    //   $user = new Query($sql);
    //   $user = $user->execute(['email' => $recepient])->fetch();
    //   $user = User::format($user);
    //   if (!$user || @$user->setting->notifications->{'mail_' . $mail['type']} === false) {
    //     return false;
    //   }
    // }

    $currentLanguage = I18n::getCurrent();
    $siteLanguage = Setting::getProperty('language', 'engine');

    $siteName = Setting::getProperty('name', 'engine') ?? Engine::NAME;
    if (is_object($siteName) && property_exists($siteName, $currentLanguage)) {
      $siteName = $siteName->$currentLanguage;
    } else if (is_object($siteName) && property_exists($siteName, $siteLanguage)) {
      $siteName = $siteName->$siteLanguage;
    }
    $headers = [
      'Content-type' => 'text/html',
      'charset' => 'utf-8',
      'MIME-Version' => '1.0',
      'From' => $siteName . '<' . $this->from . '>',
      'Reply-To' => $this->from
    ];

    Log::write("{$this->fileName} to {$this->to}", 'mail');
    Hook::run('mail.send', $this);

    return mail($this->to, $this->subject, $this->message, $headers);
  }

  protected static function load($fileName, $data = [])
  {
    $filePath = Path::resolve(Path::file('mail'), $fileName . '.php');
    if (!is_file($filePath)) {
      return [];
    }

    extract($data);

    ob_start();
    ob_implicit_flush(0);

    try {
      $content = require $filePath;
    } catch (\Exception $e) {
      ob_end_clean();
      throw $e;
    }

    echo ob_get_clean();

    if (!is_array($content)) {
      return [];
    }

    return $content;
  }
}
