<?php

$title = t('translation.edit.title');

Page::set('title', $title);

Page::breadcrumb('add', t('translation.list.title'), 'translation.list');
Page::breadcrumb('add', $title);

$form = new BuilderForm([
  'action' => 'edit',
  'modelName' => 'Translation',
  'itemId' => "$module@$language-$region",
  'isMatchRequest' => true,
  'attributes' => [
    // 'data-redirect="' . routeLink('translation.list') . '"',
    'data-validate'
  ],
  'columns' => [
    'translation' => [
      'label' => t('translation.column.translation.label'),
      'placeholder' => t('translation.column.translation.placeholder'),
      'value' => $translation
    ],
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