<?php
$languages = site('languages');
?>

<?php if (count($languages) > 1) : ?>
  <div class="header__item dropdown dropdown_bottom-right">
    <img src="<?= pathResolveUrl(Asset::url(), lang('icon')) ?>" class="fit-cover radius-round" alt="<?= lang() ?>">

    <div class="dropdown__menu">
      <?php foreach ($languages as $language) : ?>
        <?php if ($language['key'] === site('language_current')) continue; ?>

        <a href="<?= pathResolveUrl(null, $language['key'], site('uri_full_no_language')) ?>" class="dropdown__item d-flex align-items-center gap-2">
          <img src="<?= pathResolveUrl(Asset::url(), lang('icon', $language['key'])) ?>" alt="<?= $language['key'] ?>" class="flex-shrink-0 d-inline-block h-1em">

          <span><?= t("i18n.{$language['key']}") ?></span>
        </a>

      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>
