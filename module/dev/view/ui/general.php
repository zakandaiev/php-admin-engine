<?php Theme::header(); ?>

<section class="section section_offset">
	<div class="container">
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

<?php Theme::footer(); ?>
