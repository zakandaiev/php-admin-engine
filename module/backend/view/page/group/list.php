<?php

use module\backend\builder\Table;

$title = t('group.list.title');

Page::set('title', $title);

Page::breadcrumb('add', $title);

$table = new Table([
  'filter' => 'group',
  'title' => $title,
  'data' => $groups,
  'placeholder' => t('group.list.placeholder'),
  'actions' => [
    ['name' => t('group.list.add'), 'url' => Route::link('group-add')]
  ],
  'fields' => [
    'id' => [
      'type' => 'text',
      'title' => 'ID'
    ],
    'name' => [
      'type' => 'text',
      'title' => t('group.list.name')
    ],
    'translations' => [
      'type' => function ($value, $item) {
        return getInterfaceTranslationsColumn('group', $value, $item);
      },
      'title' => t('group.list.translations')
    ],
    'count_routes' => [
      'type' => function ($value, $item) {
        if ($item->access_all) {
          return t('group.list.access_all');
        }

        return $value;
      },
      'title' => t('group.list.count_routes')
    ],
    'count_users' => [
      'type' => function ($value, $item) {
        return '<a href="/admin/user">' . $value . '</a>';
      },
      'title' => t('group.list.count_users')
    ],
    'date_created' => [
      'type' => 'dateWhen',
      'format' => 'd.m.Y H:i',
      'title' => t('group.list.date_created')
    ],
    // 'is_enabled' => [
    //   'type' => function ($value, $item) {
    //     $tooltip = $item->is_enabled ? t('group.list.deactivate_this_group') : t('group.list.activate_this_group');

    //     $html = '<button type="button" data-action="' . Form::edit('group/toggle', $item->id) . '" data-fields="is_enabled:' . !$value . '" data-redirect="this" data-tooltip="top" title="' . $tooltip . '" class="table__action">';
    //     $html .= iconBoolean($value);
    //     $html .= '</button>';

    //     return $html;
    //   },
    //   'title' => t('group.list.is_enabled')
    // ],
    // 'table_actions' => [
    //   'td_class' => 'table__actions',
    //   'type' => function ($value, $item) {
    //     $html = ' <a href="' . site('url_language') . '/admin/group/edit/' . $item->id . '" data-tooltip="top" title="' . t('edit') . '" class="table__action"><i class="ti ti-edit"></i></a>';

    //     $html .= ' <button type="button" data-action="' . Form::delete('group/group', $item->id) . '" data-confirm="' . t('group.list.delete_confirm', $item->name) . '" data-remove="trow" data-decrement=".pagination-output" data-tooltip="top" title="' . t('delete') . '" class="table__action">';
    //     $html .= '<i class="ti ti-trash"></i>';
    //     $html .= '</button>';

    //     return $html;
    //   },
    // ]
  ]
]);
?>

<?php Theme::header(); ?>

<?php Theme::template('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::template('navbar-top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <form action="<?= Form::add('Group') ?>" method="POST">
        <input type="text" name="csrf_token" value="<?= \engine\http\Request::csrfToken() ?>">

        <input type="text" name="name" required>

        <label class="switch">
          <input type="hidden" name="is_enabled" value="false">
          <input type="checkbox" name="is_enabled" value="true">
          <span class="switch__slider"></span>
        </label>

        <input type="submit">
      </form>

      <?php Theme::breadcrumb(); ?>

      <?php $table->render(); ?>

    </div>
  </section>

  <?php Theme::template('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
