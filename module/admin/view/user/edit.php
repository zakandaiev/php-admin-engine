<?php

use \Module\Admin\Controller\FormBuilder;

$title = __('admin.user.edit_user');

Page::set('title', $title);

Breadcrumb::add(__('admin.user.users'), '/admin/user');
Breadcrumb::add($title);

$form_builder = new FormBuilder('user/edit');
$form_attributes = 'data-redirect="' . site('url_language') . '/admin/user" data-validate';

foreach($user as $field_name => $value) {
	if($field_name === 'password') {
		continue;
	}

	$form_builder->setFieldValue($field_name, $value);
}

$form_builder->setFieldValue('group', array_map(function($group) use ($user) {
	$t = new \stdClass();
	$t->value = $group->id;
	$t->name = $group->name;
	$t->selected = in_array($t->value, $user->groups);
	return $t;
}, $groups));

?>

<?php Theme::header(); ?>

<?php Theme::block('sidebar'); ?>

<main class="page-content__inner">

	<?php Theme::block('navbar-top'); ?>

	<section class="section section_grow section_offset">
		<div class="container-fluid">

			<?php Theme::breadcrumb() ?>

			<h2 class="section__title">
				<span><?= $title ?></span>
				<?php if($user->date_edited): ?>
				<span class="label label_info align-self-center"><?= __('admin.user.last_edit', format_date($user->date_edited)) ?></span>
				<?php endif; ?>
			</h2>

			<div class="box">
				<div class="box__body">
					<?= $form_builder->render('edit', $user->id, $form_attributes); ?>
				</div>
			</div>

		</div>
	</section>

	<?php Theme::block('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
