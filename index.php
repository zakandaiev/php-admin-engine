<?php

date_default_timezone_set('UTC');

define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT']);

require_once ROOT_DIR . '/vendor/autoload.php';

use engine\Engine;

$isPhpVersionUnsuppurted = version_compare($currentVersion = phpversion(), $requiredVersion = Engine::PHP_MIN, '<');

if ($isPhpVersionUnsuppurted) {
  $errorMessage = "
    <div style=\"position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);width:100%;text-align:center;\">
      <h1>" . Engine::NAME . "</h1>

      <p>
        Your server is running <b>PHP $currentVersion</b> version,
        the system requires at least <b>PHP $requiredVersion</b>.
      </p>
    </div>
  ";

  exit($errorMessage);
}

try {
  Engine::start();
} catch (\ErrorException $error) {
  echo $error->getMessage();
}

Engine::stop();
