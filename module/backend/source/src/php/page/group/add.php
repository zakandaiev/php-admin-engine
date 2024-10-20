<?php

use module\backend\builder\Form;

$title = t('group.add.title');

Page::set('title', $title);

Page::breadcrumb('add', t('group.list.title'), 'group-list');
Page::breadcrumb('add', $title);


$routesFormatted = [];
foreach ($routes as $method => $r) {
  foreach ($r as $p) {
    $r = new \stdClass();

    $r->text = $p;
    $r->value = $method . '@' . $p;

    $routesFormatted[$method][] = $r;
  }
}

$usersFormatted = array_map(function ($user) {
  $u = new \stdClass();

  // TODO
  // $u->text = $user->fullname;
  $u->text = $user->name;
  $u->value = $user->id;

  return $u;
}, $users);

$form = new Form([
  'modelName' => 'Group',
  'action' => 'add',
  'title' => $title,
  'attributes' => [
    'data-redirect="' . routeLink('group-list') . '"',
    'data-validate'
  ],
  'columns' => [
    'name' => [
      'label' => t('group.add.name'),
      'placeholder' => t('group.add.name_placeholder')
    ],
    'routes' => [
      'label' => t('group.add.routes'),
      'placeholder' => t('group.add.routes_placeholder'),
      'options' => $routesFormatted,
      'data-addable' => '/(any|delete|get|options|patch|post|put)@\/[0-9a-z\/\*\$\-\_]+/g'
    ],
    'users' => [
      'label' => t('group.add.users'),
      'placeholder' => t('group.add.users_placeholder'),
      'options' => $usersFormatted
    ],
    'is_enabled' => [
      'label' => t('group.add.is_enabled'),
      'placeholder' => t('group.add.is_enabled_placeholder')
    ],
    'access_all' => [
      'label' => t('group.add.access_all_placeholder'),
      'placeholder' => t('group.add.access_all_placeholder')
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

      <?php $form->render(); ?>

    </div>
  </section>

  <?php Theme::template('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
