<?php

require Path::file('form') . '/_model/Page.php';

return [
	'table' => 'page',
	'fields' => [
		'no_index_no_follow' => $no_index_no_follow,
		'author' => $author,
		'category' => $category,
		'url' => $url,
		'template' => $template,
		'date_publish' => $date_publish,
		'is_category' => $is_category,
		'allow_comment' => $allow_comment,
		'hide_comments' => $hide_comments,
		'is_enabled' => $is_enabled,

		'language' => $language,
		'title' => $title,
		'excerpt' => $excerpt,
		'content' => $content,
		'image' => $image,
		'seo_description' => $seo_description,
		'seo_keywords' => $seo_keywords,
		'seo_image' => $seo_image,

		// 'custom_fields' => $custom_fields
	],
	'translation' => ['language', 'title', 'excerpt', 'content', 'image', 'seo_description', 'seo_keywords', 'seo_image'],
	'execute_pre' => function ($fields, $data) {
		if ($data['action'] === 'delete' && !empty($data['item_id'])) {
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

			foreach ($children as $child) {
				if ($child->count_parent_categories > 1) {
					continue;
				}

				Form::execute($data['action'], $data['form_name'], $child->page_id, false, true);
			}
		}
	},
	'execute_post' => function ($rowCount, $fields, $data) {
		Hook::run('page.' . $data['action'], $fields);
	}
];
