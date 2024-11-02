<?php

namespace module\backend\model;

use engine\auth\User;
use engine\database\Model;
use engine\database\Query;
use engine\i18n\I18n;
use engine\util\Hash;

class Auth extends Model
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
      'max' => 256
    ]);

    parent::__construct($columnData, $columnKeysToValidate);
  }

  public function login()
  {
    if (!$this->hasTable()) {
      return false;
    }

    $this->validate();
    if ($this->hasError()) {
      return false;
    }

    $email = $this->getColumnValue('email');
    $password = $this->getColumnValue('password');

    $query = new Query('SELECT * FROM {user} WHERE email=:email');
    $user = $query->execute(['email' => $email])->fetch();

    if (empty($user) || !Hash::passwordVerify($password, $user->password)) {
      $this->setError('email', I18n::translate('auth.invalid_login_or_password'));
      return false;
    }

    if (!$user->is_enabled) {
      $this->setError('email', I18n::translate('auth.account_is_disabled'));
      return false;
    }

    return User::authorize($user);
  }

  public function register()
  {
    if (!$this->hasTable()) {
      return false;
    }

    $this->validate();
    if ($this->hasError()) {
      return false;
    }

    $name = $this->getColumnValue('name');
    $email = $this->getColumnValue('email');
    $password = $this->getColumnValue('password');

    $query = new Query('SELECT count(*) FROM {user} WHERE email=:email');
    $userCount = $query->execute(['email' => $email])->fetchColumn();

    if ($userCount > 0) {
      $this->setError('email', I18n::translate('auth.email_already_exists', $email));
      return false;
    }

    return User::register($name, $email, $password);
  }

  public function restore()
  {
    if (!$this->hasTable()) {
      return false;
    }

    $this->validate();
    if ($this->hasError()) {
      return false;
    }

    $email = $this->getColumnValue('email');

    $query = new Query('SELECT * FROM {user} WHERE email=:email');
    $user = $query->execute(['email' => $email])->fetch();

    if (empty($user)) {
      $this->setError('email', I18n::translate('auth.invalid_login'));
      return false;
    }

    if (!$user->is_enabled) {
      $this->setError('email', I18n::translate('auth.account_is_disabled'));
      return false;
    }

    return User::restore($user);
  }
}
