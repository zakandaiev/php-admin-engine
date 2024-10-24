<?php if (!empty($items)) : ?>
  <nav class="breadcrumb">
    <span class="breadcrumb__item"><a href="<?= routeLink('dashboard') ?>"><?= t('breadcrumb.home') ?></a></span>

    <?php foreach ($items as $crumb) : ?>
      <?php if (!empty($crumb->url)) : ?>
        <span class="breadcrumb__item"><a href="<?= routeLink($crumb->url, @$crumb->options['parameter'], @$crumb->options['query']) ?>"><?= $crumb->name ?></a></span>
      <?php else : ?>
        <span class="breadcrumb__item"><?= $crumb->name ?></span>
      <?php endif; ?>
    <?php endforeach; ?>
  </nav>
<?php endif; ?>
