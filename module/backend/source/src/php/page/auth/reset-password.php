<?php

use module\backend\builder\Form;

$title = t('auth.restore.title');
Page::set('title', $title);

$logoImage = site('logo') ?? site('logo_alt');

$form = new Form([
  'action' => 'restore',
  'modelName' => 'Auth',
  'isMatchRequest' => true,
  'attributes' => [
    'data-redirect="' . routeChangeQuery(['success' => true]) . '"',
    'data-validate'
  ],
  'columns' => [
    'email' => [
      'label' => t('auth.column.email'),
      'placeholder' => t('auth.column.email_placeholder')
    ],
  ],
  'submitButton' => t('auth.restore.submit'),
  'submitButtonClass' => 'btn btn_primary btn_fit'
]);
?>

<?php Theme::header(); ?>

<main class="page-content__inner">

  <section class="section section_grow section_offset">
    <div class="container h-100 d-flex flex-column justify-content-center">

      <div class="row">
        <div class="col-xs-12 col-sm-10 col-md-6 col-lg-4 mx-auto">

          <div class="text-center">
            <?php if (!empty($logoImage)) : ?>
              <img class="d-inline-block h-8rem mb-2" src="<?= pathResolveUrl(null, $logoImage) ?>" alt="Logo">
            <?php else : ?>
              <h1 class="font-size-32 mb-2"><?= site('name') ?></h1>
            <?php endif; ?>

            <h4 class="color-text"><?= t('auth.restore.cta') ?></h4>

            <?php Theme::template('widget/lang-inline'); ?>
          </div>

          <div class="box mt-2">
            <div class="box__body">

              <?php if (requestHas('success')): ?>
                <h4 class="text-center"><?= t('auth.restore.success_title') ?></h4>
                <p class="text-center"><?= t('auth.restore.success_text') ?></p>
              <?php else: ?>
                <?php $form->render(); ?>
              <?php endif; ?>

            </div>
          </div>

          <div class="text-center mt-4">
            <a href="<?= routeLink('login') ?>"><?= t('auth.restore.back_to_login') ?></a>
          </div>

        </div>
      </div>

    </div>
  </section>

</main>

<?php Theme::footer(); ?>
