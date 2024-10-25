<?php
$languages = site('languages');
?>

<?php if (count($languages) > 1) : ?>
  <div class="d-flex flex-wrap justify-content-center gap-2">
    <?php foreach ($languages as $language) : ?>
      <?php if ($language['key'] === site('language_current')) continue; ?>

      <a href="<?= pathResolveUrl(null, $language['key'], site('uri_full_no_language')) ?>" class="header__item" data-tooltip="right" title="<?= t("i18n.switch_to", $language['key']) ?>">
        <img src="<?= pathResolveUrl(Asset::url(), lang('icon', $language['key'])) ?>" class="fit-cover radius-round" alt="<?= $language['key'] ?>">
      </a>

    <?php endforeach; ?>
  </div>
<?php endif; ?>
