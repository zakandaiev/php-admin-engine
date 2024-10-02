<!DOCTYPE html>
<html lang="<?= lang('locale') ?>">

<head>
  <?= Page::meta() ?>
  <?= Asset::render('css') ?>
  <?= Asset::render('js') ?>
</head>

<body>
  <script src="<?= Asset::resolve(Asset::url(), 'js', 'data-theme.js') ?>"></script>

  <header id="header" class="header">
    <div class="container">
      <div class="header__wrapper">
        <a href="<?= Route::link('home') ?>" class="header__logo">
          <img class="header__logo-image" src="<?= Asset::resolve(Asset::url(), 'favicon.svg') ?>" alt="HTML5 Logo" height="24">
          <span class="header__logo-text"><?= Module::getName() ?></span>
          <span class="label label_primary header__logo-label">v<?= Module::getProperty('version') ?></span>
        </a>

        <nav class="header__nav">
          <?php foreach (getHeaderMenu() as $item): ?>

            <a href="<?= $item['link'] ?>" class="header__nav-item {% if active_nav == nav.link %}active{% endif %}"><?= $item['name'] ?></a>
          <?php endforeach; ?>
        </nav>

        <div class="header__appearance">
          <span data-theme-set="dark" class="header__appearance-item" title="Switch to dark theme"><?= Asset::getContent('img/icon/sun.svg') ?></span>
          <span data-theme-set="light" class="header__appearance-item" title="Switch to light theme"><?= Asset::getContent('img/icon/moon.svg') ?></span>
        </div>

        <div class="header__social">
          <a href="<?= getEngineProperty('repository_url') ?>" target="_blank" class="header__social-item" title="View on GitHub">
            <?= Asset::getContent('img/social-icon/github.svg') ?>
          </a>
        </div>
      </div>
    </div>
  </header>

  <main class="page-content">
