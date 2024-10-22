<?php

namespace module\backend\model;

use engine\database\Model;
use engine\database\Query;
use engine\module\Module;
use engine\util\Hash;

class Group extends Model
{
  public function __construct($columnData = null, $columnKeysToValidate = null)
  {
    $this->table = 'group';
    $this->primaryKey = 'id';

    $this->column['language'] = [
      'type' => 'text',
      'required' => true,
      'value' => site('language'),
      'foreign' => 'group_translation@group_id'
    ];

    $this->column['id'] = [
      'type' => 'text',
      'min' => 1,
      'max' => 128,
      'value' => Hash::token(32)
    ];

    $this->column['name'] = [
      'type' => 'text',
      'required' => true,
      'min' => 1,
      'max' => 256,
      'regex' => '/^[\w ]+$/iu',
      'foreign' => 'group_translation@group_id'
    ];

    $this->column['route'] = [
      'type' => 'array',
      'value' => [],
      'foreign' => 'group_route@group_id'
    ];

    $this->column['user_id'] = [
      'type' => 'array',
      'value' => [],
      'foreign' => 'group_user@group_id'
    ];

    $this->column['is_enabled'] = [
      'type' => 'boolean',
      'value' => true
    ];

    $this->column['access_all'] = [
      'type' => 'boolean',
      'value' => false
    ];

    parent::__construct($columnData, $columnKeysToValidate);
  }

  public function getGroups()
  {
    $sql = "
      SELECT
        *,
        (SELECT COUNT(*) FROM {group_route} WHERE group_id=t_group.id) as count_routes,
        (SELECT COUNT(*) FROM {group_user} WHERE group_id=t_group.id) as count_users,
        (SELECT GROUP_CONCAT(language) FROM {group_translation} WHERE group_id=t_group.id AND language<>:language) as translations
      FROM
        {group} t_group
      INNER JOIN
        {group_translation} t_group_translation
      ON
        t_group.id=t_group_translation.group_id
      WHERE
        t_group_translation.language =
          (CASE WHEN
            (SELECT count(*) FROM {group_translation} WHERE group_id=t_group.id AND language=:language) > 0
          THEN
            :language
          ELSE
            (SELECT value FROM {setting} WHERE module='engine' AND name='language')
          END)
      ORDER BY
        t_group.date_created ASC
    ";

    $query = new Query($sql);

    // TODO
    // ->filter()
    return $query->paginate()->execute(['language' => site('language_current')])->fetchAll();
  }

  public function getRoutes()
  {
    $routesGrouped = [];
    $modules = Module::list();

    foreach ($modules as $module) {
      if (!$module['isEnabled']) {
        continue;
      }

      foreach ($module['routes'] as $route) {
        if (isset($route['isPublic']) && $route['isPublic'] === true) {
          continue;
        }

        $routesGrouped['any'][] = $route['path'];
        $routesGrouped[$route['method']][] = $route['path'];
      }
    }

    foreach ($routesGrouped as $method => $routes) {
      $routesGrouped[$method] = array_unique($routesGrouped[$method]);
    }

    ksort($routesGrouped, SORT_NATURAL | SORT_FLAG_CASE);

    return array_map(function ($a) {
      sort($a, SORT_NATURAL | SORT_FLAG_CASE);
      return $a;
    }, $routesGrouped);
  }

  public function getUsers()
  {
    $sql = "SELECT * FROM {user} ORDER BY name ASC, id ASC";

    $users = new Query($sql);

    $users = $users->execute()->fetchAll();

    // TODO
    // $users = array_map(function ($user) {
    //   return \Engine\User::format($user);
    // }, $users);

    return $users;
  }

  public function getGroupById($id, $language = null)
  {
    $sql = "
      SELECT
        *,
        (SELECT COUNT(*) FROM {group_route} WHERE group_id=t_group.id) as count_routes,
        (SELECT COUNT(*) FROM {group_user} WHERE group_id=t_group.id) as count_users
      FROM
        {group} t_group
      INNER JOIN
        {group_translation} t_group_translation
      ON
        t_group.id=t_group_translation.group_id
      WHERE
        t_group.id=:id
        AND t_group_translation.language =
          (CASE WHEN
            (SELECT count(*) FROM {group_translation} WHERE group_id=t_group.id AND language=:language) > 0
          THEN
            :language
          ELSE
            (SELECT value FROM {setting} WHERE module='engine' AND name='language')
          END)
      ORDER BY
        t_group.date_created ASC
      LIMIT 1
    ";

    $group = new Query($sql);

    return $group->execute(['id' => $id, 'language' => $language ?? site('language')])->fetch();
  }

  public function getGroupRoutesById($group_id)
  {
    $routes = [];

    $query = new Query("SELECT route FROM {group_route} WHERE group_id=:group_id");
    foreach ($query->execute(['group_id' => $group_id])->fetchAll() as $route) {
      $routes[] = $route->route;
    }

    return $routes;
  }

  public function getGroupUsersById($group_id)
  {
    $users = [];

    $query = new Query("SELECT user_id FROM {group_user} WHERE group_id=:group_id");
    foreach ($query->execute(['group_id' => $group_id])->fetchAll() as $user) {
      $users[] = $user->user_id;
    }

    return $users;
  }
}
