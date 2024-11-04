<?php

use engine\Config;

$ipChecker = Config::getProperty('ipChecker', 'service');

$title = t('user.list.title');

Page::set('title', $title);

Page::breadcrumb('add', $title);

$table = new BuilderTable([
  // 'filter' => 'User', TODO filter from model like Form
  'title' => $title,
  'data' => $users,
  'placeholder' => t('user.list.placeholder'),
  'actions' => [
    ['name' => t('user.list.add'), 'url' => routeLink('user.add')]
  ],
  'columns' => [
    'fullname' => [
      'type' => 'text',
      'title' => t('user.column.name.label')
    ],
    'count_groups' => [
      'type' => 'text',
      'title' => t('user.column.count_groups.label')
    ],
    'date_created' => [
      'type' => 'dateWhen',
      'format' => 'd.m.Y H:i',
      'title' => t('user.column.date_created.label')
    ],
    'auth_date' => [
      'type' => function ($value, $item) use ($ipChecker) {
        if (empty($value)) {
          return '<i class="ti ti-minus"></i>';
        }

        return '<a href="' . $ipChecker($item->auth_ip) . '" target="_blank"><i class="ti ti-map-pin"></i> ' . dateWhen($value, 'd.m.Y H:i', true) . '</a>';
      },
      'title' => t('user.column.auth_date.label')
    ],
    'is_enabled' => [
      'type' => function ($value, $item) {
        return getColumnToggle('user', 'is_enabled', $value, $item);
      },
      'title' => t('user.column.is_enabled.label')
    ],
    'table_actions' => [
      'tdClassName' => 'table__actions',
      'type' => function ($value, $item) {
        return getColumnActions('user', $value, $item);
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
