<?php

$title = __('admin.setting.edit_optimization');

Page::set('title', $title);

Page::breadcrumb('add', ['name' => 'admin.setting.title']);
Page::breadcrumb('add', ['name' => $title]);

$form_builder = new FormBuilder('setting/optimization');
$form_attributes = 'data-validate data-redirect="this"';

$languages = site('languages');

if(site('group_css') != 'false' && !empty(site('group_css'))) {
	$form_builder->setFieldValue('group_css', true);
}
else {
	$form_builder->setFieldValue('group_css', false);
}

if(site('group_js') != 'false' && !empty(site('group_js'))) {
	$form_builder->setFieldValue('group_js', true);
}
else {
	$form_builder->setFieldValue('group_js', false);
}

if(site('cache_db') == 'true') {
	$form_builder->setFieldValue('cache_db', true);
}
else {
	$form_builder->setFieldValue('cache_db', false);
}

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

			<div class="box">
				<div class="box__body">
					<?= $form_builder->render('edit', 'engine', $form_attributes) ?>
				</div>
			</div>

		</div>
	</section>

	<?php Theme::block('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
