<?php

namespace module\backend\model;

use engine\database\Model;
use engine\database\Query;
use engine\module\Module;

class Group extends Model
{
  public function __construct()
  {
    $this->table = 'group';
    $this->primaryKey = 'id';

    $this->column['language'] = [
      'type' => 'text',
      'required' => true,
      'value' => site('language')
    ];

    $this->column['id'] = [
      'type' => 'number',
      'min' => 1,
      'max' => 128
    ];

    $this->column['name'] = [
      'type' => 'text',
      'required' => true,
      'min' => 1,
      'max' => 256,
      'regex' => '/^[\w ]+$/iu'
    ];

    $this->column['routes'] = [
      'type' => 'array',
      'value' => []
    ];

    $this->column['users'] = [
      'type' => 'array',
      'value' => []
    ];

    $this->column['is_enabled'] = [
      'type' => 'boolean',
      'value' => true
    ];

    $this->column['access_all'] = [
      'type' => 'boolean',
      'value' => false
    ];
  }

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

    $groups = new Query($sql);

    // TODO
    // $groups = $groups->filter('group')->paginate()->execute(['language' => site('language')])->fetchAll();
    $groups = $groups->paginate()->execute(['language' => site('language_current')])->fetchAll();

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

    $users = new Query($sql);

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

    $group = new Query($sql);

    return $group->execute(['id' => $id, 'language' => $language ?? site('language')])->fetch();
  }

  public function getGroupRoutesById($group_id)
  {
    $routes = new \stdClass();

    $sql = 'SELECT route FROM {group_route} WHERE group_id = :group_id';

    $statement = new Query($sql);

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

    $statement = new Query($sql);

    foreach ($statement->execute(['group_id' => $group_id])->fetchAll() as $user) {
      $users[] = $user->user_id;
    }

    return $users;
  }
}
