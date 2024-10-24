<?php

$title = t('group.list.title');

Page::set('title', $title);

Page::breadcrumb('add', $title);

$table = new BuilderTable([
  // 'filter' => 'Group', TODO filter from model like Form
  'title' => $title,
  'data' => $groups,
  'placeholder' => t('group.page.list.placeholder'),
  'actions' => [
    ['name' => t('group.list.add'), 'url' => routeLink('group.add')]
  ],
  'columns' => [
    'name' => [
      'type' => 'text',
      'title' => t('group.column.name')
    ],
    'translations' => [
      'type' => function ($value, $item) {
        $value = !empty($value) ? explode(',', $value) : [];

        return getColumnTranslations('group', $item->language, $value, ['id' => $item->id]);
      },
      'title' => t('group.column.translations')
    ],
    'count_routes' => [
      'type' => function ($value, $item) {
        if ($item->access_all) {
          return t('group.column.access_all');
        }

        return $value;
      },
      'title' => t('group.column.count_routes')
    ],
    'count_users' => [
      'type' => 'text',
      'title' => t('group.column.count_users')
    ],
    'date_created' => [
      'type' => 'dateWhen',
      'format' => 'd.m.Y H:i',
      'title' => t('group.column.date_created')
    ],
    'is_enabled' => [
      'type' => function ($value, $item) {
        $tooltip = $item->is_enabled ? t('group.list.deactivate_this_group') : t('group.list.activate_this_group');

        $html = '<button type="button" data-action="' . Form::edit('group', $item->id, true) . '" data-body="' . textHtml(json_encode(['is_enabled' => !$value])) . '" data-redirect="this" data-tooltip="top" title="' . $tooltip . '" class="table__action">';
        $html .= iconBoolean($value);
        $html .= '</button>';

        return $html;
      },
      'title' => t('group.column.is_enabled')
    ],
    'table_actions' => [
      'tdClassName' => 'table__actions',
      'type' => function ($value, $item) {
        $html = ' <a href="' . routeLink('group.edit', ['id' => $item->id]) . '" data-tooltip="top" title="' . t('group.list.edit') . '" class="table__action"><i class="ti ti-edit"></i></a>';

        $html .= ' <button type="button" data-action="' . Form::delete('group', $item->id, true) . '" data-confirm="' . t('group.list.delete_confirm', $item->name) . '" data-remove="trow" data-decrement=".pagination-output > span" data-tooltip="top" title="' . t('group.list.delete') . '" class="table__action">';
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
