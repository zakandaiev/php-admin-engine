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
  protected $isMatchRequest;

  protected $model;
  protected $isModelActive = false;

  public function __construct($token)
  {
    // CHECK TOKEN IN DATABASE
    $sql = "SELECT * FROM {form} WHERE token=:token ORDER BY date_created DESC LIMIT 1";
    $query = new Query($sql);
    $result = $query->execute(['token' => $token])->fetch();
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
    $this->isMatchRequest = $result->is_match_request;

    // TODO
    // Module::loadHooks();
    Module::setName($this->module);

    // CHECK MODEL
    $this->model = $this->loadModel();
    if (empty($this->model)) {
      return $this;
    }
    $this->model->setItemId($this->itemId);
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

    $result = $this->model->{$this->action}();
    if ($result) {
      $answer['status'] = 'success';
      $answer['message'] = I18n::translate('form.success');
      $answer['data'] = $result;
      $answer['code'] = 200;
    } else {
      $answer['status'] = 'error';
      $answer['message'] = I18n::translate('form.error');
      $answer['data'] = $this->model->getError();
      $answer['code'] = 400;
    }

    // TODO
    // executePost
    // submitMessage
    // forceNoAnswer

    Response::answer(@$answer['status'], @$answer['message'], @$answer['data'], @$answer['code']);

    return $this;
  }

  protected function clearExpired()
  {
    $tokenLifetime = Config::getProperty('form', 'lifetime');
    $tokenLifetime *= 2;

    $query = new Query("DELETE FROM {form} WHERE date_created <= DATE_SUB(NOW(), INTERVAL $tokenLifetime SECOND)");

    $query->execute();

    return true;
  }

  protected function loadModel()
  {
    $model = Path::class('model') . '\\' . $this->modelName;

    if (class_exists($model)) {
      $values = Request::get();
      $columnKeysToValidate = $this->isMatchRequest ? array_keys($values) : null;

      $modelInstance = $model::getInstance();
      if (!$modelInstance) {
        return new $model($values, $columnKeysToValidate);
      }

      $modelInstance->setData($values, $columnKeysToValidate);

      return $modelInstance;
    }

    return null;
  }

  public static function add($modelName, $isMatchRequest = null)
  {
    return self::generateToken(__FUNCTION__, $modelName, null, $isMatchRequest);
  }

  public static function edit($modelName, $itemId, $isMatchRequest = null)
  {
    return self::generateToken(__FUNCTION__, $modelName, $itemId, $isMatchRequest);
  }

  public static function delete($modelName, $itemId, $isMatchRequest = null)
  {
    return self::generateToken(__FUNCTION__, $modelName, $itemId, $isMatchRequest);
  }

  public static function isModelExists($modelName)
  {
    $model = Path::class('model') . '\\' . $modelName;

    return class_exists($model);
  }

  public static function generateToken($action, $modelName, $itemId = null, $isMatchRequest = null)
  {
    if (!self::isModelExists($modelName)) {
      return null;
    }

    $tokenExists = self::isFormExistsAndActive($action, $modelName, $itemId, $isMatchRequest);
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
      $bindParams['is_match_request'] = $isMatchRequest ?? false;
    }

    $sqlParams = '(' . implode(', ', array_keys($bindParams)) . ') VALUES (:' . implode(', :', array_keys($bindParams)) . ')';

    $sql = "INSERT INTO {form} $sqlParams";
    $query = new Query($sql);
    $query->execute($bindParams);

    return Path::resolveUrl(null, $token);
  }

  protected static function isFormExistsAndActive($action, $modelName, $itemId = null, $isMatchRequest = null)
  {
    $bindParams = [
      'module' => Module::getName(),
      'model_name' => $modelName,
      'action' => $action,
      'ip' => Request::ip()
    ];

    if ($action !== 'add') {
      $bindParams['item_id'] = $itemId;
      $bindParams['is_match_request'] = $isMatchRequest ?? false;
    }

    $sqlParams = array_reduce(array_keys($bindParams), function ($carry, $v) use ($bindParams) {
      $operand = '=';

      if (is_null($bindParams[$v])) {
        $operand = '<=>';
      }

      return ($carry ? "$carry AND " : '') . "$v$operand:$v";
    });

    $tokenLifetime = Config::getProperty('form', 'lifetime');

    $sql = "SELECT * FROM {form} WHERE $sqlParams AND date_created > DATE_SUB(NOW(), INTERVAL $tokenLifetime SECOND)";
    $query = new Query($sql);

    return $query->execute($bindParams)->fetchColumn();
  }
}
