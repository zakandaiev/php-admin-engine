<?php
	Page::set('no_index_no_follow', true);
	Page::set('title', __('admin.404.title'));
?>

<?php Theme::header(); ?>

<main class="page-content__inner">

	<section class="section section_grow section_offset">
		<div class="container h-100 d-flex flex-column align-items-center justify-content-center text-center">

			<h1 class="font-size-60 mb-4"><strong>404</strong></h1>

			<h2 class="mt-0 mb-3"><?= __('admin.404.title') ?></h2>

			<h4 class="color-text"><?= __('admin.404.subtitle') ?></h4>

			<div class="d-flex gap-2 mt-4">
				<?php if(isset(Request::$referer)): ?>
					<a href="<?= Request::$referer ?>" class="btn btn_lg btn_secondary"><?= __('admin.404.go_back') ?></a>
					<?php if(trim(Request::$referer ?? '', '/') !== Request::$base): ?>
						<a href="<?= site('url_language') ?>/admin/dashboard" class="btn btn_lg btn_primary"><?= __('admin.404.go_dashboard') ?></a>
					<?php endif; ?>
				<?php else: ?>
					<a href="<?= site('url_language') ?>/admin/dashboard" class="btn btn_lg btn_primary"><?= __('admin.404.go_dashboard') ?></a>
				<?php endif; ?>
			</div>

		</div>
	</section>

</main>

<?php Theme::footer(); ?>
