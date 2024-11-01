<?php

namespace module\backend\model;

use engine\auth\User;
use engine\database\Model;
use engine\database\Query;
use engine\module\Module;
use engine\util\Hash;

class Group extends Model
{
  public function __construct($columnData = null, $columnKeysToValidate = null)
  {
    $this->setTable('group');
    $this->setPrimaryKey('id');

    $this->setColumn('id', [
      'type' => 'text',
      'required' => true,
      'min' => 16,
      'max' => 32,
      'value' => Hash::token()
    ]);

    $this->setColumn('language', [
      'type' => 'text',
      'required' => true,
      'value' => site('language'),
      'foreign' => 'group_translation@group_id'
    ]);

    $this->setColumn('name', [
      'type' => 'text',
      'required' => true,
      'min' => 1,
      'max' => 256,
      'regex' => '/^[\w ]+$/iu',
      'foreign' => 'group_translation@group_id',
      'isForeignDeleteSkip' => true
    ]);

    $this->setColumn('route', [
      'type' => 'select',
      'isMultiple' => true,
      'options' => function ($itemId, $value) {
        return $this->getRouteOptions($value->route ?? []);
      },
      'value' => [],
      'foreign' => 'group_route@group_id'
    ]);

    $this->setColumn('user_id', [
      'type' => 'select',
      'isMultiple' => true,
      'options' => function () {
        return $this->getUserOptions();
      },
      'value' => [],
      'foreign' => 'group_user@group_id'
    ]);

    $this->setColumn('is_enabled', [
      'type' => 'boolean',
      'value' => true
    ]);

    $this->setColumn('access_all', [
      'type' => 'boolean',
      'value' => false
    ]);

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
    $groups = $query->paginate()->execute(['language' => site('language')])->fetchAll();

    return $groups;
  }

  public function getGroupById($id, $language = null)
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

  public function getRouteOptions($addableRoutes = [])
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

    $routesGrouped = array_map(function ($a) {
      sort($a, SORT_NATURAL | SORT_FLAG_CASE);
      return $a;
    }, $routesGrouped);

    $routes = [];
    foreach ($routesGrouped as $method => $rg) {
      foreach ($rg as $p) {
        $r = new \stdClass();

        $r->text = $p;
        $r->value = $method . '@' . $p;

        $routes[$method][] = $r;
      }
    }

    foreach ($addableRoutes as $addableRoute) {
      list($method, $path) = explode('@', $addableRoute);

      if (empty($method) || empty($path)) {
        continue;
      }

      $isAddableRouteAlreadyInArray = array_filter($routes[$method], function ($routeOption) use ($addableRoute) {
        return $routeOption->value === $addableRoute;
      });

      if (!$isAddableRouteAlreadyInArray) {
        $r = new \stdClass();

        $r->text = $path;
        $r->value = $addableRoute;

        $routes[$method][] = $r;
      }
    }

    return $routes;
  }

  public function getUserOptions()
  {
    $sql = "SELECT * FROM {user} ORDER BY name ASC, id ASC";
    $users = new Query($sql);
    $users = $users->execute()->fetchAll();

    $users = array_map(function ($user) {
      $user = User::format($user);

      $u = new \stdClass();

      $u->text = $user->fullname;
      $u->value = $user->id;

      return $u;
    }, $users);

    return $users;
  }
}
