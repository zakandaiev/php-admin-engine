<?php

$languages = site('languages');

?>

<?php if(!empty($languages)): ?>

	<div class="d-flex flex-wrap justify-content-center gap-2">
		<?php foreach($languages as $language): ?>

			<?php if($language['key'] === site('language_current')) continue; ?>

			<a href="<?= site('url') ?>/<?= $language['key'] . site('uri_no_language') ?>" class="header__item" title="<?= __("locale.{$language['key']}") ?>">
				<img src="<?= Asset::url() ?>/<?= lang('icon', $language['key']) ?>" class="fit-cover radius-circle" alt="<?= lang('locale', $language['key']) ?>">
			</a>

		<?php endforeach; ?>
	</div>

<?php endif; ?>
