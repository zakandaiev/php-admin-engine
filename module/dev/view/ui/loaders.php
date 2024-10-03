<?php Theme::header(); ?>

<?php Theme::block('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::block('navbar/top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <nav class="breadcrumb">
        <span class="breadcrumb__item"><a href="<?= Route::link('dashboard') ?>">Home</a></span>
        <span class="breadcrumb__item"><a href="<?= Route::link('ui-home') ?>">Dev UI</a></span>
        <span class="breadcrumb__item">Loaders</span>
      </nav>

      <h2 class="section__title">Loaders</h2>

      <div class="box">
        <div class="box__body">
          <span class="loader loader_lg"></span>
          <span class="loader loader_lg loader_heading"></span>
          <span class="loader loader_lg loader_subheading"></span>
          <span class="loader loader_lg loader_muted"></span>
          <span class="loader loader_lg loader_default"></span>
          <span class="loader loader_lg loader_cancel"></span>
          <span class="loader loader_lg loader_primary"></span>
          <span class="loader loader_lg loader_secondary"></span>
          <span class="loader loader_lg loader_success"></span>
          <span class="loader loader_lg loader_error"></span>
          <span class="loader loader_lg loader_warning"></span>
          <span class="loader loader_lg loader_info"></span>

          <hr>

          <span class="loader"></span>
          <span class="loader loader_heading"></span>
          <span class="loader loader_subheading"></span>
          <span class="loader loader_muted"></span>
          <span class="loader loader_default"></span>
          <span class="loader loader_cancel"></span>
          <span class="loader loader_primary"></span>
          <span class="loader loader_secondary"></span>
          <span class="loader loader_success"></span>
          <span class="loader loader_error"></span>
          <span class="loader loader_warning"></span>
          <span class="loader loader_info"></span>

          <hr>

          <span class="loader loader_sm"></span>
          <span class="loader loader_sm loader_heading"></span>
          <span class="loader loader_sm loader_subheading"></span>
          <span class="loader loader_sm loader_muted"></span>
          <span class="loader loader_sm loader_default"></span>
          <span class="loader loader_sm loader_cancel"></span>
          <span class="loader loader_sm loader_primary"></span>
          <span class="loader loader_sm loader_secondary"></span>
          <span class="loader loader_sm loader_success"></span>
          <span class="loader loader_sm loader_error"></span>
          <span class="loader loader_sm loader_warning"></span>
          <span class="loader loader_sm loader_info"></span>
        </div>
      </div>

  </section>

  <?php Theme::block('navbar/bottom'); ?>

</main>

<?php Theme::footer(); ?>
