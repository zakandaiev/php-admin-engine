<?php

$title = 'Log';

Page::set('title', $title);

Page::breadcrumb('add', 'Logs', 'log.list');
// Page::breadcrumb('add', $title);
if ($folder) {
  Page::breadcrumb('add', $folder, 'log.list');
}
Page::breadcrumb('add', $file);
?>

<?php Theme::header(); ?>

<?php Theme::template('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::template('navbar-top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <?php Theme::breadcrumb(); ?>

      <h2 class="section__title">Log</h2>

      <div class="box">
        <div class="box__body">
          <div class="log"><?= formatLog($log) ?></div>
        </div>
      </div>

    </div>
  </section>

  <?php Theme::template('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
