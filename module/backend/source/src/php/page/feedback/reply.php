<?php

use engine\Config;

$ipChecker = Config::getProperty('ipChecker', 'service');

$title = t('feedback.reply.title');

Page::set('title', $title);

Page::breadcrumb('add', t('feedback.list.title'), 'feedback.list');
Page::breadcrumb('add', $title);

$form = new BuilderForm([
  'action' => 'reply',
  'modelName' => 'Feedback',
  'itemId' => $feedback->id,
  'isMatchRequest' => true,
  // 'values' => $feedback,
  'attributes' => [
    // 'data-redirect="' . routeLink('feedback.list') . '"',
    'data-validate',
    'data-reset'
  ],
  'columns' => [
    'subject' => [
      'label' => t('feedback.column.subject.label'),
      'placeholder' => t('feedback.column.subject.placeholder')
    ],
    'message' => [
      'label' => t('feedback.column.message.label'),
      'placeholder' => t('feedback.column.message.placeholder')
    ]
  ],
  'submitButton' => t('feedback.reply.submit_button'),
  'submitError' => t('feedback.reply.submit_error'),
  'submitSuccess' => t('feedback.reply.submit_success')
]);
?>

<?php Theme::header(); ?>

<?php Theme::template('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::template('navbar-top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <?php Theme::breadcrumb(); ?>

      <div class="box mb-6">
        <div class="box__header">
          <h4 class="box__title d-block">
            <span><?= t('feedback.reply.from') ?>:</span>

            <a href="<?= $ipChecker($feedback->ip) ?>" target="_blank">
              <?php if (isset($feedback->user->fullname)): ?>
                <?= $feedback->user->fullname ?>
              <?php else: ?>
                <?= $feedback->email ?>
              <?php endif; ?>
            </a>
          </h4>

          <?php if (!empty($feedback->subject)): ?>
            <h5 class="box__subtitle">
              <span><?= t('feedback.column.subject.label') ?>:</span>

              <span><?= textWord($feedback->subject) ?></span>
            </h5>
          <?php endif; ?>
        </div>

        <div class="box__body"><?= textWord($feedback->message) ?></div>
      </div>

      <?= getFormBox('feedback', $title, $feedback, $form->renderHtml()) ?>

    </div>
  </section>

  <?php Theme::template('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
