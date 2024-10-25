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
  'placeholder' => t('user.page.list.placeholder'),
  'actions' => [
    ['name' => t('user.list.add'), 'url' => routeLink('user.add')]
  ],
  'columns' => [
    'name' => [
      'type' => 'text',
      'title' => t('user.column.name')
    ],
    'count_groups' => [
      'type' => 'text',
      'title' => t('user.column.count_groups')
    ],
    'date_created' => [
      'type' => 'dateWhen',
      'format' => 'd.m.Y H:i',
      'title' => t('user.column.date_created')
    ],
    'auth_date' => [
      'type' => function ($value, $item) use ($ipChecker) {
        if (empty($value)) {
          return '<i class="ti ti-minus"></i>';
        }

        return '<a href="' . $ipChecker($item->auth_ip) . '" target="_blank"><i class="ti ti-map-pin"></i> ' . dateWhen($value, 'd.m.Y H:i') . '</a>';
      },
      'title' => t('user.column.auth_date')
    ],
    'is_enabled' => [
      'type' => function ($value, $item) {
        $tooltip = $item->is_enabled ? t('user.list.deactivate_this_user') : t('user.list.activate_this_user');

        $html = '<button type="button" data-action="' . Form::edit('user', $item->id, true) . '" data-body="' . textHtml(json_encode(['is_enabled' => !$value])) . '" data-redirect="this" data-tooltip="top" title="' . $tooltip . '" class="table__action">';
        $html .= iconBoolean($value);
        $html .= '</button>';

        return $html;
      },
      'title' => t('user.column.is_enabled')
    ],
    'table_actions' => [
      'tdClassName' => 'table__actions',
      'type' => function ($value, $item) {
        $html = ' <a href="' . routeLink('user.edit', ['id' => $item->id]) . '" data-tooltip="top" title="' . t('user.list.edit') . '" class="table__action"><i class="ti ti-edit"></i></a>';

        $html .= ' <button type="button" data-action="' . Form::delete('user', $item->id, true) . '" data-confirm="' . t('user.list.delete_confirm', $item->name) . '" data-remove="trow" data-decrement=".pagination-output > span" data-tooltip="top" title="' . t('user.list.delete') . '" class="table__action">';
        $html .= '<i class="ti ti-trash"></i>';
        $html .= '</button>';

        return $html;
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
