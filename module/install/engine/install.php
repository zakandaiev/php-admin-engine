<?php

define('LOG', [
	'folder' => 'log',
	'file_mask' => 'Y-m-d',
	'extension' => 'log'
]);
define('COOKIE_KEY', ['auth' => 'auth_token']);
define('LIFETIME', ['auth' => 60 * 60 * 24 * 7]);

use \Engine\Engine;
use \Engine\Hash;
use \Engine\Log;
use \Engine\Session;

Session::initialize();

storePostData();

$step = 'db';
$step_next = 'site';

if(isset($_GET['step']) && $_GET['step'] == 'site') {
	$step = 'site';
	$step_next = 'auth';
}
if(isset($_GET['step']) && $_GET['step'] == 'auth') {
	$step = 'auth';
	$step_next = 'demo';
}
if(isset($_GET['step']) && $_GET['step'] == 'demo') {
	$step = 'demo';
	$step_next = 'install';
}
if(isset($_GET['step']) && $_GET['step'] == 'install') {
	$step = 'install';
}

if($step == 'install' && install()) {
	Session::flush();
	header('Location: /admin/dashboard', true, 307);
}

function tableExists($connection, $table) {
	try {
		$result = $connection->query("SELECT 1 FROM $table LIMIT 1");
	}
	catch(Exception $e) {
		return false;
	}
	return $result !== false;
}

function install() {
	$data = Session::get();

	// FOLDERS
	if(!file_exists(ROOT_DIR . '/theme')) {
		mkdir(ROOT_DIR . '/theme', 0755, true);
	}
	if(!file_exists(ROOT_DIR . '/upload')) {
		mkdir(ROOT_DIR . '/upload', 0755, true);
	}

	installConfig($data);
	installSEO($data);

	// INSTALL PROCESS
	$dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $data['db_host'], $data['db_name'], $data['db_charset']);

	$options = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $data['db_charset']
	];

	$connection = new PDO($dsn, $data['db_user'], $data['db_pass'], $options);

	$install_file = file_get_contents(ROOT_DIR . '/module/install/engine/engine.sql');
	executeSQL($data, $connection, $install_file);

	// DEMO DATA
	if(isset($data['demo_data']) && $data['demo_data'] === 'on') {
		$demo_file = file_get_contents(ROOT_DIR . '/module/install/engine/demo/demo.sql');
		executeSQL($data, $connection, $demo_file);

		if(file_exists(ROOT_DIR . '/upload/demo')) {
			unlink(ROOT_DIR . '/upload/demo');
		}
		else {
			mkdir(ROOT_DIR . '/upload/demo', 0755, true);
		}

		foreach(glob_recursive(ROOT_DIR . '/module/install/engine/demo/theme/*.*') as $file) {
			$dest_folder = str_replace('/module/install/engine/demo', '', $file);
			$dest_folder = str_replace('/' . file_name($file) . '.' . file_extension($file), '', $dest_folder);

			if(!file_exists($dest_folder)) {
				mkdir($dest_folder, 0755, true);
			}

			copy($file, $dest_folder . '/' . file_name($file) . '.' . file_extension($file));
		}

		foreach(glob(ROOT_DIR . '/module/install/engine/demo/upload/*.*') as $file) {
			copy($file, ROOT_DIR . '/upload/demo/' . file_name($file) . '.' . file_extension($file));
		}
	}

	Log::write('Engine has been installed successfully');

	return true;
}

function executeSQL($data, $connection, $sql) {
	$replace_from = [
		'%prefix%',
		'%site_name%', '%contact_email%',
		'%admin_login%', '%admin_password%', '%admin_email%',
		'%auth_token%', '%auth_ip%'
	];
	$replace_to = [
		$data['db_prefix'],
		$data['site_name'], $data['contact_email'],
		$data['admin_login'], Hash::password($data['admin_password']), $data['admin_email'],
		generateAuthToken(), $_SERVER['REMOTE_ADDR']
	];

	$sql_formatted = str_replace($replace_from, $replace_to, $sql);

	$connection->query($sql_formatted);
}

function storePostData() {
	foreach($_POST as $key => $value) {
		${$key} = $value;
		Session::set($key, $value);
	}
}

function generateAuthToken() {
	$auth_key = COOKIE_KEY['auth'];
	$auth_token = Session::hasCookie($auth_key) ? Session::getCookie($auth_key) : Hash::token();

	Session::setCookie($auth_key, $auth_token);

	return $auth_token;
}

