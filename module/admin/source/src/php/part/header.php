<!DOCTYPE html>
<html lang="TODO">

<head>
	<?= Meta::all($page) ?>
	<?= Asset::render('css') ?>
	<?= Asset::render('js') ?>
</head>

<body>
	<script src="<?= Asset::url() ?>/js/data-theme.js"></script>

	<header id="header" class="header section_offset">
		<div class="container">
			<h1>header</h1>
		</div>
	</header>

	<main class="page-content">