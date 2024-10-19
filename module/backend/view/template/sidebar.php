<?php

use \engine\module\Hook;

$sidebar = Hook::getData('sidebar');

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

function isRouteActive($route = [])
{
  if (isset($route['name']) && is_string($route['name'])) {
    return isRouteActive($route['name'], @$route['parameter'], @$route['module']);
  }

  return false;
}

function isRouteParentActive($route = [])
{
  if (!isset($route['name']) || !is_array($route['name'])) {
    return false;
  }

  $result = false;

  foreach ($route['name'] as $item) {
    if (!is_array($item) || !isset($item['name'])) {
      continue;
    }

    $result = isRouteActive($item['name'], @$item['parameter'], @$item['module']);

    if ($result) {
      return true;
    }
  }

  return false;
}
?>

<aside class="sidebar">
  <a class="sidebar__logo" href="<?= routeLink('dashboard', null, null, 'backend') ?>">
    <?php if (!empty(site('logo_alt'))) : ?>
      <img class="sidebar__logo-image" src="<?= pathResolveUrl(null, site('logo_alt')) ?>" alt="Logo">
    <?php else : ?>
      <span class="sidebar__logo-text"><?= site('name') ?></span>
    <?php endif; ?>
  </a>

  <nav class="sidebar__nav">
    <?php foreach ($sidebar as $item) : ?>
      <?php
      if (!@$item['isPublic'] && !checkRouteAccess(@$item['name'])) {
        continue;
      }
      ?>

      <?php if (@$item['isSeparator'] === true) : ?>
        <span class="sidebar__separator"><?= $item['text'] ?></span>
      <?php elseif (is_array($item['name'])) : ?>
        <div class="sidebar__collapse <?php if (isRouteParentActive($item)) : ?>active<?php endif; ?>">
          <span class="sidebar__item <?php if (isRouteParentActive($item)) : ?>active<?php endif; ?>">
            <i class="ti ti-<?= $item['icon'] ?>"></i>
            <span class="sidebar__text"><?= $item['text'] ?></span>
          </span>

          <div class="sidebar__collapse-menu">
            <?php foreach ($item['name'] as $key => $value) : ?>
              <a href="<?= routeLink($value['name'], @$value['parameter'], null, @$value['module']) ?>" class="sidebar__collapse-item <?php if (isRouteActive($value)) : ?>active<?php endif; ?>">
                <span class="sidebar__text"><?= $key ?></span>
                <!-- <span class="label label_primary">2</span> -->
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      <?php else : ?>
        <a href="<?= routeLink($item['name'], @$item['parameter'], null, @$item['module']) ?>" class="sidebar__item <?php if (isRouteActive($item)) : ?>active<?php endif; ?>">
          <i class="ti ti-<?= $item['icon'] ?>"></i>
          <span class="sidebar__text"><?= $item['text'] ?></span>
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
