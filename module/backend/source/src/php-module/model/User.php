<?php

namespace module\backend\model;

use engine\auth\User as AuthUser;
use engine\database\Model;
use engine\database\Query;
use engine\util\Hash;

class User extends Model
{
  public function __construct($columnData = null, $columnKeysToValidate = null)
  {
    $this->setTable('user');
    $this->setPrimaryKey('id');

    $this->setColumn('id', [
      'type' => 'text',
      'required' => true,
      'min' => 16,
      'max' => 32,
      'value' => Hash::token()
    ]);

    $this->setColumn('name', [
      'type' => 'text',
      'required' => true,
      'min' => 2,
      'max' => 256,
      'regex' => '/^[\w ]+$/iu'
    ]);

    $this->setColumn('email', [
      'type' => 'email',
      'required' => true,
      'min' => 6,
      'max' => 256
    ]);

    $this->setColumn('password', [
      'type' => 'password',
      'required' => true,
      'min' => 8,
      'max' => 256,
      'unsetNull' => true,
      'modify' => function ($pass) {
        return hashPassword($pass);
      }
    ]);

    $this->setColumn('group_id', [
      'type' => 'select',
      'isMultiple' => true,
      'options' => function () {
        return $this->getGroupOptions();
      },
      'value' => [],
      'foreign' => 'group_user@user_id'
    ]);

    $this->setColumn('is_enabled', [
      'type' => 'boolean',
      'value' => true
    ]);

    $this->setColumn('avatar', [
      'type' => 'file',
      'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']
    ]);

    $this->setColumn('setting', [
      'type' => 'text'
    ]);

    parent::__construct($columnData, $columnKeysToValidate);
  }

  public function getUsers()
  {
    $sql = '
      SELECT
        *,
        (SELECT COUNT(*) FROM {group_user} WHERE user_id=t_user.id) as count_groups
      FROM
        {user} t_user
      ORDER BY
        date_created ASC
    ';
    $query = new Query($sql);
    // TODO
    // ->filter()
    $users = $query->paginate()->execute()->fetchAll();

    $users = array_map(function ($user) {
      return AuthUser::format($user);
    }, $users);

    return $users;
  }

  public function getUserById($id)
  {
    $sql = 'SELECT * FROM {user} WHERE id=:id';
    $query = new Query($sql);
    $user = $query->execute(['id' => $id])->fetch();

    return $user;
  }

  public function getUserGroups($userId)
  {
    $sql = 'SELECT group_id FROM {group_user} WHERE user_id=:user_id';
    $query = new Query($sql);
    $groups = $query->execute(['user_id' => $userId])->fetchAll();

    $groups = array_map(function ($group) {
      return $group->group_id;
    }, $groups);

    return $groups;
  }

  public function getGroupOptions()
  {
    $sql = "
      SELECT
        *
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
        t_group_translation.name ASC
    ";

    $query = new Query($sql);
    $groups = $query->execute(['language' => site('language_current')])->fetchAll();

    $groups = array_map(function ($group) {
      $u = new \stdClass();

      $u->text = $group->name;
      $u->value = $group->id;

      return $u;
    }, $groups);

    return $groups;
  }
}
