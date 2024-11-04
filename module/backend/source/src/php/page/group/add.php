<?php

$title = t('group.add.title');

Page::set('title', $title);

Page::breadcrumb('add', t('group.list.title'), 'group.list');
Page::breadcrumb('add', $title);

$form = new BuilderForm([
  'action' => 'add',
  'modelName' => 'Group',
  'attributes' => [
    'data-redirect="' . routeLink('group.edit') . '"',
    'data-validate'
  ],
  'columns' => [
    'name' => [
      'label' => t('group.column.name.label'),
      'placeholder' => t('group.column.name.placeholder')
    ],
    'route' => [
      'label' => t('group.column.route.label'),
      'placeholder' => t('group.column.route.placeholder'),
      'data-addable' => '/(any|delete|get|options|patch|post|put)@\/[0-9a-z\/\*\$\-\_]+/g'
    ],
    'user_id' => [
      'label' => t('group.column.user_id.label'),
      'placeholder' => t('group.column.user_id.placeholder')
    ],
    'is_enabled' => [
      'label' => t('group.column.is_enabled.label'),
      'placeholder' => t('group.column.is_enabled.placeholder')
    ],
    'access_all' => [
      'label' => t('group.column.access_all.label'),
      'placeholder' => t('group.column.access_all.placeholder')
    ],
  ],
  'submitButton' => t('group.edit.submit_button'),
  'submitError' => t('group.edit.submit_error'),
  'submitSuccess' => t('group.edit.submit_success')
]);
?>

<?php Theme::header(); ?>

<?php Theme::template('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::template('navbar-top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <?php Theme::breadcrumb(); ?>

      <?= getFormBox('group', $title, null, $form->renderHtml()) ?>

    </div>
  </section>

  <?php Theme::template('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
