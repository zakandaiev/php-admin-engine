<?php

$title = __('admin.group.add_group');

Page::set('title', $title);

Page::breadcrumb('add', ['name' => __('admin.group.groups'), 'url' => '/admin/group']);
Page::breadcrumb('add', ['name' => $title]);

$form_builder = new FormBuilder('group/group');
$form_attributes = 'data-redirect="' . site('url_language') . '/admin/group" data-validate';

$routes_formatted = [];

foreach ($routes as $method => $r) {
	foreach ($r as $p) {
		$r = new \stdClass();

		$r->value = $method . '@' . $p;
		$r->name = $p;
		$r->selected = false;

		$routes_formatted[$method][] = $r;
	}
}

$form_builder->setFieldValue('routes', $routes_formatted);
$form_builder->setFieldValue('users', array_map(function ($user) {
	$u = new \stdClass();

	$u->value = $user->id;
	$u->name = $user->fullname;
	$u->selected = false;

	return $u;
}, $users));
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
