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
    $this->table = 'user';
    $this->primaryKey = 'id';

    $this->column['id'] = [
      'type' => 'text',
      'required' => true,
      'min' => 16,
      'max' => 32,
      'value' => Hash::token()
    ];

    $this->column['name'] = [
      'type' => 'text',
      'required' => true,
      'min' => 2,
      'max' => 256,
      'regex' => '/^[\w ]+$/iu'
    ];

    $this->column['email'] = [
      'type' => 'email',
      'required' => true,
      'min' => 6,
      'max' => 256
    ];

    $this->column['password'] = [
      'type' => 'password',
      'required' => true,
      'min' => 8,
      'max' => 256
    ];

    parent::__construct($columnData, $columnKeysToValidate);
  }

  public function login()
  {
    if (empty($this->table) || empty($this->column)) {
      return false;
    }

    $this->validation->validate();
    if ($this->validation->hasError()) {
      $this->queryError = $this->validation->getError();
      return false;
    }

    $email = @$this->getColumn('email')['value'];
    $password = @$this->getColumn('password')['value'];

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
    if (empty($this->table) || empty($this->column)) {
      return false;
    }

    $this->validation->validate();
    if ($this->validation->hasError()) {
      $this->queryError = $this->validation->getError();
      return false;
    }

    $name = @$this->getColumn('name')['value'];
    $email = @$this->getColumn('email')['value'];
    $password = @$this->getColumn('password')['value'];

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
    if (empty($this->table) || empty($this->column)) {
      return false;
    }

    $this->validation->validate();
    if ($this->validation->hasError()) {
      $this->queryError = $this->validation->getError();
      return false;
    }

    $email = @$this->getColumn('email')['value'];

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
