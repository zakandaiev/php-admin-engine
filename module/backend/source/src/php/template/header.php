<!DOCTYPE html>
<html lang="<?= lang('locale') ?>">

<head>
  <?= Page::meta() ?>
  <?= Asset::render() ?>
</head>

<body>
  <script src="<?= pathResolveUrl(Asset::url(), 'js', 'data-theme.js') ?>"></script>

  <div class="page-content">
