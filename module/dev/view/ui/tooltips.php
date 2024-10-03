<?php Theme::header(); ?>

<?php Theme::block('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::block('navbar/top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <nav class="breadcrumb">
        <span class="breadcrumb__item"><a href="<?= Route::link('dashboard') ?>">Home</a></span>
        <span class="breadcrumb__item"><a href="<?= Route::link('ui-home') ?>">Dev UI</a></span>
        <span class="breadcrumb__item">Tooltips</span>
      </nav>

      <h2 class="section__title">Tooltips</h2>

      <div class="box">
        <div class="box__body text-center">
          <button type="button" class="btn btn_secondary m-1" data-tooltip="top" title="Tooltip on top">Tooltip on top</button>

          <br>

          <button type="button" class="btn btn_secondary m-1" data-tooltip="left" title="Tooltip on left">Tooltip on left</button>

          <button type="button" class="btn btn_secondary m-1" data-tooltip="right" title="Tooltip on right">Tooltip on right</button>

          <br>

          <button type="button" class="btn btn_secondary m-1" data-tooltip="bottom" title="Tooltip on bottom">Tooltip on bottom</button>
        </div>
      </div>

  </section>

  <?php Theme::block('navbar/bottom'); ?>

</main>

<?php Theme::footer(); ?>
