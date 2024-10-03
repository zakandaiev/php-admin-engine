<?php Theme::header(); ?>

<?php Theme::block('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::block('navbar/top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <nav class="breadcrumb">
        <span class="breadcrumb__item"><a href="<?= Route::link('dashboard') ?>">Home</a></span>
        <span class="breadcrumb__item"><a href="<?= Route::link('ui-home') ?>">Dev UI</a></span>
        <span class="breadcrumb__item">Popovers</span>
      </nav>

      <h2 class="section__title">Popovers</h2>

      <div class="box">
        <div class="box__body text-center">
          <button type="button" class="btn btn_secondary m-1" data-popover="top" data-title="Popover on top" data-content="Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.">Popover on top</button>

          <br>

          <button type="button" class="btn btn_secondary m-1" data-popover="left" data-title="Popover on left" data-content="Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.">Popover on left</button>

          <button type="button" class="btn btn_secondary m-1" data-popover="right" data-title="Popover on right" data-content="Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.">Popover on right</button>

          <br>

          <button type="button" class="btn btn_secondary m-1" data-popover="bottom" data-title="Popover on bottom" data-content="Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.">Popover on bottom</button>
        </div>
      </div>

  </section>

  <?php Theme::block('navbar/bottom'); ?>

</main>

<?php Theme::footer(); ?>
