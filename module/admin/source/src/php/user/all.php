<?php

$title = __('admin.user.users');

Page::set('title', $title);

Page::breadcrumb('add', ['name' => $title]);

$interface_builder = new InterfaceBuilder([
	'filter' => 'user',
	'title' => $title,
	'actions' => [
		['name' => __('admin.user.add_user'), 'url' => '/admin/user/add']
	],
	'fields' => [
		'id' => [
			'type' => 'text',
			'title' => 'ID'
		],
		'name' => [
			'type' => function ($value, $item) {
				return $item->fullname;
			},
			'title' => __('admin.user.name')
		],
		'count_groups' => [
			'type' => function ($value, $item) {
				return '<a href="/admin/group">' . $value . '</a>';
			},
			'title' => __('admin.user.count_groups')
		],
		'date_created' => [
			'type' => 'date_when',
			'format' => 'd.m.Y H:i',
			'title' => __('admin.user.date_created')
		],
		'auth_date' => [
			'type' => function ($value, $item) {
				if (!empty($value) && $item->auth_ip) {
					return '<a href="' . sprintf(SERVICE['ip_checker'], $item->auth_ip) . '" target="_blank">' . date_when($value, 'd.m.Y H:i') . '</a>';
				}
				return '<i class="icon icon-minus"></i>';
			},
			'title' => __('admin.user.auth_date')
		],
		'is_enabled' => [
			'type' => function ($value, $item) {
				$tooltip = $item->is_enabled ? __('admin.user.deactivate_this_user') : __('admin.user.activate_this_user');

				$html = '<button type="button" data-action="' . Form::edit('user/toggle', $item->id) . '" data-fields="is_enabled:' . !$value . '" data-redirect="this" data-tooltip="top" title="' . $tooltip . '" class="table__action">';
				$html .= icon_boolean($value);
				$html .= '</button>';

				return $html;
			},
			'title' => __('admin.user.is_enabled')
		],
		'table_actions' => [
			'td_class' => 'table__actions',
			'type' => function ($value, $item) {
				$html = '<a href="' . site('url_language') . '/admin/profile/' . $item->id . '" data-tooltip="top" title="' . __('admin.view') . '" class="table__action"><i class="icon icon-eye"></i></a>';

				$html .= ' <a href="' . site('url_language') . '/admin/user/edit/' . $item->id . '" data-tooltip="top" title="' . __('admin.edit') . '" class="table__action"><i class="icon icon-edit"></i></a>';

				$html .= ' <button type="button" data-action="' . Form::delete('user/edit', $item->id) . '" data-confirm="' . __('admin.user.delete_confirm', $item->fullname) . '" data-remove="trow" data-decrement=".pagination-output" data-tooltip="top" title="' . __('admin.delete') . '" class="table__action">';
				$html .= '<i class="icon icon-trash"></i>';
				$html .= '</button>';

				return $html;
			},
		]
	],
	'data' => $users,
	'placeholder' => __('admin.user.there_are_no_users_yet')
]);
?>

<?php Theme::header(); ?>

<?php Theme::block('sidebar'); ?>

<main class="page-content__inner">

	<?php Theme::block('navbar-top'); ?>

	<section class="section section_grow section_offset">
		<div class="container-fluid">

			<?php Theme::breadcrumb(); ?>

			<?= $interface_builder->render() ?>

		</div>
	</section>

	<?php Theme::block('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
