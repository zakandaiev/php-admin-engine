<?php
$pageTitle = @$title;
$parentTitle = 'Dev UI';

if ($pageTitle) {
  Page::set('title', "$pageTitle | $parentTitle");
}

Page::breadcrumb('add', $parentTitle, 'ui-home');
if ($pageTitle) {
  Page::breadcrumb('add', $pageTitle);
}
?>

<?php Theme::header(); ?>

<?php Theme::template('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::template('navbar-top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <?php Theme::breadcrumb(); ?>

      <h2 class="section__title"><?= $pageTitle ?></h2>
