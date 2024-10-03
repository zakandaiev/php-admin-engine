<?php

Theme::header(); ?>

<?php Theme::block('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::block('navbar/top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <nav class="breadcrumb">
        <span class="breadcrumb__item"><a href="<?= Route::link('dashboard') ?>">Home</a></span>
        <span class="breadcrumb__item">Dev UI</span>
      </nav>

      <h2 class="section__title">Dev UI</h2>

      <div class="box mt-2">
        <div class="box__header">
          <h4 class="box__title">List of all UI sections</h4>
        </div>

        <nav class="list-group">
          <?php foreach (getUiSectionFiles(true) as $sectionName => $sectionLink): ?>
            <a href="<?= $sectionLink ?>" class="list-group__item"><?= $sectionName ?></a>
          <?php endforeach; ?>
        </nav>
      </div>
  </section>

  <?php Theme::block('navbar/bottom'); ?>

</main>

<?php Theme::footer(); ?>
