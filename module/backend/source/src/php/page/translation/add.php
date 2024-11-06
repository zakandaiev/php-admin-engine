<?php

$title = t('translation.add.title');

Page::set('title', $title);

Page::breadcrumb('add', t('translation.list.title'), 'translation.list');
Page::breadcrumb('add', $title);

$form = new BuilderForm([
  'action' => 'add',
  'modelName' => 'Translation',
  'isMatchRequest' => true,
  'attributes' => [
    'data-redirect="' . routeLink('translation.edit') . '"',
    'data-validate'
  ],
  'columns' => [
    'module' => [
      'label' => t('translation.column.module.label'),
      'placeholder' => t('translation.column.module.placeholder')
    ],
    'language' => [
      'label' => t('translation.column.language.label'),
      'placeholder' => t('translation.column.language.placeholder')
    ],
    'region' => [
      'label' => t('translation.column.region.label'),
      'placeholder' => t('translation.column.region.placeholder')
    ],
    'icon' => [
      'label' => t('translation.column.icon.label'),
      'placeholder' => t('translation.column.icon.placeholder')
    ]
  ],
  'submitButton' => t('translation.edit.submit_button'),
  'submitError' => t('translation.edit.submit_error'),
  'submitSuccess' => t('translation.edit.submit_success')
]);
?>

<?php Theme::header(); ?>

<?php Theme::template('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::template('navbar-top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <?php Theme::breadcrumb(); ?>

      <?= getFormBox('translation', $title, null, $form->renderHtml()) ?>

    </div>
  </section>

  <?php Theme::template('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
