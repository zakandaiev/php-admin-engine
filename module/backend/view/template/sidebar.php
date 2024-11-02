<?php

use engine\module\Hook;

$sidebar = Hook::getData('sidebar') ?? [];

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
  if (!isset($route['name']) || !is_string($route['name'])) {
    return false;
  }

  $result = routeIsActive($route['name'], @$route['parameter'], @$route['module']);
  if (!$result && is_array(@$route['activeRoutes'])) {
    foreach ($route['activeRoutes'] as $routeAlsoActive) {
      if ($routeAlsoActive === routeGet('name')) {
        return true;
      }
    }
  }

  return $result;
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

    $result = isRouteActive($item);

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
    <?php foreach ($sidebar as $route) : ?>
      <?php
      if (!@$route['isPublic'] && !checkRouteAccess(@$route['name'])) {
        continue;
      }
      ?>

      <?php if (@$route['isSeparator'] === true) : ?>
        <span data-id="<?= @$route['id'] ?>" class="sidebar__separator"><?= $route['text'] ?></span>
      <?php elseif (is_array($route['name'])) : ?>
        <div data-id="<?= @$route['id'] ?>" class="sidebar__collapse <?php if (isRouteParentActive($route)) : ?>active<?php endif; ?>">
          <span class="sidebar__item <?php if (isRouteParentActive($route)) : ?>active<?php endif; ?>">
            <i class="ti ti-<?= $route['icon'] ?>"></i>
            <span class="sidebar__text"><?= $route['text'] ?></span>
          </span>

          <div class="sidebar__collapse-menu">
            <?php foreach ($route['name'] as $routeInner) : ?>
              <a data-id="<?= @$routeInner['id'] ?>" href="<?= routeLink($routeInner['name'], @$routeInner['parameter'], @$routeInner['query'], @$routeInner['module']) ?>" class="sidebar__collapse-item <?php if (isRouteActive($routeInner)) : ?>active<?php endif; ?>">
                <span class="sidebar__text"><?= $routeInner['text'] ?></span>
                <!-- <span class="label label_primary">2</span> -->
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      <?php else : ?>
        <a data-id="<?= @$route['id'] ?>" href="<?= routeLink($route['name'], @$route['parameter'], @$value['query'], @$route['module']) ?>" class="sidebar__item <?php if (isRouteActive($route)) : ?>active<?php endif; ?>">
          <i class="ti ti-<?= $route['icon'] ?>"></i>
          <span class="sidebar__text"><?= $route['text'] ?></span>
          <?php
          if (false) :
            // TODO
            // if(
            // 	isset($route['label'])
            // 	&& (
            // 		isClosure($route['label'])
            // 		||
            // 		(!isClosure($route['label']) && !empty($route['label']))
            // 	)
            // ):
          ?>
            <span class="label label_<?php if (isset($route['label_color'])) : ?><?= $route['label_color'] ?><?php else : ?>primary<?php endif; ?>">
              <?php if (isClosure($route['label'])) : ?>
                <?= $route['label']() ?>
              <?php else : ?>
                <?= $route['label'] ?>
              <?php endif; ?>
            </span>
          <?php endif; ?>
        </a>
      <?php endif; ?>
    <?php endforeach; ?>
  </nav>
</aside>
