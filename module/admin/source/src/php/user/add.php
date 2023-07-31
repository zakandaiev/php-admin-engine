<?php
	use \Module\Admin\Controller\FormBuilder;

	$title = __('admin.user.add_user');

	Page::set('title', $title);

	Breadcrumb::add(__('admin.user.users'), '/admin/user');
	Breadcrumb::add($title);

	$form_builder = new FormBuilder('user/User');
?>

<?php Theme::header(); ?>

<?php Theme::block('sidebar'); ?>

<main class="page-content__inner">

	<?php Theme::block('navbar-top'); ?>

	<section class="section section_grow section_offset">
		<div class="container-fluid">

			<?php Theme::breadcrumb() ?>

			<h2 class="section__title">
				<span><?= __('admin.user.users') ?></span>
			</h2>

			<div class="box">
				<div class="box__body">
					<?= $form_builder->render('add', null, 'data-redirect="' . site('url_language') . '/admin/page" data-validate'); ?>
				</div>
			</div>

		</div>
	</section>

	<?php Theme::block('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
