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

    // SET MODULE NAME
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

  public function isMatchRequest()
  {
    return $this->isMatchRequest;
  }

  public function getModule()
  {
    return $this->module;
  }

  public function getAction()
  {
    return $this->action;
  }

  public function getModelName()
  {
    return $this->modelName;
  }

  public function getItemId()
  {
    return $this->itemId;
  }

  public function getModel()
  {
    return $this->model;
  }

  public function execute()
  {
    if (!$this->isFormActiveAndValid()) {
      return $this;
    }

    $this->clearExpired();

    if ($this->model->hasSubmitOption('execute.pre')) {
      $this->model->getSubmitOption('execute.pre')($this);
    }

    if ($this->model->hasSubmitOption('execute')) {
      $result = $this->model->getSubmitOption('execute')($this);
    } else {
      $result = $this->model->{$this->action}();
    }

    $submitMessageSuccess = $this->model->hasSubmitMessage('success') ? $this->model->getSubmitMessage('success') : I18n::translate('form.success');
    $submitMessageError = $this->model->hasSubmitMessage('error') ? $this->model->getSubmitMessage('error') : I18n::translate('form.error');

    if ($result) {
      $answer['status'] = 'success';
      $answer['message'] = $submitMessageSuccess;
      $answer['data'] = $result;
      $answer['code'] = 200;
    } else {
      $answer['status'] = 'error';
      $answer['message'] = $submitMessageError;
      $answer['data'] = $this->model->getError();
      $answer['code'] = 400;
    }

    if ($this->model->hasSubmitOption('execute.post')) {
      $this->model->getSubmitOption('execute.post')($result, $this);
    }

    if (!$this->model->hasSubmitOption('forceNoAnswer')) {
      Response::answer(@$answer['status'], @$answer['message'], @$answer['data'], @$answer['code']);
    }

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
    $model = Path::class('model', $this->module) . '\\' . $this->modelName;
    $moduleExtends = Module::getProperty('extends');
    if (!class_exists($model) && $moduleExtends) {
      $model = Path::class('model', $moduleExtends) . '\\' . $this->modelName;
    }

    if (!class_exists($model)) {
      return null;
    }

    $values = Request::get();
    $columnKeysToValidate = $this->isMatchRequest ? array_merge(array_keys($values), array_keys(Request::files())) : null;

    $modelInstance = $model::getInstance();
    if (!$modelInstance) {
      $modelInstance = new $model($values, $columnKeysToValidate);
    }

    $modelInstance->setData($values, $columnKeysToValidate);

    return $modelInstance;
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

  public static function isModelExists($modelName, $moduleName = null)
  {
    $model = Path::class('model', $moduleName) . '\\' . $modelName;
    if (class_exists($model)) {
      return true;
    }

    $moduleExtends = Module::getProperty('extends');
    if (!$moduleExtends) {
      return false;
    }

    $modelFromExtendedModule = Path::class('model', $moduleExtends) . '\\' . $modelName;
    if (class_exists($modelFromExtendedModule)) {
      return true;
    }

    return false;
  }

  public static function generateToken($action, $modelName, $itemId = null, $isMatchRequest = null, $moduleName = null)
  {
    if (!self::isModelExists($modelName, $moduleName)) {
      return null;
    }

    $tokenExists = self::isFormExistsAndActive($action, $modelName, $itemId, $isMatchRequest, $moduleName);
    if ($tokenExists) {
      return Path::resolveUrl(null, $tokenExists);
    }

    $token = Hash::token();

    $bindParams = [
      'token' => $token,
      'module' => $moduleName ?? Module::getName(),
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

  protected static function isFormExistsAndActive($action, $modelName, $itemId = null, $isMatchRequest = null, $moduleName = null)
  {
    $bindParams = [
      'module' => $moduleName ?? Module::getName(),
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
