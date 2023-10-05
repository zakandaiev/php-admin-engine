<?php

$title = __('admin.setting.edit_contact');

Page::set('title', $title);

Page::breadcrumb('add', ['name' => 'admin.setting.title']);
Page::breadcrumb('add', ['name' => $title]);

$form_builder = new FormBuilder('setting/contact');
$form_attributes = 'data-validate';

$languages = site('languages');

foreach($setting as $field_name => $value) {
	if(in_array($field_name, ['name', 'description', 'address', 'hours'])) {
		$form_builder->setFieldValue($field_name, site($field_name));
	}
	else {
		$form_builder->setFieldValue($field_name, $value);
	}
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
