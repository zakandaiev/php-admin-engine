<?php Theme::header(); ?>

<div class="hero section_offset">
  <div class="hero__main">
    <h1 class="hero__title"><?= getEngineProperty('name') ?></h1>

    <h2 class="hero__subtitle">Dummy module example</h2>

    <h3 class="hero__text">This is a boilerplate kit for easy building web based modules for <?= getEngineProperty('name') ?></h3>

    <div class="hero__actions">
      <a href="<?= getHeaderMenu()[1]['link'] ?>" class="btn btn_primary">Get Started</a>
      <a href="<?= getEngineProperty('repository_url') ?>" target="_blank" class="btn">View on GitHub</a>
    </div>
  </div>

  <div class="hero__image">
    <img src="<?= resolveUrl(Asset::url(), 'favicon.svg') ?>" alt="HTML5 Logo" height="320">
  </div>
</div>


<div class="section section_offset">
  <div class="row cols-xs-1 cols-sm-2 cols-md-3 gap-xs">
    <?php foreach (getFeatures() as $item): ?>
      <div class="col">
        <div class="box h-100">
          <div class="box__icon"><?= $item['icon'] ?></div>
          <div class="box__title"><?= $item['title'] ?></div>
          <div class="box__text"><?= $item['text'] ?></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php Theme::footer(); ?>
