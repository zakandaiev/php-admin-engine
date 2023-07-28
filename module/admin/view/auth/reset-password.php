<?php Theme::header(); ?>

<main class="page-content__inner">

	<section class="section section_grow section_offset">
		<div class="container h-100 d-flex flex-column justify-content-center">

			<div class="row">
				<div class="col-xs-12 col-sm-10 col-md-6 col-lg-4 m-x-auto">

					<div class="text-center m-b-2">
						<?php if(!empty(site('logo_admin'))): ?>
							<img class="d-inline-block w-25 m-b-2" src="<?= site('url') ?>/<?= site('logo_admin') ?>" alt="Logo">
						<?php else: ?>
							<h1 class="font-size-6 m-b-2"><?= site('name') ?></h1>
						<?php endif; ?>

						<h4 class="color-text"><?= __('admin.auth.cta.reset_password') ?></h4>

						<?php Theme::widget('lang'); ?>
					</div>

					<div class="box">
						<div class="box__body">

							<form data-validate>
								<label><?= __('admin.auth.password') ?></label>
								<input type="password" placeholder="<?= __('admin.auth.enter_password') ?>" required>

								<button class="btn btn_fit btn_primary" type="submit"><?= __('admin.auth.submit') ?></button>
							</form>

						</div>
					</div>

					<div class="text-center m-t-4">
						<a href="<?= site('url_language') ?>/admin/login"><?= __('admin.auth.login') ?></a>
					</div>

				</div>
			</div>

		</div>
	</section>

</main>

<?php Theme::footer(); ?>
