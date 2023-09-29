<?php

$languages = site('languages');

?>

<?php if(!empty($languages)): ?>

<div class="header__item dropdown dropdown_bottom-right">
	<img src="<?= Asset::url() ?>/<?= lang('icon') ?>" class="fit-cover radius-circle" alt="<?= lang('locale') ?>">

	<div class="dropdown__menu">
		<?php foreach($languages as $language): ?>

			<?php if($language['key'] === site('language_current')) continue; ?>

			<a href="<?= site('url') ?>/<?= $language['key'] . site('uri_no_language') ?>" class="dropdown__item d-flex align-items-center gap-2">
				<img src="<?= Asset::url() ?>/<?= lang('icon', $language['key']) ?>" alt="<?= lang('locale', $language['key']) ?>" class="flex-shrink-0 d-inline-block h-1em">
				<span><?= __("locale.{$language['key']}") ?></span>
			</a>

		<?php endforeach; ?>
	</div>
</div>

<?php endif; ?>