function installConfig($data) {
	$path = ROOT_DIR . '/config.php';

	// CONFIG START
	$config  = "<?php" . PHP_EOL . PHP_EOL;

	// TIME ZONE
	$config  .= "define('TIMEZONE', '{$data['timezone']}');" . PHP_EOL;
	$config  .= "define('TIMEZONE_OFFSET', (new DateTime('now', new DateTimeZone(TIMEZONE)))->format('P'));" . PHP_EOL . PHP_EOL;
	$config  .= "date_default_timezone_set(TIMEZONE);" . PHP_EOL . PHP_EOL;

	// DATABASE
	$config .= "define('DATABASE', [" . PHP_EOL;
	$config .= "\t'host' => '{$data['db_host']}'," . PHP_EOL;
	$config .= "\t'name' => '{$data['db_name']}'," . PHP_EOL;
	$config .= "\t'username' => '{$data['db_user']}'," . PHP_EOL;
	$config .= "\t'password' => '{$data['db_pass']}'," . PHP_EOL;
	$config .= "\t'charset' => '{$data['db_charset']}'," . PHP_EOL;
	$config .= "\t'prefix' => '{$data['db_prefix']}'," . PHP_EOL;
	$config .= "\t'options' => [" . PHP_EOL;
	$config .= "\t\t\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION," . PHP_EOL;
	$config .= "\t\t\PDO::MYSQL_ATTR_INIT_COMMAND => \"SET NAMES {$data['db_charset']}, time_zone='\" . TIMEZONE_OFFSET . \"'\"" . PHP_EOL;
	$config .= "\t]" . PHP_EOL;
	$config .= "]);" . PHP_EOL;
	$config .= PHP_EOL;

	// DEBUG
	$config .= "define('DEBUG', [" . PHP_EOL;
	$config .= "\t'is_enabled' => true," . PHP_EOL;
	$config .= "\t'lang_wrap' => '_'" . PHP_EOL;
	$config .= "]);" . PHP_EOL;
	$config .= PHP_EOL;

	// CACHE
	$config .= "define('CACHE', [" . PHP_EOL;
	$config .= "\t'folder' => 'cache'," . PHP_EOL;
	$config .= "\t'extension' => 'bin'" . PHP_EOL;
	$config .= "]);" . PHP_EOL;
	$config .= PHP_EOL;

	// LOG
	$config .= "define('LOG', [" . PHP_EOL;
	$config .= "\t'folder' => 'log'," . PHP_EOL;
	$config .= "\t'file_mask' => 'Y-m-d'," . PHP_EOL;
	$config .= "\t'extension' => 'log'" . PHP_EOL;
	$config .= "]);" . PHP_EOL;
	$config .= PHP_EOL;

	// COOKIE_KEY
	$config .= "define('COOKIE_KEY', [" . PHP_EOL;
	$config .= "\t'auth' => 'auth_token'," . PHP_EOL;
	$config .= "\t'csrf' => 'csrf_token'," . PHP_EOL;
	$config .= "\t'language' => 'language'" . PHP_EOL;
	$config .= "]);" . PHP_EOL;
	$config .= PHP_EOL;

	// LIFETIME
	$config .= "define('LIFETIME', [" . PHP_EOL;
	$config .= "\t'auth' => 60 * 60 * 24 * 7, // 7 days" . PHP_EOL;
	$config .= "\t'cache' => 60 * 60 * 1, // 1 hour" . PHP_EOL;
	$config .= "\t'form' => 60 * 60 * 12 // 12 hours" . PHP_EOL;
	$config .= "]);" . PHP_EOL;
	$config .= PHP_EOL;

	// AUTH
	$config .= "define('AUTH', [" . PHP_EOL;
	$config .= "\t'bind_session_to_ip' => true" . PHP_EOL;
	$config .= "]);" . PHP_EOL;
	$config .= PHP_EOL;

	// PAGINATION
	$config .= "define('PAGINATION', [" . PHP_EOL;
	$config .= "\t'uri_key' => 'page'" . PHP_EOL;
	$config .= "]);" . PHP_EOL;
	$config .= PHP_EOL;

	// SERVICE
	$config .= "define('SERVICE', [" . PHP_EOL;
	$config .= "\t'ip_checker' => 'https://check-host.net/ip-info?host=%s'" . PHP_EOL;
	$config .= "]);" . PHP_EOL;
	$config .= PHP_EOL;

	// UPLOAD
	$config .= "define('UPLOAD', [" . PHP_EOL;
	$config .= "\t'folder' => 'upload'," . PHP_EOL;
	$config .= "\t'max_size' => 1024 * 1024 * 10, // 10MB" . PHP_EOL;
	$config .= "\t'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'pdf', 'txt', 'zip', 'rar']" . PHP_EOL;
	$config .= "]);" . PHP_EOL;
	$config .= PHP_EOL;

	// MISC
	$config  .= 'define(\'IS_SHARED_HOSTING\', false);' . PHP_EOL;

	// CONFIG END
	file_put_contents($path, $config, LOCK_EX);

	return true;
}

function installSEO($data) {
	$site_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'];

	// ROBOTS
	$robots_txt = 'User-agent: *' . PHP_EOL;
	$robots_txt .= 'Disallow: /404' . PHP_EOL;
	$robots_txt .= 'Disallow: /admin' . PHP_EOL;
	$robots_txt .= 'Disallow: /admin/' . PHP_EOL . PHP_EOL;
	$robots_txt .= 'Sitemap: ' . $site_url . '/sitemap.xml';
	file_put_contents(ROOT_DIR . '/robots.txt', $robots_txt, LOCK_EX);

	// SITEMAP
	$sitemap_xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
	$sitemap_xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . PHP_EOL;
	$sitemap_xml .= '<url><loc>' . $site_url . '</loc><lastmod>' . date('c') . '</lastmod><priority>1.00</priority></url>' . PHP_EOL;
	$sitemap_xml .= '</urlset>';
	file_put_contents(ROOT_DIR . '/sitemap.xml', $sitemap_xml, LOCK_EX);

	return true;
}

