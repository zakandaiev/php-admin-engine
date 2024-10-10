<!DOCTYPE html>
<html lang="<?= lang('locale') ?>">

<head>
  <?= Page::meta() ?>
  <?= Asset::render('css') ?>
  <?= Asset::render('js') ?>
</head>

<body>
  <script src="<?= Path::resolveUrl(Asset::url(), 'js', 'data-theme.js') ?>"></script>

  <div class="page-content">
