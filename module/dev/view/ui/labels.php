<?php Theme::header(); ?>

<?php Theme::block('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::block('navbar/top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <nav class="breadcrumb">
        <span class="breadcrumb__item"><a href="<?= Route::link('dashboard') ?>">Home</a></span>
        <span class="breadcrumb__item"><a href="<?= Route::link('ui-home') ?>">Dev UI</a></span>
        <span class="breadcrumb__item">Labels</span>
      </nav>

      <h2 class="section__title">Labels</h2>

      <div class="box">
        <div class="box__body">
          <div>
            <?php foreach (getButtonColors() as $color): ?>
              <span class="label label_<?= $color ?>"><?= ucfirst($color) ?></span>
            <?php endforeach; ?>
          </div>

          <div class="mt-3">
            <?php foreach (getButtonAccentColors() as $color): ?>
              <span class="label label_light label_<?= $color ?>"><?= ucfirst($color) ?></span>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

  </section>

  <?php Theme::block('navbar/bottom'); ?>

</main>

<?php Theme::footer(); ?>
