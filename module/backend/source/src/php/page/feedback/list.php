<?php

use engine\Config;

$ipChecker = Config::getProperty('ipChecker', 'service');

$title = t('feedback.list.title');

Page::set('title', $title);

Page::breadcrumb('add', $title);

$table = new BuilderTable([
  // 'filter' => 'Feedback', TODO filter from model like Form
  'title' => $title,
  'data' => $feedbacks,
  'placeholder' => t('feedback.list.placeholder'),
  'columns' => [
    'user_id' => [
      'type' => function ($value, $item) use ($ipChecker) {
        $output = '';

        if (isset($item->user->fullname)) {
          $output .= '<b>' . t('feedback.column.user_id.label') . '</b>: ' . $item->user->fullname . '<br>';
        }
        $output .= '<b>' . t('feedback.column.email.label') . '</b>: ' . $item->email . '<br>';
        $output .= '<b>' . t('feedback.column.ip.label') . '</b>: ' . '<a href="' . $ipChecker($item->ip) . '" target="_blank">' . $item->ip . '</a>';

        return $output;
      },
      'title' => t('feedback.column.user_id.label')
    ],
    'message' => [
      'type' => function ($value, $item) {
        $output = '';

        if (!empty($item->subject)) {
          $output .= '<b>' . t('feedback.column.subject.label') . '</b>: ' . textHtml(textExcerpt($item->subject, 50)) . '<br>';
          $output .= '<b>' . t('feedback.column.message.label') . '</b>: ';
        }

        $output .= textHtml(textExcerpt($item->message, 50));

        return $output;
      },
      'title' => t('feedback.column.message.label')
    ],
    'date_created' => [
      'type' => 'dateWhen',
      'format' => 'd.m.Y H:i',
      'title' => t('feedback.column.date_created.label')
    ],
    'is_read' => [
      'type' => function ($value, $item) {
        return getColumnToggle('feedback', 'is_read', $value, $item, 'read', 'unread');
      },
      'title' => t('feedback.column.is_read.label')
    ],
    'is_replied' => [
      'type' => function ($value, $item) {
        return getColumnToggle('feedback', 'is_replied', $value, $item, 'reply', 'unreply');
      },
      'title' => t('feedback.column.is_replied.label')
    ],
    'table_actions' => [
      'tdClassName' => 'table__actions',
      'type' => function ($value, $item) {
        return getColumnActions('feedback', $value, $item, ['reply@message', 'delete@trash']);
      },
    ]
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

      <?php $table->render(); ?>

    </div>
  </section>

  <?php Theme::template('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
