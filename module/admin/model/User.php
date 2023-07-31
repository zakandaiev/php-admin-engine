<?php

namespace Module\Admin\Model;

use Engine\Statement;

class User {
	public function getUsers() {
		$sql = '
			SELECT
				*,
				(SELECT COUNT(*) FROM {user_group} WHERE user_id=t_user.id) as count_groups
			FROM
				{user} t_user
		';

		$users = new Statement($sql);

		$users = $users->filter('User', 'WHERE')->paginate()->execute()->fetchAll();

		return $users;
	}

	public function getUserById($id) {
		return \Engine\User::get($id);
	}

	public function getGroups() {
		$sql = '
			SELECT
				*
			FROM
				{group} t_group
			INNER JOIN
				{group_translation} t_group_translation
			ON
				t_group.id = t_group_translation.group_id
			WHERE
				t_group_translation.language =
					(CASE WHEN
						(SELECT count(*) FROM {group_translation} WHERE group_id = t_group.id AND language = :language) > 0
					THEN
						:language
					ELSE
						(SELECT value FROM {setting} WHERE module = \'engine\' AND name = \'language\')
					END)
			ORDER BY
				t_group_translation.name ASC
		';

		$groups = new Statement($sql);

		return $groups->execute(['language' => site('language_current')])->fetchAll();
	}

	public function getUserGroups($user_id) {
		$groups_array = [];

		$sql = 'SELECT group_id FROM {user_group} WHERE user_id = :user_id';

		$groups = new Statement($sql);

		foreach($groups->execute(['user_id' => $user_id])->fetchAll() as $category) {
			$groups_array[] = $category->group_id;
		}

		return $groups_array;
	}
}