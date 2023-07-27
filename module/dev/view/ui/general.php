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

			<h2 class="section__title">General</h2>

			<div class="row fill gap-xs cols-xs-1 cols-md-2">
				<div class="col">
					<div class="row fill gap-xs cols-xs-1">

						<div class="col">
							<?php Theme::block('../ui/part/accordion'); ?>
						</div>

						<div class="col">
							<?php Theme::block('../ui/part/tooltip'); ?>
						</div>

						<div class="col">
							<?php Theme::block('../ui/part/popover'); ?>
						</div>

						<div class="col">
							<?php Theme::block('../ui/part/loader'); ?>
						</div>

					</div>
				</div>

				<div class="col">
					<div class="row fill gap-xs cols-xs-1">

					<div class="col">
						<?php Theme::block('../ui/part/breadcrumb'); ?>
					</div>

					<div class="col">
						<?php Theme::block('../ui/part/label'); ?>
					</div>

					<div class="col">
						<?php Theme::block('../ui/part/dropdown'); ?>
					</div>

					<div class="col">
						<?php Theme::block('../ui/part/pagination'); ?>
					</div>

				</div>

			</div>

		</div>
	</section>

	<?php Theme::block('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
