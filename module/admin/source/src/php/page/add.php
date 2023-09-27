<?php
	use \Module\Admin\Controller\FormBuilder;

	$title = Request::has('is_category') ? __('admin.page.add_category') : __('admin.page.add_page');

	Page::set('title', $title);

	Breadcrumb::add(__('admin.page.pages'), '/admin/page');
	Breadcrumb::add($title);

	$form_builder = new FormBuilder(['page/Page', 'page/Translation']);
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

				<span class="section__actions">
					<button class="btn btn_secondary"><?= __('admin.page.add_category') ?></button>
					<button class="btn btn_primary"><?= __('admin.page.add_page') ?></button>
				</span>
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

								<div class="row">
									<?= $form_builder->renderCol('title'); ?>
									<?= $form_builder->renderCol('excerpt'); ?>
									<?= $form_builder->renderCol('tags', array_map(function($tag) {
										$t = new \stdClass();
										$t->value = $tag->id;
										$t->name = $tag->name;
										return $t;
									}, $tags)); ?>
									<?= $form_builder->renderCol('content'); ?>
								</div>

								<label><?php if(isset($content['label'])): ?><?= $content['label'] ?><?php else: ?><?= __('admin.page.content') ?><?php endif; ?></label>
								<textarea
									name="content"
									data-wysiwyg
									<?php if(isset($content['required'])): ?>required<?php endif; ?>
									<?php if(isset($content['placeholder'])): ?>placeholder="<?= $content['placeholder'] ?>"<?php endif; ?>
								>
								</textarea>
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
