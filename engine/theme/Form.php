<?php

namespace engine\theme;

use engine\Config;
use engine\database\Query;
use engine\http\Request;
use engine\http\Response;
use engine\i18n\I18n;
use engine\module\Module;
use engine\util\Hash;
use engine\util\Path;

class Form
{
  protected $token;
  protected $isTokenExists = false;
  protected $isTokenAllowedIp = false;
  protected $isTokenActive = false;

  protected $module;
  protected $action;
  protected $modelName;
  protected $itemId;

  protected $model;
  protected $isModelActive = false;

  protected $answer = [];

  public function __construct($token)
  {
    // CHECK TOKEN IN DATABASE
    $bindParams = ['token' => $token];
    $sqlParams = array_reduce(array_keys($bindParams), function ($carry, $v) {
      return ($carry ? "$carry AND " : '') . "$v=:$v";
    });
    $sql = "SELECT * FROM {form} WHERE $sqlParams ORDER BY date_created DESC LIMIT 1";
    $query = new Query($sql);
    $result = $query->execute($bindParams)->fetch();
    if (!$result) {
      return $this;
    }
    $this->isTokenExists = true;

    // CHECK IP
    if (Request::ip() !== $result->ip) {
      return $this;
    }
    $this->isTokenAllowedIp = true;

    // CHECK TOKEN STILL LIFEABLE
    $tokenLifetime = Config::getProperty('form', 'lifetime');
    $timestampNow = time();
    $timestampCreated = strtotime($result->date_created);
    $timestampDiff = $timestampNow - $timestampCreated;
    if ($timestampDiff > $tokenLifetime) {
      return $this;
    }
    $this->isTokenActive = true;

    // PREPARE DATA BEFORE EXECUTION
    $this->module = $result->module;
    $this->action = $result->action;
    $this->modelName = $result->model_name;
    $this->itemId = $result->item_id;

    // TODO
    // Module::loadHooks();
    Module::setName($this->module);

    // CHECK MODEL
    $this->model = $this->loadModel($this->modelName);
    if (empty($this->model)) {
      return $this;
    }
    $this->isModelActive = true;

    return $this;
  }

  public function isTokenExists()
  {
    return $this->isTokenExists;
  }

  public function isTokenAllowedIp()
  {
    return $this->isTokenAllowedIp;
  }

  public function isTokenActive()
  {
    return $this->isTokenActive;
  }

  public function isModelActive()
  {
    return $this->isModelActive;
  }

  public function isFormActiveAndValid()
  {
    return $this->isTokenExists && $this->isTokenAllowedIp && $this->isTokenActive && $this->isModelActive;
  }

  public function execute()
  {
    if (!$this->isFormActiveAndValid()) {
      return $this;
    }

    $this->clearExpired();
    $this->process();

    return $this;
  }

  public function answer()
  {
    Response::answer(@$this->answer['status'], @$this->answer['message'], @$this->answer['data'], @$this->answer['code']);
  }

  protected function clearExpired()
  {
    $tokenLifetime = Config::getProperty('form', 'lifetime');
    $tokenLifetime *= 2;

    $query = new Query("DELETE FROM {form} WHERE date_created <= DATE_SUB(NOW(), INTERVAL $tokenLifetime SECOND)");

    $query->execute();

    return true;
  }

  protected function loadModel($modelName)
  {
    $model = Path::class('model') . '\\' . $modelName;

    if (class_exists($model)) {
      return new $model(Request::get());
    }

    return null;
  }

