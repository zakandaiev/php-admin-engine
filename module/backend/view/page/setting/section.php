<?php

use engine\module\Hook;

$title = t('setting.' . $section . '.title');

Page::set('title', $title);
Page::breadcrumb('add', $title);

$columnHookData = Hook::getData('setting.column') ?? [];

$columns = [];
foreach (settingGet($section) as $key => $value) {
  $columns[$key] = [
    'label' => $columnHookData[$key]['label'] ?? t('setting.column.' . $key . '.label'),
    'placeholder' => $columnHookData[$key]['placeholder'] ?? t('setting.column.' . $key . '.placeholder'),
    'value' => site($key, @$columnHookData[$key]['module'] ?? $section)
  ];

  if (in_array($key, ['favicon', 'logo', 'logo_alt', 'placeholder_avatar', 'placeholder_image'])) {
    $columns[$key]['className'] = 'col-xs-12 col-md-6 col-lg-3';
  }
}

if (isset($columns['language'])) {
  $columns['language']['options'] = function () {
    return array_map(function ($language) {
      $l = new \stdClass();

      $l->text = $language['key'] . '_' . $language['region'] . ' - ' . t('i18n.' . $language['key']);
      $l->value = $language['key'];

      return $l;
    }, site('languages'));
  };
}

$form = new BuilderForm([
  'action' => 'editSection',
  'modelName' => 'Setting',
  'itemId' => $section,
  'isMatchRequest' => true,
  'attributes' => [
    'data-validate'
  ],
  'columns' => $columns,
  'submitButton' => t('setting.' . $section . '.submit_button'),
  'submitError' => t('setting.' . $section . '.submit_error'),
  'submitSuccess' => t('setting.' . $section . '.submit_success')
]);
?>

<?php Theme::header(); ?>

<?php Theme::template('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::template('navbar-top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <?php Theme::breadcrumb(); ?>

      <?= getFormBox('setting', $title, null, $form->renderHtml()) ?>

    </div>
  </section>

  <?php Theme::template('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