?>

<!DOCTYPE html>
<html lang="en-US">

<head>
	<meta charset="utf-8">
	<meta name="author" content="<?= Engine::AUTHOR ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

	<title>Instalation page — <?= Engine::NAME ?></title>

	<link rel="icon" type="image/x-icon" sizes="any" href="/module/admin/view/asset/favicon.ico">
	<link rel="icon" type="image/png" href="/module/admin/view/asset/favicon.png">
	<link rel="icon" type="image/svg+xml" href="/module/admin/view/asset/favicon.svg">
	<link rel="apple-touch-icon" href="/module/admin/view/asset/favicon.png">

	<link rel="stylesheet" href="/module/admin/view/asset/css/main.css?version=<?= Engine::VERSION ?>">
</head>

<body>
	<script src="/module/admin/view/asset/js/data-theme.js"></script>

	<div class="page-content">
		<main class="page-content__inner">
			<section class="section section_grow section_offset">
				<div class="container h-100 d-flex flex-column justify-content-center">
					<form action="/install?step=<?= $step_next ?>" method="POST" class="row">
						<div class="col-xs-12 col-sm-10 col-md-6 col-lg-4 mx-auto">

							<div class="text-center mb-4">
								<h1 class="font-size-6 mb-2"><?= Engine::NAME ?></h1>
								<h4 class="color-text">Instalation page</h4>
							</div>

							<div class="box">
								<div class="box__header">
									<h4 class="box__title">
										<?php if($step == 'site'): ?>
											Setup site
										<?php elseif($step == 'auth'): ?>
											Setup administrator's account
										<?php elseif($step == 'demo'): ?>
											Demo data
										<?php else: ?>
											Setup database
										<?php endif; ?>
									</h4>
								</div>

								<div class="box__body">
									<?php if($step == 'auth'): ?>

										<label>Login</label>
										<input type="text" name="admin_login" placeholder="Login" required minlength="2" maxlength="100" autofocus>

										<label>Password</label>
										<input type="text" name="admin_password" placeholder="Password" required minlength="8" maxlength="200">

										<label>Email</label>
										<input type="email" name="admin_email" placeholder="Email" required minlength="6" maxlength="200">

									<?php elseif($step == 'site'): ?>

										<?php
											$dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $_POST['db_host'], $_POST['db_name'], $_POST['db_charset']);

											$options = [
												PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
												PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $_POST['db_charset']
											];

											try {
												$connection = new PDO($dsn, $_POST['db_user'], $_POST['db_pass'], $options);
											} catch(PDOException $e) {
												echo "<p>Error! Reason: {$e->getMessage()}</p>";
												echo '<a href="/install" class="btn btn_fit btn_primary mt-2">Try again</a>';
												exit;
											}

											if(tableExists($connection, $_POST['db_prefix'] . '_setting') == 1) {
												echo '<p>Error! Remove all tables from DB <b>' . $_POST['db_name'] . '</b> and try again.</p>';
												echo '<a href="/install" class="btn btn_fit btn_primary mt-2">Try again</a>';
												exit;
											}
										?>

										<label>Site name</label>
										<input type="text" name="site_name" placeholder="Site name" required autofocus>

										<label>Contact email</label>
										<input type="email" name="contact_email" value="admin@<?=$_SERVER['HTTP_HOST']?>" placeholder="Contact email" required>

									<?php elseif($step == 'demo'): ?>

										<label class="switch">
											<input type="checkbox" name="demo_data">
											<span class="switch__slider"></span>
											<span>Fill up with demo data</span>
										</label>

									<?php else: ?>

										<label>Timezone</label>
										<input type="text" name="timezone" value="Europe/Kiev" placeholder="Timezone" required>

										<label>Host</label>
										<input type="text" name="db_host" value="localhost" placeholder="Host" required>

										<label>User</label>
										<input type="text" name="db_user" placeholder="User" required autofocus>

										<label>Password</label>
										<input type="text" name="db_pass" placeholder="Password" required>

										<label>Name</label>
										<input type="text" name="db_name" placeholder="Name" required>

										<label>Charset</label>
										<input type="text" name="db_charset" value="utf8" placeholder="Charset" required>

										<label>Table prefix</label>
										<input type="text" name="db_prefix" pattern="[a-z]+" value="pae" placeholder="Table prefix" required>

									<?php endif; ?>
								</div>

								<div class="box__footer">
									<button type="submit" class="btn btn_fit btn_primary">
										<?php if($step == 'demo'): ?>
											Install
										<?php else: ?>
											Next step
										<?php endif; ?>
									</button>
								</div>
							</div>

						</div>
					</form>
				</div>
			</section>
		</main>
	</div>
</body>

</html>
