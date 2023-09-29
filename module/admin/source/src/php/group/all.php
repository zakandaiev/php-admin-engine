<?php

$title = __('admin.group.groups');

Page::set('title', $title);

Page::breadcrumb('add', ['name' => $title]);

$interface_builder = new InterfaceBuilder([
	'filter' => 'group',
	'title' => $title,
	'actions' => [
		['name' => __('admin.group.add_group'), 'url' => '/admin/group/add']
	],
	'fields' => [
		'id' => [
			'type' => 'text',
			'title' => 'ID'
		],
		'name' => [
			'type' => 'text',
			'title' => __('admin.group.name')
		],
		'count_routes' => [
			'type' => function($value, $item) {
				if($item->access_all) {
					return __('admin.group.access_all');
				}

				return $value;
			},
			'title' => __('admin.group.count_routes')
		],
		'count_users' => [
			'type' => function($value, $item) {return '<a href="/admin/user">' . $value . '</a>';},
			'title' => __('admin.group.count_users')
		],
		'date_created' => [
			'type' => 'date_when',
			'format' => 'd.m.Y H:i',
			'title' => __('admin.group.date_created')
		],
		'is_enabled' => [
			'type' => function($value, $item) {
				$tooltip = $item->is_enabled ? __('admin.group.deactivate_this_group') : __('admin.group.activate_this_group');

				$html = '<button type="button" data-action="' . Form::edit('group/toggle', $item->id) . '" data-fields="is_enabled:' . !$value . '" data-redirect="this" data-tooltip="top" title="' . $tooltip . '" class="table__action">';
				$html .= icon_boolean($value);
				$html .= '</button>';

				return $html;
			},
			'title' => __('admin.group.is_enabled')
		],
		'table_actions' => [
			'td_class' => 'table__actions',
			'type' => function($value, $item) {
				$html = '<a href="' . site('url_language') . '/admin/profile/' . $item->id .'" data-tooltip="top" title="' . __('admin.view') . '" class="table__action"><i class="icon icon-eye"></i></a>';

				$html .= ' <a href="' . site('url_language') . '/admin/group/edit/' . $item->id .'" data-tooltip="top" title="' . __('admin.edit') . '" class="table__action"><i class="icon icon-edit"></i></a>';

				$html .= ' <button type="button" data-action="' . Form::delete('group/edit', $item->id) . '" data-confirm="' . __('admin.group.delete_confirm', $item->name) . '" data-remove="trow" data-decrement=".pagination-output" data-tooltip="top" title="' . __('admin.delete') . '" class="table__action">';
				$html .= '<i class="icon icon-trash"></i>';
				$html .= '</button>';

				return $html;
			},
		]
	],
	'data' => $groups,
	'placeholder' => __('admin.group.there_are_no_groups_yet')
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
