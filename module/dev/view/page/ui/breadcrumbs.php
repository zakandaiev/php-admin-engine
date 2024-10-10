<?php Theme::template('ui-header', ['title' => 'Breadcrumbs']); ?>

<div class="box">
  <div class="box__body">

    <nav class="breadcrumb">
      <span class="breadcrumb__item">Home</span>
    </nav>

    <nav class="breadcrumb">
      <span class="breadcrumb__item"><a href="<?= Route::link('home') ?>">Home</a></span>
      <span class="breadcrumb__item">Path</span>
    </nav>

    <nav class="breadcrumb">
      <span class="breadcrumb__item"><a href="<?= Route::link('home') ?>">Home</a></span>
      <span class="breadcrumb__item"><a href="<?= Route::link('path') ?>">Path</a></span>
      <span class="breadcrumb__item">Data</span>
    </nav>

  </div>
</div>

<?php Theme::template('ui-footer'); ?>
