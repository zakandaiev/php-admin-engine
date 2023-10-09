<?php Theme::header(); ?>

<main class="page-content__inner">

	<section class="section section_grow section_offset">
		<div class="container h-100 d-flex flex-column justify-content-center">

			<div class="row">
				<div class="col-xs-12 col-sm-10 col-md-6 col-lg-4 mx-auto">

					<div class="text-center mb-2">
						<?php if(!empty(site('logo'))): ?>
							<img class="d-inline-block w-25 mb-2" src="<?= site('url') ?>/<?= site('logo') ?>" data-src-dark="<?= site('url') ?>/<?= site('logo_alt') ?>" alt="Logo">
						<?php else: ?>
							<h1 class="font-size-6 mb-2"><?= site('name') ?></h1>
						<?php endif; ?>

						<h4 class="color-text"><?= __('admin.auth.cta.login') ?></h4>

						<?php Theme::widget('/lang'); ?>
					</div>

					<div class="box">
						<div class="box__body">

							<div class="d-flex flex-column gap-2 mb-3">
								<a class="btn btn_lg btn_auth btn_google" href="#">
									<?= svg('brand/google') ?>
									<span><?= __('admin.auth.login_with_google') ?></span>
								</a>
								<a class="btn btn_lg btn_auth btn_facebook" href="#">
									<?= svg('brand/facebook') ?>
									<span><?= __('admin.auth.login_with_facebook') ?></span>
								</a>
								<a class="btn btn_lg btn_auth btn_apple" href="#">
									<?= svg('brand/apple') ?>
									<span><?= __('admin.auth.login_with_apple') ?></span>
								</a>
							</div>

							<div class="row gap-xs">
								<div class="col fill">
									<hr>
								</div>
								<div class="col text-uppercase d-flex align-items-center"><?= __('admin.auth.or') ?></div>
								<div class="col fill">
									<hr>
								</div>
							</div>

							<form data-validate>
								<label><?= __('admin.auth.email') ?></label>
								<input type="email" placeholder="<?= __('admin.auth.enter_email') ?>" required>

								<label><?= __('admin.auth.password') ?></label>
								<input type="password" placeholder="<?= __('admin.auth.enter_password') ?>" required>

								<button class="btn btn_fit btn_primary" type="submit"><?= __('admin.auth.submit') ?></button>
							</form>

						</div>
					</div>

					<div class="text-center mt-4">
						<a href="<?= site('url_language') ?>/admin/reset-password"><?= __('admin.auth.reset_password') ?></a>
					</div>

				</div>
			</div>

		</div>
	</section>

</main>

<?php Theme::footer(); ?>
