<?php

require Path::file('form') . '/_model/Page.php';

return [
	'table' => 'page',
	'fields' => [
		'is_enabled' => $is_enabled
	],
	'execute_pre' => function($fields, $data) {
		$sql = '
			SELECT
				page_id,
				(SELECT count(*) FROM {page_category} WHERE page_id = t_pc.page_id) as count_parent_categories
			FROM
				{page_category} t_pc
			WHERE
				category_id = :category_id
		';

		$children = new Statement($sql);
		
		$children = $children->execute(['category_id' => $data['item_id']])->fetchAll();

		foreach($children as $child) {
			if($child->count_parent_categories > 1) {
				continue;
			}

			Form::execute($data['action'], $data['form_name'], $child->page_id, true);
		}
	},
	'execute_post' => function($rowCount, $fields, $data) {
		Hook::run('page.toggle', $fields);
	}
];
