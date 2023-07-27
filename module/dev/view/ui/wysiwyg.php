<?php Theme::header(); ?>

<?php Theme::block('sidebar'); ?>

<main class="page-content__inner">

	<?php Theme::block('navbar-top'); ?>

	<section class="section section_grow section_offset">
		<div class="container-fluid">

			<nav class="breadcrumb">
				<span class="breadcrumb__item"><a href="/">Home</a></span>
				<span class="breadcrumb__item">Dev UI</span>
			</nav>

			<h2 class="section__title">Editor</h2>

			<div class="box">
				<div class="box__header">
					<h4 class="box__title">Wysiwyg</h4>
				</div>
				<div class="box__body">
					<textarea data-wysiwyg placeholder="Type something"></textarea>
				</div>
			</div>

		</div>
	</section>

	<?php Theme::block('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
