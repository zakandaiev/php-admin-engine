<?php

$title = __('admin.setting.edit_main');

Page::set('title', $title);

Page::breadcrumb('add', ['name' => 'admin.setting.title']);
Page::breadcrumb('add', ['name' => $title]);

$form_builder = new FormBuilder('setting/main');
$form_attributes = 'data-validate';

$languages = site('languages');

foreach ($setting as $field_name => $value) {
	$form_builder->setFieldValue($field_name, $value);
}

$form_builder->setFieldValue('language', array_map(function ($language) use ($setting) {
	$l = new \stdClass();

	$l->value = $language['key'];
	$l->name = $language['key'] . '_' . $language['region'] . ' - ' . __('locale.' . $language['key']);
	$l->selected = $l->value === $setting->language ? true : false;

	return $l;
}, $languages));

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
