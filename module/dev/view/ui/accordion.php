<?php Theme::header(); ?>

<?php Theme::block('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::block('navbar/top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <nav class="breadcrumb">
        <span class="breadcrumb__item"><a href="<?= Route::link('dashboard') ?>">Home</a></span>
        <span class="breadcrumb__item"><a href="<?= Route::link('ui-home') ?>">Dev UI</a></span>
        <span class="breadcrumb__item">Accordion</span>
      </nav>

      <h2 class="section__title">Accordion</h2>

      <div class="box">
        <div class="accordions" data-collapse>
          <div class="accordion accordion_underline" data-active>
            <button type="button" class="accordion__header">
              <span>Accordion</span>
              <i class="ti ti-chevron-right"></i>
            </button>

            <div class="accordion__body">
              <div class="accordion__content">
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Et, sint tempore? Rem sequi culpa esse numquam earum nemo quos alias repellat, hic necessitatibus. Voluptates accusamus dolores tempore accusantium repellat numquam?
              </div>
            </div>
          </div>

          <div class="accordion accordion_underline">
            <button type="button" class="accordion__header">
              <span>Accordion 2</span>
              <i class="ti ti-chevron-right"></i>
            </button>

            <div class="accordion__body">
              <div class="accordion__content">
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Et, sint tempore? Rem sequi culpa esse numquam earum nemo quos alias repellat, hic necessitatibus. Voluptates accusamus dolores tempore accusantium repellat numquam?
              </div>
            </div>
          </div>

          <div class="accordion accordion_underline">
            <button type="button" class="accordion__header">
              <span>Accordion 3</span>
              <i class="ti ti-chevron-right"></i>
            </button>

            <div class="accordion__body">
              <div class="accordion__content">
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Et, sint tempore? Rem sequi culpa esse numquam earum nemo quos alias repellat, hic necessitatibus. Voluptates accusamus dolores tempore accusantium repellat numquam?
              </div>
            </div>
          </div>
        </div>
      </div>

  </section>

  <?php Theme::block('navbar/bottom'); ?>

</main>

<?php Theme::footer(); ?>
