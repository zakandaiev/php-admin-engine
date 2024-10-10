<?php

$pageTitle = 'Dev UI';

Page::set('title', $pageTitle);

Page::breadcrumb('set', $pageTitle);

?>

<?php Theme::header(); ?>

<?php Theme::template('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::template('navbar/top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <?php Theme::breadcrumb(); ?>

      <h2 class="section__title"><?= $pageTitle ?></h2>

      <div class="box mt-2">
        <div class="box__header">
          <h4 class="box__title">List of ready to use UI elements</h4>
        </div>

        <nav class="list-group">
          <?php foreach (getUiSectionFiles(true) as $sectionName => $sectionLink): ?>
            <a href="<?= $sectionLink ?>" class="list-group__item"><?= $sectionName ?></a>
          <?php endforeach; ?>
        </nav>
      </div>
  </section>

  <?php Theme::template('navbar/bottom'); ?>

</main>

<?php Theme::footer(); ?>
