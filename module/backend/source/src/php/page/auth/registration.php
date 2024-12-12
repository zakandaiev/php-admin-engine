<?php

use module\backend\builder\Form;

$title = t('auth.register.title');
Page::set('title', $title);

$logoImage = site('logo') ?? site('logo_alt');

$form = new Form([
  'action' => 'register',
  'modelName' => 'Auth',
  // 'isMatchRequest' => true,
  'attributes' => [
    'data-redirect="' . routeChangeQuery(['success' => true]) . '"',
    'data-validate'
  ],
  'columns' => [
    'name' => [
      'label' => t('auth.column.name.label'),
      'placeholder' => t('auth.column.name.placeholder')
    ],
    'email' => [
      'label' => t('auth.column.email.label'),
      'placeholder' => t('auth.column.email.placeholder')
    ],
    'password' => [
      'label' => t('auth.column.password.label'),
      'placeholder' => t('auth.column.password.placeholder')
    ],
  ],
  'submitButton' => t('auth.register.submit_button'),
  'submitError' => t('auth.register.submit_error'),
  'submitSuccess' => t('auth.register.submit_success'),
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

            <h4 class="color-text"><?= t('auth.register.cta') ?></h4>

            <?php Theme::template('widget/lang-inline'); ?>
          </div>

          <div class="box mt-2">
            <div class="box__body">

              <?php if (requestHas('success')): ?>
                <h4 class="text-center"><?= t('auth.register.success_title') ?></h4>
                <p class="text-center"><?= t('auth.register.success_text') ?></p>
              <?php else: ?>
                <?php /* <div class="d-flex flex-column gap-2 mb-3">
                  <button class="btn btn_google"><i class="ti ti-brand-google"></i> <?= t('auth.login_with_google') ?></button>

                  <button class="btn btn_facebook"><i class="ti ti-brand-facebook"></i> <?= t('auth.login_with_facebook') ?></button>

                  <button class="btn btn_apple"><i class="ti ti-brand-apple"></i> <?= t('auth.login_with_apple') ?></button>
                </div>

                <div class="row gap-xs">
                  <div class="col fill">
                    <hr>
                  </div>

                  <div class="col text-uppercase d-flex align-items-center"><?= t('auth.register.or') ?></div>

                  <div class="col fill">
                    <hr>
                  </div>
                </div> */ ?>

                <?php $form->render(); ?>
              <?php endif; ?>

            </div>
          </div>

          <div class="d-flex flex-column align-items-center gap-1 text-center mt-4">
            <a href="<?= routeLink('login') ?>"><?= t('auth.register.login') ?></a>
          </div>

        </div>
      </div>

    </div>
  </section>

</main>

<?php Theme::footer(); ?>
