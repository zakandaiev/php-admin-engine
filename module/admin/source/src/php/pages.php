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

			<h2 class="section__title">
				<span>Pages</span>

				<div class="section__actions">
					<button class="btn btn_secondary">Add category</button>
					<button class="btn btn_primary">Add page</button>
				</div>
			</h2>
			<div class="box">
				<div class="box__header">
					<h4 class="box__title">Polar area chart</h4>
				</div>
				<div class="box__body">
					asd
				</div>
			</div>

		</div>
	</section>

	<?php Theme::block('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
