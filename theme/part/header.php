<!DOCTYPE html>
<html lang="<?= lang('locale') ?>">

<head>
  <?= Page::meta() ?>
  <?= Asset::render('css') ?>
</head>

<body>
  <script src="<?= Asset::resolve(Asset::url(), 'js', 'data-theme.js') ?>"></script>

  <header id="header" class="header">
    <div class="container">
      <h1>Header</h1>
    </div>
  </header>

  <main class="page-content">
