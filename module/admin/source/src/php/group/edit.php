<?php

$title = __('admin.group.edit_group');

Page::set('title', $title);

Page::breadcrumb('add', ['name' => __('admin.group.groups'), 'url' => '/admin/group']);
Page::breadcrumb('add', ['name' => $title]);

$form_builder = new FormBuilder('group/group');
$form_attributes = 'data-redirect="' . site('url_language') . '/admin/group" data-validate';

foreach($group as $field_name => $value) {
	$form_builder->setFieldValue($field_name, $value);
}

$routes_all = $routes;
foreach($group->routes as $gr_method => $gr_routes) {
	foreach($gr_routes as $gr_r) {
		if(isset($routes_all[$gr_method]) && in_array($gr_r, $routes_all[$gr_method])) {
			continue;
		}

		$routes_all[$gr_method][] = $gr_r;
	}
}
$routes_formatted = [];

foreach($routes_all as $method => $r) {
	foreach($r as $p) {
		$r = new \stdClass();

		$r->value = $method . '@' . $p;
		$r->name = $p;
		$r->selected = in_array($p, $group->routes->$method ?? []) ? true : false;

		$routes_formatted[$method][] = $r;
	}
}

$form_builder->setFieldValue('routes', $routes_formatted);
$form_builder->setFieldValue('users', array_map(function($user) use($group) {
	$u = new \stdClass();

	$u->value = $user->id;
	$u->name = $user->fullname;
	$u->selected = in_array($user->id, $group->users ?? []) ? true : false;

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
				<?php if($group->date_edited): ?>
				<span class="label label_info align-self-center"><?= __('admin.group.last_edit_at', format_date($group->date_edited)) ?></span>
				<?php endif; ?>
			</h2>

			<div class="box">
				<div class="box__body">
					<?= $form_builder->render('edit', $group->id, $form_attributes) ?>
				</div>
			</div>

		</div>
	</section>

	<?php Theme::block('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