  protected function process()
  {
    $this->answer['status'] = 'success';
    $this->answer['message'] = null;
    $this->answer['data'] = null;
    $this->answer['code'] = 200;

    $this->model->validation->validate();
    if ($this->model->validation->hasError()) {
      $this->answer['status'] = 'error';
      $this->answer['message'] = I18n::translate('form.error');
      $this->answer['data'] = $this->model->validation->getError();
      $this->answer['code'] = 400;

      return $this;
    }

    // TODO
    // self::prepareMediaFields();
    // $foreign_data = self::getForeignFields();
    // $translation_data = self::getTranslationFields($form_data['modelName']);

    $table = $this->model->getTable();
    $pkName = $this->model->getPrimaryKey();
    $column = $this->model->getColumn();

    $columnKeys = [];
    $columnValues = [];
    foreach ($column as $columnName => $column) {
      $columnValue = $column['value'];
      if ($columnValue === null && $columnName === $pkName) {
        $columnValue = $this->itemId;
      }

      $columnKeys[] = $columnName;
      $columnValues[$columnName] = $columnValue;
    }

    $sql = null;
    if ($this->action === 'add') {
      $sqlParams = '(' . implode(', ', $columnKeys) . ') VALUES (:' . implode(', :', $columnKeys) . ')';
      $sql = "INSERT INTO {{$table}} $sqlParams";
    } else if ($this->action === 'edit') {
      $sqlParams = array_reduce($columnKeys, function ($carry, $v) {
        return ($carry ? "$carry, " : '') . "$v=:$v";
      });
      $sql = "UPDATE {{$table}} SET $sqlParams WHERE $pkName=:$pkName";
    } else if ($this->action === 'delete') {
      $sqlParams = array_reduce($columnKeys, function ($carry, $v) {
        return ($carry ? "$carry AND " : '') . "$v=:$v";
      });
      $sql = "DELETE FROM {{$table}} WHERE $sqlParams AND $pkName=:$pkName";
    }

    $query = new Query($sql);
    $result = $query->execute($columnValues);

    if ($query->hasError()) {
      $this->answer['status'] = 'error';
      $this->answer['message'] = $query->getError('message');
      $this->answer['data'] = $query->getError();
      $this->answer['code'] = 400;

      return $this;
    }

    if ($this->action === 'add') {
      $result = $result->insertId();
    } else if ($this->action === 'edit') {
      $result = true;
    } else if ($this->action === 'delete') {
      $result = true;
    }






    // TODO
    // $statement = new Statement($form_data['sql']);
    // $statement->execute($form_data['sql_binding']);

    // $form_data['rowCount'] = $statement->rowCount();

    // if ($form_data['action'] === 'add') {
    //   $form_data['itemId'] = $statement->insertId();
    // }

    // self::uploadMediaFields();
    // self::processForeignFields($foreign_data, $form_data);
    // self::processTranslationFields($translation_data, $form_data);

    // if (isset($form['execute_post']) && is_closure($form['execute_post'])) {
    //   $form['execute_post']($form_data['rowCount'], self::$fields, $form_data);
    // }

    // if (isset($form['submit_message']) && is_closure($form['submit_message'])) {
    //   $submit_message = $form['submit_message'](self::$fields, $form_data);
    // } else if (isset($form['submit_message'])) {
    //   $submit_message = $form['submit_message'];
    // }

    // if (!$form_data['force_no_answer']) {
    //   Server::answer(null, 'success', @$submit_message);
    // }

    return $this;
  }

  // STATIC METHODS
  public static function add($modelName)
  {
    return self::generateToken(__FUNCTION__, $modelName, null);
  }

  public static function edit($modelName, $itemId)
  {
    return self::generateToken(__FUNCTION__, $modelName, $itemId);
  }

  public static function delete($modelName, $itemId)
  {
    return self::generateToken(__FUNCTION__, $modelName, $itemId);
  }

  public static function isModelExists($modelName)
  {
    $model = Path::class('model') . '\\' . $modelName;

    return class_exists($model);
  }

  public static function generateToken($action, $modelName, $itemId = null)
  {
    if (!self::isModelExists($modelName)) {
      return null;
    }

    $tokenExists = self::isFormExistsAndActive($action, $modelName, $itemId);
    if ($tokenExists) {
      return Path::resolveUrl(null, $tokenExists);
    }

    $token = Hash::token();

    $bindParams = [
      'token' => $token,
      'module' => Module::getName(),
      'action' => $action,
      'model_name' => $modelName,
      'ip' => Request::ip()
    ];

    if ($action !== 'add') {
      $bindParams['item_id'] = $itemId;
    }

    $sqlParams = '(' . implode(', ', array_keys($bindParams)) . ') VALUES (:' . implode(', :', array_keys($bindParams)) . ')';

    $sql = "INSERT INTO {form} $sqlParams";
    $query = new Query($sql);
    $query->execute($bindParams);

    return Path::resolveUrl(null, $token);
  }

  protected static function isFormExistsAndActive($action, $modelName, $itemId = null)
  {
    $bindParams = [
      'module' => Module::getName(),
      'model_name' => $modelName,
      'action' => $action,
      'ip' => Request::ip()
    ];

    if ($action !== 'add') {
      $bindParams['item_id'] = $itemId;
    }

    $sqlParams = array_reduce(array_keys($bindParams), function ($carry, $v) {
      return ($carry ? "$carry AND " : '') . "$v=:$v";
    });

    $tokenLifetime = Config::getProperty('form', 'lifetime');

    $sql = "SELECT * FROM {form} WHERE $sqlParams AND date_created > DATE_SUB(NOW(), INTERVAL $tokenLifetime SECOND)";
    $query = new Query($sql);

    return $query->execute($bindParams)->fetchColumn();
  }








