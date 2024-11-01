<?php

use engine\http\Request;

$pageTitle = t('dashboard.title');
$pageSubtitle = t('dashboard.subtitle');

Page::set('no_index_no_follow', true);
Page::set('title', $pageTitle);

$referer = Request::referer();
?>

<?php Theme::header(); ?>

<main class="page-content__inner">

  <section class="section section_grow section_offset">
    <div class="container h-100 d-flex flex-column align-items-center justify-content-center text-center">

      <h1 class="font-size-60 mb-4"><strong>404</strong></h1>

      <h2 class="mt-0 mb-3"><?= $pageTitle ?></h2>

      <h4 class="color-text"><?= $pageSubtitle ?></h4>

      <div class="d-flex gap-2 mt-4">
        <?php if ($referer && !routeIsActive($referer)) : ?>
          <a href="<?= $referer ?>" class="btn btn_lg btn_secondary"><?= t('404.go_back') ?></a>
        <?php endif; ?>

        <?php if ($referer !== routeLink('dashboard')) : ?>
          <a href="<?= routeLink('dashboard') ?>" class="btn btn_lg btn_primary"><?= t('404.go_dashboard') ?></a>
        <?php endif; ?>
      </div>

    </div>
  </section>

</main>

<?php Theme::footer(); ?>
