<?php Theme::header(); ?>

<?php Theme::template('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::template('navbar/top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <nav class="breadcrumb">
        <span class="breadcrumb__item"><a href="<?= Route::link('dashboard') ?>">Home</a></span>
        <span class="breadcrumb__item"><a href="<?= Route::link('ui-home') ?>">Dev UI</a></span>
        <span class="breadcrumb__item">Alerts</span>
      </nav>

      <h2 class="section__title">Alerts</h2>

      <div class="box">
        <div class="box__header">
          <h4 class="box__title">Title</h4>
          <h5 class="box__subtitle">Subitle</h5>
        </div>

        <div class="box__body">
          TODO
        </div>
      </div>

    </div>
  </section>

  <?php Theme::template('navbar/bottom'); ?>

</main>

<?php Theme::footer(); ?>
