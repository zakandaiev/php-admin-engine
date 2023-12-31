<?php

namespace Module\Admin\Model;

use Engine\Statement;
use Engine\Module;
use Engine\User;

class Group extends \Engine\Model
{
	public function getGroups()
	{
		$sql = '
			SELECT
				*,
				(SELECT COUNT(*) FROM {group_route} WHERE group_id = t_group.id) as count_routes,
				(SELECT COUNT(*) FROM {group_user} WHERE group_id = t_group.id) as count_users,
				(SELECT GROUP_CONCAT(language) FROM {group_translation} WHERE group_id = t_group.id AND language<>:language) as translations
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
				t_group.id ASC
		';

		$groups = new Statement($sql);

		$groups = $groups->filter('group')->paginate()->execute(['language' => site('language')])->fetchAll();

		foreach ($groups as $group) {
			$group->translations = !empty($group->translations) ? explode(',', $group->translations) : [];
		}

		return $groups;
	}

	public function getRoutes()
	{
		$routes_grouped = [];
		$modules = Module::get();

		foreach ($modules as $module) {
			if (!$module['is_enabled']) {
				continue;
			}

			foreach ($module['routes'] as $route) {
				if (isset($route['is_public']) && $route['is_public'] === true) {
					continue;
				}

				$routes_grouped['any'][] = $route['path'];
				$routes_grouped[$route['method']][] = $route['path'];
			}
		}

		$routes_grouped['any'] = array_unique($routes_grouped['any']);

		ksort($routes_grouped, SORT_NATURAL | SORT_FLAG_CASE);

		return array_map(function ($a) {
			sort($a, SORT_NATURAL | SORT_FLAG_CASE);
			return $a;
		}, $routes_grouped);
	}

	public function getUsers()
	{
		$sql = "SELECT * FROM {user} ORDER BY name ASC, id ASC";

		$users = new Statement($sql);

		$users = $users->execute()->fetchAll();

		$users = array_map(function ($user) {
			return \Engine\User::format($user);
		}, $users);

		return $users;
	}

	public function getGroupById($id, $language = null)
	{
		$sql = '
			SELECT
				*,
				(SELECT COUNT(*) FROM {group_route} WHERE group_id = t_group.id) as count_routes,
				(SELECT COUNT(*) FROM {group_user} WHERE group_id = t_group.id) as count_users
			FROM
				{group} t_group
			INNER JOIN
				{group_translation} t_group_translation
			ON
				t_group.id = t_group_translation.group_id
			WHERE
				t_group.id = :id
				AND t_group_translation.language =
					(CASE WHEN
						(SELECT count(*) FROM {group_translation} WHERE group_id = t_group.id AND language = :language) > 0
					THEN
						:language
					ELSE
						(SELECT value FROM {setting} WHERE module = \'engine\' AND name = \'language\')
					END)
			ORDER BY
				t_group.id ASC
			LIMIT 1
		';

		$group = new Statement($sql);

		return $group->execute(['id' => $id, 'language' => $language ?? site('language')])->fetch();
	}

	public function getGroupRoutesById($group_id)
	{
		$routes = new \stdClass();

		$sql = 'SELECT route FROM {group_route} WHERE group_id = :group_id';

		$statement = new Statement($sql);

		foreach ($statement->execute(['group_id' => $group_id])->fetchAll() as $route) {
			list($method, $uri) = explode('@', $route->route, 2);
			$routes->{$method}[] = $uri;
		}

		return $routes;
	}

	public function getGroupUsersById($group_id)
	{
		$users = [];

		$sql = 'SELECT user_id FROM {group_user} WHERE group_id = :group_id';

		$statement = new Statement($sql);

		foreach ($statement->execute(['group_id' => $group_id])->fetchAll() as $user) {
			$users[] = $user->user_id;
		}

		return $users;
	}
}
