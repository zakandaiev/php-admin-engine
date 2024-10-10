<?php if (!empty($items)) : ?>
	<nav class="breadcrumb">
		<span class="breadcrumb__item"><a href="<?= Route::link('dashboard') ?>"><?= t('breadcrumb.home') ?></a></span>

		<?php foreach ($items as $crumb) : ?>
			<?php if (!empty($crumb->url)) : ?>
				<span class="breadcrumb__item"><a href="<?= Route::link($crumb->url) ?>"><?= $crumb->name ?></a></span>
			<?php else : ?>
				<span class="breadcrumb__item"><?= $crumb->name ?></span>
			<?php endif; ?>
		<?php endforeach; ?>
	</nav>
<?php endif; ?>
