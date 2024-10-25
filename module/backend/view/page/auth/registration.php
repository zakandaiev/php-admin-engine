<?php

use module\backend\builder\Form;

$title = t('auth.registration.title');

Page::set('title', $title);

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
      'label' => t('auth.column.name'),
      'placeholder' => t('auth.column.name_placeholder')
    ],
    'email' => [
      'label' => t('auth.column.email'),
      'placeholder' => t('auth.column.email_placeholder')
    ],
    'password' => [
      'label' => t('auth.column.password'),
      'placeholder' => t('auth.column.password_placeholder')
    ],
  ],
  'submitText' => t('auth.registration.submit'),
  'submitClassName' => 'btn btn_primary btn_fit'
]);
?>

<?php Theme::header(); ?>

<main class="page-content__inner">

  <section class="section section_grow section_offset">
    <div class="container h-100 d-flex flex-column justify-content-center">

      <div class="row">
        <div class="col-xs-12 col-sm-10 col-md-6 col-lg-4 mx-auto">

          <div class="text-center">
            <?php if (!empty(site('logo'))) : ?>
              <img class="d-inline-block w-25 mb-2" src="<?= pathResolveUrl(null, site('logo')) ?>" data-src-dark="<?= pathResolveUrl(null, site('logo_alt')) ?>" alt="Logo">
            <?php else : ?>
              <h1 class="font-size-32 mb-2"><?= site('name') ?></h1>
            <?php endif; ?>

            <h4 class="color-text"><?= t('auth.registration.cta') ?></h4>

            <?php Theme::template('widget/lang-inline'); ?>
          </div>

          <div class="box mt-2">
            <div class="box__body">

              <?php if (requestHas('success')): ?>
                <h4 class="text-center"><?= t('auth.registration.success_title') ?></h4>
                <p class="text-center"><?= t('auth.registration.success_text') ?></p>
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

                  <div class="col text-uppercase d-flex align-items-center"><?= t('auth.registration.or') ?></div>

                  <div class="col fill">
                    <hr>
                  </div>
                </div> */ ?>

                <?php $form->render(); ?>
              <?php endif; ?>

            </div>
          </div>

          <div class="text-center mt-4">
            <a href="<?= routeLink('login') ?>"><?= t('auth.registration.login') ?></a>
          </div>

        </div>
      </div>

    </div>
  </section>

</main>

<?php Theme::footer(); ?>