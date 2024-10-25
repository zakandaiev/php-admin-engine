<?php

$title = t('group.edit.title');

Page::set('title', $title);

Page::breadcrumb('add', t('group.list.title'), 'group.list');
Page::breadcrumb('add', $title);

$routeOptions = [];
foreach ($routes as $method => $r) {
  foreach ($r as $p) {
    $r = new \stdClass();

    $r->text = $p;
    $r->value = $method . '@' . $p;

    $routeOptions[$method][] = $r;
  }
}

foreach ($group->route as $addableRoute) {
  list($method, $path) = explode('@', $addableRoute);

  if (empty($method) || empty($path)) {
    continue;
  }

  $isAddableRouteAlreadyInArray = array_filter($routeOptions[$method], function ($routeOption) use ($addableRoute) {
    return $routeOption->value === $addableRoute;
  });

  if (!$isAddableRouteAlreadyInArray) {
    $r = new \stdClass();

    $r->text = $path;
    $r->value = $addableRoute;

    $routeOptions[$method][] = $r;
  }
}

$userOptions = array_map(function ($user) {
  $u = new \stdClass();

  // TODO
  // $u->text = $user->fullname;
  $u->text = $user->name;
  $u->value = $user->id;

  return $u;
}, $users);

$form = new BuilderForm([
  'action' => 'edit',
  'modelName' => 'Group',
  'itemId' => $group->id,
  'values' => $group,
  'attributes' => [
    // 'data-redirect="' . routeLink('group.list') . '"',
    'data-validate'
  ],
  'columns' => [
    'name' => [
      'label' => t('group.column.name'),
      'placeholder' => t('group.column.name_placeholder')
    ],
    'route' => [
      'label' => t('group.column.routes'),
      'placeholder' => t('group.column.routes_placeholder'),
      'data-addable' => '/(any|delete|get|options|patch|post|put)@\/[0-9a-z\/\*\$\-\_]+/g',
      'options' => $routeOptions
    ],
    'user_id' => [
      'label' => t('group.column.users'),
      'placeholder' => t('group.column.users_placeholder'),
      'options' => $userOptions
    ],
    'is_enabled' => [
      'label' => t('group.column.is_enabled'),
      'placeholder' => t('group.column.is_enabled_placeholder')
    ],
    'access_all' => [
      'label' => t('group.column.access_all'),
      'placeholder' => t('group.column.access_all_placeholder')
    ],
  ]
]);
?>

<?php Theme::header(); ?>

<?php Theme::template('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::template('navbar-top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <?php Theme::breadcrumb(); ?>

      <?= getFormBox('group', $title, $group, $form->renderHtml()) ?>

    </div>
  </section>

  <?php Theme::template('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
