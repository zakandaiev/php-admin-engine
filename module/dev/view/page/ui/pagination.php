<?php Theme::header(); ?>

<?php Theme::template('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::template('navbar/top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <nav class="breadcrumb">
        <span class="breadcrumb__item"><a href="<?= Route::link('dashboard') ?>">Home</a></span>
        <span class="breadcrumb__item"><a href="<?= Route::link('ui-home') ?>">Dev UI</a></span>
        <span class="breadcrumb__item">Pagination</span>
      </nav>

      <h2 class="section__title">Pagination</h2>

      <div class="box">
        <div class="box__body">
          <div>
            <nav class="pagination pagination_sm">
              <a href="/" class="pagination__item"><i class="ti ti-chevron-left"></i></a>
              <a href="/" class="pagination__item">1</a>
              <span class="pagination__item active">2</span>
              <a href="/" class="pagination__item">3</a>
              <span class="pagination__item">...</span>
              <a href="/" class="pagination__item">9</a>
              <a href="/" class="pagination__item">10</a>
              <a href="/" class="pagination__item"><i class="ti ti-chevron-right"></i></a>
            </nav>
          </div>

          <div class="mt-3">
            <nav class="pagination">
              <a href="/" class="pagination__item"><i class="ti ti-chevron-left"></i></a>
              <a href="/" class="pagination__item">1</a>
              <span class="pagination__item active">2</span>
              <a href="/" class="pagination__item">3</a>
              <span class="pagination__item">...</span>
              <a href="/" class="pagination__item">9</a>
              <a href="/" class="pagination__item">10</a>
              <a href="/" class="pagination__item"><i class="ti ti-chevron-right"></i></a>
            </nav>
          </div>

          <div class="mt-3">
            <nav class="pagination pagination_lg">
              <a href="/" class="pagination__item"><i class="ti ti-chevron-left"></i></a>
              <a href="/" class="pagination__item">1</a>
              <span class="pagination__item active">2</span>
              <a href="/" class="pagination__item">3</a>
              <span class="pagination__item">...</span>
              <a href="/" class="pagination__item">9</a>
              <a href="/" class="pagination__item">10</a>
              <a href="/" class="pagination__item"><i class="ti ti-chevron-right"></i></a>
            </nav>
          </div>

        </div>
      </div>

  </section>

  <?php Theme::template('navbar/bottom'); ?>

</main>

<?php Theme::footer(); ?>
