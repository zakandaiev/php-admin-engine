<?php

$title = t('user.edit.title');

Page::set('title', $title);

Page::breadcrumb('add', t('user.list.title'), 'user.list');
Page::breadcrumb('add', $title);

$groupOptions = array_map(function ($group) {
  $u = new \stdClass();

  $u->text = $group->name;
  $u->value = $group->id;

  return $u;
}, $groups);

$form = new BuilderForm([
  'action' => 'edit',
  'modelName' => 'User',
  'itemId' => $user->id,
  'values' => $user,
  'attributes' => [
    // 'data-redirect="' . routeLink('user.list') . '"',
    'data-validate'
  ],
  'columns' => [
    'group_id' => [
      'label' => t('user.column.groups'),
      'placeholder' => t('user.column.groups_placeholder'),
      'options' => $groupOptions
    ],
    'email' => [
      'label' => t('user.column.email'),
      'placeholder' => t('user.column.email_placeholder'),
      'className' => 'col-xs-12 col-md-6 col-lg-3'
    ],
    'password' => [
      'type' => 'text',
      'required' => false,
      'value' => '',
      'label' => t('user.column.password'),
      'placeholder' => t('user.column.password_placeholder'),
      'className' => 'col-xs-12 col-md-6 col-lg-3'
    ],
    'name' => [
      'label' => t('user.column.name'),
      'placeholder' => t('user.column.name_placeholder'),
      'className' => 'col-xs-12 col-md-6 col-lg-3'
    ],
    'avatar' => [
      'label' => t('user.column.avatar'),
      'placeholder' => t('user.column.avatar_placeholder'),
      'className' => 'col-xs-12 col-md-6 col-lg-3'
    ],
    'setting' => [
      'label' => t('user.column.setting'),
      'placeholder' => t('user.column.setting_placeholder')
    ],
    'is_enabled' => [
      'label' => t('user.column.is_enabled'),
      'placeholder' => t('user.column.is_enabled_placeholder')
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

      <?= getFormBox('user', $title, $user, $form->renderHtml()) ?>

    </div>
  </section>

  <?php Theme::template('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
