<?php
	$title = Request::has('is_category') ? __('admin.page.add_category') : __('admin.page.add_page');

	Page::set('title', $title);

	Breadcrumb::add(__('admin.page.pages'), '/admin/page');
	Breadcrumb::add($title);
?>

<?php Theme::header(); ?>

<?php Theme::block('sidebar'); ?>

<main class="page-content__inner">

	<?php Theme::block('navbar-top'); ?>

	<section class="section section_grow section_offset">
		<div class="container-fluid">

			<?php Theme::breadcrumb() ?>

			<h2 class="section__title">
				<span><?= __('admin.page.pages') ?></span>

				<div class="section__actions">
					<button class="btn btn_secondary"><?= __('admin.page.add_category') ?></button>
					<button class="btn btn_primary"><?= __('admin.page.add_page') ?></button>
				</div>
			</h2>

			<form action="<?= Form::add('page/Page') ?>" data-redirect="<?= site('url_language') ?>/admin/page" data-validate>
				<div class="row gap-xs">

					<div class="col-xs-12 col-lg-8">

						<div class="tab">
							<nav class="tab__nav">
								<a href="#tab-page-content" class="tab__nav-item active"><?= __('admin.page.content') ?></a>
								<a href="#tab-page-seo" class="tab__nav-item"><?= __('admin.page.seo') ?></a>
							</nav>

							<div id="tab-page-content" class="tab__body active">



								<label>
									<input type="checkbox" name="test">
									<span>Option</span>
								</label>





								<label><?= __('admin.page.title') ?></label>
								<input name="title" type="text" placeholder="<?= __('admin.page.enter_title') ?>" autofocus>

								<label><?= __('admin.page.excerpt') ?></label>
								<textarea name="excerpt" placeholder="<?= __('admin.page.enter_excerpt') ?>" rows="1"></textarea>

								<label><?= __('admin.page.content') ?></label>
								<textarea name="content" data-wysiwyg placeholder="<?= __('admin.page.enter_content') ?>"></textarea>
							</div>

							<div id="tab-page-seo" class="tab__body">

							</div>
						</div>

					</div>

					<div class="col-xs-12 col-lg-4">

						<div class="row gap-xs">

							<div class="col-xs-12">
								<button type="submit" class="btn btn_fit btn_primary p-4"><?= __('admin.page.add_page') ?></button>
							</div>

							<div class="col-xs-12">
								<div class="box">
									<div class="box__header">
										<h4 class="box__title">Polar area chart</h4>
									</div>
									<div class="box__body">
										asd
									</div>
								</div>
							</div>

						</div>

					</div>

				</div>
			</form>

		</div>
	</section>

	<?php Theme::block('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