  protected static function prepareMediaFields()
  {
    foreach (self::$fields as $key => $field_data) {
      if ($field_data['type'] !== 'file' || empty($field_data['value']) || !isset($field_data['value'][0]['tmp_name'])) {
        continue;
      }

      $value = $field_data['value'];

      $upload = new Upload($value, @$field_data['folder'], @$field_data['extensions']);

      if (!$upload->get('status')) {
        Server::answer(null, 'error', $upload->get('message'), 400);
      }

      if (!isset($field_data['multiple']) || !$field_data['multiple']) {
        self::$fields[$key]['value'] = @$upload->get('files')[0];
      } else {
        self::$fields[$key]['value'] = $upload->get('files') ?? [];
      }

      self::$fields[$key]['upload'] = $upload;
    }

    return true;
  }

  protected static function uploadMediaFields()
  {
    foreach (self::$fields as $key => $field_data) {
      if ($field_data['type'] !== 'file' || !isset($field_data['upload']) || empty($field_data['upload'])) {
        continue;
      }

      $field_data['upload']->execute();
    }

    return true;
  }

  protected static function getForeignFields()
  {
    $data = [];

    foreach (self::$fields as $key => $field_data) {
      if (!isset($field_data['foreign']) || empty($field_data['foreign'])) {
        continue;
      }

      $field_name = $field_data['name'];
      $foreign = $field_data['foreign'];

      $data[$field_name]['value'] = $field_data['value'];

      if (is_closure($foreign)) {
        $data[$field_name]['closure'] = $foreign;
      } else {
        preg_match('/(\w+)\@(\w+)\/(\w+)/i', $foreign, $matches);

        if (empty($matches) || count($matches) !== 4) {
          continue;
        }

        $data[$field_name]['table'] = $matches[1];
        $data[$field_name]['key_pk'] = $matches[2];
        $data[$field_name]['key_fk'] = $matches[3];
      }

      unset(self::$fields[$key]);
    }

    return $data;
  }

  protected static function processForeignFields($foreign_data, $form_data)
  {
    if (empty($foreign_data) || empty($form_data)) {
      return false;
    }

    foreach ($foreign_data as $foreign) {
      if (isset($foreign['closure']) && is_closure($foreign['closure'])) {
        $foreign['closure']($foreign['value'], $form_data);
      } else if (isset($foreign['table']) && isset($foreign['key_pk']) && isset($foreign['key_fk'])) {
        $table = $foreign['table'];
        $key_pk = $foreign['key_pk'];
        $key_fk = $foreign['key_fk'];
        $value = $foreign['value'];
        $itemId = $form_data['itemId'];

        $sql = 'DELETE FROM {' . $table . '} WHERE ' . $key_pk . ' = :' . $key_pk;

        $statement = new Statement($sql);
        $statement->execute([$key_pk => $itemId]);

        if (empty($value)) {
          continue;
        }

        $value = is_array($value) ? $value : [$value];

        foreach ($value as $v) {
          $sql = '
            INSERT INTO {' . $table . '}
              (' . $key_pk . ', ' . $key_fk . ')
            VALUES
              (:' . $key_pk . ', :' . $key_fk . ')
          ';

          $statement = new Statement($sql);
          $statement->execute([$key_pk => $itemId, $key_fk => $v]);
        }
      }
    }

    return true;
  }

  protected static function getTranslationFields($modelName)
  {
    $data = [];

    $form_data = self::get($modelName);

    if (!isset($form_data['translation']) || empty($form_data['translation'])) {
      return false;
    }

    foreach ($form_data['translation'] as $key) {
      if (!isset(self::$fields[$key])) {
        continue;
      }

      $data[$key] = self::$fields[$key];

      unset(self::$fields[$key]);
    }

    return $data;
  }

  protected static function processTranslationFields($fields, $form_data)
  {
    if (!is_array($fields) || empty($form_data) || empty($form_data['itemId']) || ($form_data['action'] !== 'delete' && empty($fields)) || str_contains($form_data['table'], '_translation')) {
      return false;
    }

    if (!isset($fields['language']['value']) || empty($fields['language']['value'])) {
      $fields['language']['type'] = 'hidden';
      $fields['language']['value'] = Language::current();
    }

    $name = $form_data['modelName'] . '_translation';
    $table = $form_data['table'] . '_translation';
    $action = $form_data['action'];
    $data = [
      'table' => $table,
      'fields' => $fields,
      'modify_sql' => function ($sql, $fields, $data) {
        if ($data['action'] === 'edit') {
          $sql .= ' AND language = :language';
        }

        return $sql;
      },
    ];

    self::set($name, $data);
    self::execute($action, $name, $form_data['itemId'], false, true);

    return true;
  }
}
