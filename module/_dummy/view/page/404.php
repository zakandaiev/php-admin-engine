<?php Theme::header(); ?>

<div class="container">
  <div class="hero section_offset">
    <div class="hero__main">
      <h1 class="hero__title">404</h1>

      <h2 class="hero__subtitle">Page not found</h2>

      <h3 class="hero__text">The page you are looking for might have been removed</h3>

      <div class="hero__actions">
        <a href="<?= Route::link('home') ?>" class="btn btn_primary">Return to Homepage</a>
      </div>
    </div>

    <div class="hero__image">
      <img src="<?= resolveUrl(Asset::url(), 'favicon.svg') ?>" alt="HTML5 Logo" height="320">
    </div>
  </div>
</div>

<?php Theme::footer(); ?>
