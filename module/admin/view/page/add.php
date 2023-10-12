<?php

$title = Request::has('is-category') ? __('admin.page.add_category') : __('admin.page.add_page');

Page::set('title', $title);

Page::breadcrumb('add', ['name' => __('admin.page.pages'), 'url' => '/admin/page']);
Page::breadcrumb('add', ['name' => $title]);

$form_builder = new FormBuilder('page/page');
$form_attributes = 'data-redirect="' . site('url_language') . '/admin/page" data-validate';

// debug($tags);

$form_builder->setFieldValue('author', array_map(function($author) {
	$a = new \stdClass();

	$a->value = $author->id;
	$a->name = $author->fullname;
	$a->selected = $author->id == User::get()->id ? true : false;

	return $a;
}, $authors));

$form_builder->setFieldValue('category', array_map(function($category) {
	$c = new \stdClass();

	$c->value = $category->id;
	$c->name = $category->title;
	$c->selected = false;

	return $c;
}, $categories));

$form_builder->setFieldValue('template', array_map(function($template) {
	$t = new \stdClass();

	$t->value = $template;
	$t->name = ucfirst($template);
	$t->selected = false;

	return $t;
}, $templates));
?>

<?php Theme::header(); ?>

<?php Theme::block('sidebar'); ?>

<main class="page-content__inner">

	<?php Theme::block('navbar-top'); ?>

	<section class="section section_grow section_offset">
		<div class="container-fluid">

			<?php Theme::breadcrumb(); ?>

			<h2 class="section__title">
				<span><?= $title ?></span>
			</h2>

			<form action="<?= Form::add('page/page') ?>" data-redirect="<?= site('url_language') ?>/admin/page" data-validate>
				<div class="row gap-xs">
					<div class="col-xs-12 col-md-8">
						<div class="tab">

							<nav class="tab__nav">
								<a href="#tab-page-content" class="tab__nav-item active"><?= __('admin.page.content') ?></a>
								<a href="#tab-page-seo" class="tab__nav-item"><?= __('admin.page.seo') ?></a>
								<a href="#tab-page-fields" class="tab__nav-item"><?= __('admin.page.custom_fields') ?></a>
							</nav>

							<div id="tab-page-content" class="tab__body active">
								<div class="row gap-xs">
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
							</div>

							<div id="tab-page-seo" class="tab__body">
								<div class="row gap-xs">
									<?= $form_builder->renderCol('seo_description'); ?>
									<?= $form_builder->renderCol('seo_keywords'); ?>
									<?= $form_builder->renderCol('seo_image'); ?>
									<?= $form_builder->renderCol('no_index_no_follow'); ?>
								</div>
							</div>

							<div id="tab-page-fields" class="tab__body">
								
							</div>

						</div>
					</div>
					<div class="col-xs-12 col-md-4">

						<div class="box">
							<button type="submit" class="btn btn_fit btn_primary py-4"><?= __('admin.page.add_page') ?></button>
						</div>

						<div class="box">
							<div class="box__header">
								<h4 class="box__title"><?= __('admin.page.featured_image') ?></h4>
							</div>
							<div class="box__body">
								<div class="row gap-xs">
									<?= $form_builder->renderCol('image'); ?>
								</div>
							</div>
						</div>

						<div class="box">
							<div class="box__header">
								<h4 class="box__title"><?= __('admin.page.settings') ?></h4>
							</div>
							<div class="box__body">
								<div class="row gap-xs">
									<?= $form_builder->renderCol('author'); ?>
									<?= $form_builder->renderCol('category'); ?>
									<?= $form_builder->renderCol('url'); ?>
									<?= $form_builder->renderCol('template'); ?>
									<?= $form_builder->renderCol('date_publish'); ?>
									<?= $form_builder->renderCol('is_category'); ?>
									<?= $form_builder->renderCol('allow_comment'); ?>
									<?= $form_builder->renderCol('hide_comments'); ?>
									<?= $form_builder->renderCol('is_enabled'); ?>
									<?= $form_builder->renderCol('language'); ?>
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
