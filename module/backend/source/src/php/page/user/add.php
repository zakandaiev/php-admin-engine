<?php

$title = t('user.add.title');

Page::set('title', $title);

Page::breadcrumb('add', t('user.list.title'), 'user.list');
Page::breadcrumb('add', $title);

$form = new BuilderForm([
  'action' => 'add',
  'modelName' => 'User',
  'attributes' => [
    'data-redirect="' . routeLink('user.edit') . '"',
    'data-validate'
  ],
  'columns' => [
    'group_id' => [
      'label' => t('user.column.group_id.label'),
      'placeholder' => t('user.column.group_id.placeholder')
    ],
    'email' => [
      'label' => t('user.column.email.label'),
      'placeholder' => t('user.column.email.placeholder'),
      'className' => 'col-xs-12 col-md-6 col-lg-3'
    ],
    'password' => [
      'type' => 'text',
      'label' => t('user.column.password.label'),
      'placeholder' => t('user.column.password.placeholder'),
      'className' => 'col-xs-12 col-md-6 col-lg-3'
    ],
    'name' => [
      'label' => t('user.column.name.label'),
      'placeholder' => t('user.column.name.placeholder'),
      'className' => 'col-xs-12 col-md-6 col-lg-3'
    ],
    'avatar' => [
      'label' => t('user.column.avatar.label'),
      'placeholder' => t('user.column.avatar.placeholder'),
      'className' => 'col-xs-12 col-md-6 col-lg-3'
    ],
    'setting' => [
      'label' => t('user.column.setting.label'),
      'placeholder' => t('user.column.setting.placeholder')
    ],
    'is_enabled' => [
      'label' => t('user.column.is_enabled.label'),
      'placeholder' => t('user.column.is_enabled.placeholder')
    ],
  ],
  'submitButton' => t('user.edit.submit_button'),
  'submitError' => t('user.edit.submit_error'),
  'submitSuccess' => t('user.edit.submit_success')
]);
?>

<?php Theme::header(); ?>

<?php Theme::template('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::template('navbar-top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <?php Theme::breadcrumb(); ?>

      <?= getFormBox('user', $title, null, $form->renderHtml()) ?>

    </div>
  </section>

  <?php Theme::template('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
