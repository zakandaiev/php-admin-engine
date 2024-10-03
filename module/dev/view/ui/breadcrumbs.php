<?php Theme::header(); ?>

<?php Theme::block('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::block('navbar/top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <nav class="breadcrumb">
        <span class="breadcrumb__item"><a href="<?= Route::link('dashboard') ?>">Home</a></span>
        <span class="breadcrumb__item"><a href="<?= Route::link('ui-home') ?>">Dev UI</a></span>
        <span class="breadcrumb__item">Breadcrumbs</span>
      </nav>

      <h2 class="section__title">Breadcrumbs</h2>

      <div class="box">
        <div class="box__body">

          <nav class="breadcrumb">
            <span class="breadcrumb__item">Home</span>
          </nav>

          <nav class="breadcrumb">
            <span class="breadcrumb__item"><a href="/">Home</a></span>
            <span class="breadcrumb__item">Library</span>
          </nav>

          <nav class="breadcrumb">
            <span class="breadcrumb__item"><a href="/">Home</a></span>
            <span class="breadcrumb__item"><a href="/">Library</a></span>
            <span class="breadcrumb__item">Data</span>
          </nav>

        </div>
      </div>

  </section>

  <?php Theme::block('navbar/bottom'); ?>

</main>

<?php Theme::footer(); ?>
