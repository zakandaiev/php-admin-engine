<?php

use module\backend\builder\Form;

$title = t('auth.login.title');

Page::set('title', $title);

$form = new Form([
  'action' => 'login',
  'modelName' => 'Auth',
  'isMatchRequest' => true,
  'attributes' => [
    'data-redirect="' . routeLink('dashboard') . '"',
    'data-validate'
  ],
  'columns' => [
    'email' => [
      'label' => t('auth.column.email'),
      'placeholder' => t('auth.column.email_placeholder')
    ],
    'password' => [
      'label' => t('auth.column.password'),
      'placeholder' => t('auth.column.password_placeholder')
    ],
  ],
  'submitText' => t('auth.login.submit'),
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

            <h4 class="color-text"><?= t('auth.login.cta') ?></h4>

            <?php Theme::template('widget/lang-inline'); ?>
          </div>

          <div class="box mt-2">
            <div class="box__body">

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

            </div>
          </div>

          <div class="d-flex flex-column gap-1 text-center mt-4">
            <a href="<?= routeLink('registration') ?>"><?= t('auth.login.registration') ?></a>
            <a href="<?= routeLink('reset-password') ?>"><?= t('auth.login.reset_password') ?></a>
          </div>

        </div>
      </div>

    </div>
  </section>

</main>

<?php Theme::footer(); ?>
