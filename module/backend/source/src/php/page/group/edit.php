<?php

$title = t('group.edit.title');

Page::set('title', $title);

Page::breadcrumb('add', t('group.list.title'), 'group.list');
Page::breadcrumb('add', $title);

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
      'data-addable' => '/(any|delete|get|options|patch|post|put)@\/[0-9a-z\/\*\$\-\_]+/g'
    ],
    'user_id' => [
      'label' => t('group.column.users'),
      'placeholder' => t('group.column.users_placeholder')
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
