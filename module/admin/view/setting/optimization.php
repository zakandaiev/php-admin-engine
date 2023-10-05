<?php

$title = __('admin.setting.edit_optimization');

Page::set('title', $title);

Page::breadcrumb('add', ['name' => 'admin.setting.title']);
Page::breadcrumb('add', ['name' => $title]);

$form_builder = new FormBuilder('setting/optimization');
$form_attributes = 'data-validate';

$languages = site('languages');

foreach($setting as $field_name => $value) {
	$form_builder->setFieldValue($field_name, $value);
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

			<div class="box">
				<div class="box__body">
				<form method="POST">
					<div class="form-check form-switch mb-3">
						<input class="form-check-input" type="checkbox" id="group_css" name="group_css" <?php if($settings->group_css != 'false' && !empty($settings->group_css)): ?>checked<?php endif; ?>>
						<label class="form-check-label" for="group_css">
							<?= __('Group CSS assets') ?>
							<?php if($settings->group_css != 'false' && !empty($settings->group_css)): ?>
								<?php
									$file_url = Path::url('asset', 'public') . '/css/' . $settings->group_css . '.css';
									$file_path = Path::file('asset', 'public') . '/css/' . $settings->group_css . '.css';
								?>
								<br>
								<a class="text-sm" href="<?= $file_url ?>" target="_blank"><?= __('Final size') ?>: <?= file_size($file_path) ?></a>
							<?php endif; ?>
						</label>
					</div>
					<div class="form-check form-switch mb-3">
						<input class="form-check-input" type="checkbox" id="group_js" name="group_js" <?php if($settings->group_js != 'false' && !empty($settings->group_js)): ?>checked<?php endif; ?>>
						<label class="form-check-label" for="group_js">
							<?= __('Group JS assets') ?>
							<?php if($settings->group_js != 'false' && !empty($settings->group_js)): ?>
								<?php
									$file_url = Path::url('asset', 'public') . '/js/' . $settings->group_js . '.js';
									$file_path = Path::file('asset', 'public') . '/js/' . $settings->group_js . '.js';
								?>
								<br>
								<a class="text-sm" href="<?= $file_url ?>" target="_blank"><?= __('Final size') ?>: <?= file_size($file_path) ?></a>
							<?php endif; ?>
						</label>
					</div>
					<div class="form-check form-switch mb-3">
						<input class="form-check-input" type="checkbox" id="cache_db" name="cache_db" <?php if($settings->cache_db == 'true'): ?>checked<?php endif; ?>>
						<label class="form-check-label" for="cache_db">
							<?= __('Cache database queries') ?>
							<?php if($settings->cache_db == 'true'): ?>
								<a data-action="<?= site('url_language') ?>/admin/setting/optimization/flush-cache" data-confirm="<?= __('Flush cache') ?>?" data-class="submit" data-class-target="form" data-delete="this" href="#" class="text-sm"><br><?= __('Cache size') ?>: <?= file_size(Path::file('cache')) ?></a>
							<?php endif; ?>
						</label>
					</div>
					<button type="submit" class="btn btn-primary"><?= __('Save') ?></button>
				</form>
				</div>
			</div>

		</div>
	</section>

	<?php Theme::block('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
