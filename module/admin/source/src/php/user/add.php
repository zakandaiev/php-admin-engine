<?php

$title = __('admin.user.add_user');

Page::set('title', $title);

Page::breadcrumb('add', ['name' => __('admin.user.users'), 'url' => '/admin/user']);
Page::breadcrumb('add', ['name' => $title]);

$form_builder = new FormBuilder('user/add');
$form_attributes = 'data-redirect="' . site('url_language') . '/admin/user" data-validate';

$form_builder->setFieldValue('group', array_map(function($group) {
	$t = new \stdClass();
	$t->value = $group->id;
	$t->name = $group->name;
	$t->selected = false;
	return $t;
}, $groups));
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
					<?= $form_builder->render('add', null, $form_attributes) ?>
				</div>
			</div>

		</div>
	</section>

	<?php Theme::block('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
