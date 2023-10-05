<?php

define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT']);

if(!is_file(ROOT_DIR . '/config.php')) {
	file_put_contents(ROOT_DIR . '/config.php', '');
}

require_once ROOT_DIR . '/vendor/autoload.php';

use Engine\Engine;

$is_php_version_unsuppurted = version_compare($version = phpversion(), $required = Engine::PHP_MIN, '<');

if($is_php_version_unsuppurted) {
	$error_message = '<div style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;">';
	$error_message .= '<h1>' . Engine::NAME . '</h1>';
	$error_message .= '<p>You are running <b>PHP ' . $version . '</b> version, but it needs at least <b>PHP ' . $required . '</b> version.</p>';
	$error_message .= '</div>';
	exit($error_message);
}

$is_install = (strtok($_SERVER['REQUEST_URI'], '?') === '/install');

if(!defined('DATABASE') && !$is_install) {
	header('Location: /install', true, 307);
	exit;
}

if(defined('DATABASE') && $is_install) {
	header('Location: /', true, 307);
	exit;
}

if($is_install) {
	require_once ROOT_DIR . '/module/install/engine/install.php';
	exit;
}

try {
	Engine::start();
}
catch (\ErrorException $error) {
	echo $error->getMessage();
}

Engine::stop();
