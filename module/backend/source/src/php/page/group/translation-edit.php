<?php

$title = t('group.translation.title');

Page::set('title', $title);

Page::breadcrumb('add', t('group.list.title'), 'group.list');
Page::breadcrumb('add', t('group.edit.title'), 'group.edit', ['parameter' => ['id' => $group->id]]);
Page::breadcrumb('add', $title);

$form = new BuilderForm([
  'action' => 'edit',
  'modelName' => 'Group',
  'itemId' => $group->id,
  'isMatchRequest' => true,
  'values' => $group,
  'attributes' => [
    // 'data-redirect="' . routeLink('group.list') . '"',
    'data-validate'
  ],
  'columns' => [
    'name' => [
      'label' => t('group.column.name.label'),
      'placeholder' => t('group.column.name.placeholder')
    ],
    'language' => [
      'type' => 'hidden'
    ],
  ],
  'submitButton' => t('group.translation.submit_button'),
  'submitError' => t('group.translation.submit_error'),
  'submitSuccess' => t('group.translation.submit_success')
]);

$titleNice = '<img class="d-inline-block w-1em h-1em vertical-align-middle radius-round" src="' . pathResolveUrl(Asset::url(), lang('icon', $group->language)) . '" alt="' . $group->language . '">&nbsp;' . $title;
?>

<?php Theme::header(); ?>

<?php Theme::template('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::template('navbar-top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <?php Theme::breadcrumb(); ?>

      <?= getFormBox('group', $titleNice, $group, $form->renderHtml()) ?>

    </div>
  </section>

  <?php Theme::template('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
