<?php

return [
	'table' => 'group',
	'filter' => 'group',
	'form' => 'group/group',
	'form_toggle' => 'group/toggle',
	'title' => __('admin.group.groups'),
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
		'translations' => [
			'type' => function ($value, $item) {
				return getInterfaceTranslationsColumn('group', $value, $item);
			},
			'title' => __('admin.group.translations')
		],
		'count_routes' => [
			'type' => function ($value, $item) {
				if ($item->access_all) {
					return __('admin.group.access_all');
				}

				return $value;
			},
			'title' => __('admin.group.count_routes')
		],
		'count_users' => [
			'type' => function ($value, $item) {
				return '<a href="/admin/user">' . $value . '</a>';
			},
			'title' => __('admin.group.count_users')
		],
		'date_created' => [
			'type' => 'date_when',
			'format' => 'd.m.Y H:i',
			'title' => __('admin.group.date_created')
		],
		'is_enabled' => [
			'type' => function ($value, $item) {
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
			'type' => function ($value, $item) {
				$html = ' <a href="' . site('url_language') . '/admin/group/edit/' . $item->id . '" data-tooltip="top" title="' . __('admin.edit') . '" class="table__action"><i class="icon icon-edit"></i></a>';

				$html .= ' <button type="button" data-action="' . Form::delete('group/group', $item->id) . '" data-confirm="' . __('admin.group.delete_confirm', $item->name) . '" data-remove="trow" data-decrement=".pagination-output" data-tooltip="top" title="' . __('admin.delete') . '" class="table__action">';
				$html .= '<i class="icon icon-trash"></i>';
				$html .= '</button>';

				return $html;
			},
		]
	]
];
