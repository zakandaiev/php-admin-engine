<?php

use \engine\module\Hook;

$sidebar = Hook::getData('admin.sidebar');

// TODO
function checkRouteAccess($route)
{
  return true;
  // if(User::get()->access_all) {
  // 	return true;
  // }

  // if(is_array($route)) {
  // 	$route = array_map(function($string) {
  // 		return trim($string ?? '', '/');
  // 	}, $route);
  // } else {
  // 	$route = trim($route ?? '', '/');
  // }

  // foreach(User::get()->routes as $user_route) {
  // 	list($method, $uri) = explode('@', $user_route);

  // 	$uri = trim($uri ?? '', '/');

  // 	if($method !== 'get') {
  // 		continue;
  // 	}

  // 	if(is_array($route) && in_array($uri, $route)) {
  // 		return true;
  // 	} else if($route === $uri) {
  // 		return true;
  // 	}
  // }

  // return false;
}

?>

<aside class="sidebar">
  <a class="sidebar__logo" href="<?= Route::link('dashboard', 'backend') ?>">
    <?php if (!empty(site('logo_alt'))) : ?>
      <img class="sidebar__logo-image" src="<?= site('url') ?>/<?= site('logo_alt') ?>" alt="Logo">
    <?php else : ?>
      <span class="sidebar__logo-text"><?= site('name') ?></span>
    <?php endif; ?>
  </a>

  <nav class="sidebar__nav">
    <?php foreach ($sidebar as $item) : ?>
      <?php
      if (!@$item['is_public'] && !checkRouteAccess(@$item['route'])) {
        continue;
      }
      ?>

      <?php if (isset($item['is_separator']) && $item['is_separator']) : ?>
        <span class="sidebar__separator"><?= $item['name'] ?></span>
      <?php elseif (is_array($item['route'])) : ?>
        <div class="sidebar__collapse <?php if (Route::isActive('todo' ?? $item['route'])) : ?>active<?php endif; ?>">
          <span class="sidebar__item <?php if (Route::isActive('todo' ?? $item['route'])) : ?>active<?php endif; ?>">
            <i class="ti ti-<?= $item['icon'] ?>"></i>
            <span class="sidebar__text"><?= $item['name'] ?></span>
          </span>

          <div class="sidebar__collapse-menu">
            <?php foreach ($item['route'] as $key => $value) : ?>
              <a href="<?= $value ?>" class="sidebar__collapse-item <?php if (Route::isActive('todo' ?? $value)) : ?>active<?php endif; ?>">
                <span class="sidebar__text"><?= $key ?></span>
                <!-- <span class="label label_primary">2</span> -->
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      <?php else : ?>
        <a href="<?= $item['route'] ?>" class="sidebar__item <?php if (Route::isActive('todo' ?? $item['route'])) : ?>active<?php endif; ?>">
          <i class="ti ti-<?= $item['icon'] ?>"></i>
          <span class="sidebar__text"><?= $item['name'] ?></span>
          <?php
          if (false) :
            // TODO
            // if(
            // 	isset($item['label'])
            // 	&& (
            // 		isClosure($item['label'])
            // 		||
            // 		(!isClosure($item['label']) && !empty($item['label']))
            // 	)
            // ):
          ?>
            <span class="label label_<?php if (isset($item['label_color'])) : ?><?= $item['label_color'] ?><?php else : ?>primary<?php endif; ?>">
              <?php if (isClosure($item['label'])) : ?>
                <?= $item['label']() ?>
              <?php else : ?>
                <?= $item['label'] ?>
              <?php endif; ?>
            </span>
          <?php endif; ?>
        </a>
      <?php endif; ?>
    <?php endforeach; ?>
  </nav>
</aside>
