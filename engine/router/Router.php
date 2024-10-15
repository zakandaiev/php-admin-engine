<?php

namespace engine\router;

use engine\Config;
use engine\module\Module;
use engine\http\Request;
use engine\http\Response;
use engine\i18n\I18n;
use engine\util\File;
use engine\util\Hash;
use engine\util\Path;

class Router
{
  protected static $route = [];
  protected static $routeList = [];
  protected static $allowedMethods = ['get', 'post', 'put', 'patch', 'delete', 'options', 'any'];

  public static function initialize()
  {
    self::loadModuleRoutes();
  }

  public static function watch()
  {
    // TODO
    // self::checkForm();
    self::checkRoutes();
    self::check404();
    self::setController();
  }

  public static function list($moduleName = null)
  {
    return isset($moduleName) ? @self::$routeList[$moduleName] : self::$routeList;
  }

  public static function has($routeName, $moduleName = null)
  {
    $moduleName = $moduleName ?? Module::getName();
    $moduleExtends = Module::getProperty('extends');

    if (isset(self::$routeList[$moduleName][$routeName])) {
      return true;
    }

    return isset(self::$routeList[$moduleExtends][$routeName]);
  }

  public static function get($routeName = null, $moduleName = null)
  {
    $moduleExtends = Module::getProperty('extends');

    if ($moduleExtends && isset($routeName) && !isset($moduleName) && !isset(self::$routeList[$moduleName ?? Module::getName()][$routeName])) {
      $moduleName = $moduleExtends;
    }

    $moduleName = $moduleName ?? Module::getName();

    return isset($routeName) ? @self::$routeList[$moduleName][$routeName] : @self::$routeList[$moduleName];
  }

  public static function isMethodAllowed($method)
  {
    return in_array($method, self::$allowedMethods);
  }

  public static function register($method, $path, $controller, $name = null, $option = [])
  {
    if (is_array($path)) {
      foreach ($path as $itemPath) {
        self::register($method, $itemPath, $controller, $name, $option);
      }

      return true;
    }

    $moduleName = Module::getName();

    if (!self::isMethodAllowed($method)) {
      throw new \Exception(sprintf('Invalid method declaration for %s route in %s module - method %s is not allowed.', $path, $moduleName, $method));
    }

    if (isClosure($controller)) {
      $routeController = $controller;
      $routeAction = null;
    } else {
      @list($routeController, $routeAction) = explode('@', $controller, 2);

      if (empty($routeController) || empty($routeAction)) {
        throw new \Exception(sprintf('Invalid controller declaration for %s route in %s module.', $path, $moduleName));
      }
    }

    $route = [
      'module' => $moduleName,
      'method' => $method,
      'path' => $path,
      'controller' => $routeController,
      'action' => $routeAction,
      'name' => $name,
      'option' => $option
    ];

    self::$routeList[$moduleName][$route['name'] ?? Hash::token(8)] = $route;

    $routes = Module::getProperty('routes');
    $routes[] = $route;
    Module::setProperty($routes, 'routes');

    return true;
  }

  protected static function loadModuleRoutes()
  {
    foreach (Module::list() as $module) {
      if (!$module['isEnabled']) {
        continue;
      }

      Module::setName($module['name']);

      $moduleName = Module::getName();
      $modulePath = Path::file('module');

      $routesPath = "$modulePath/$moduleName/routes.php";

      if (is_file($routesPath)) {
        require $routesPath;
      }
    }

    Module::setName(null);

    return true;
  }

  protected static function checkForm()
  {
    if (Request::method() === 'get') {
      return false;
    }

    $token = Request::uriParts(0);

    if (empty($token)) {
      return false;
    }

    $statement = new Query('SELECT * FROM {form} WHERE token = :token ORDER BY date_created DESC LIMIT 1');

    $form = $statement->execute(['token' => Request::uriParts(0)])->fetch();

    if (empty($form)) {
      return false;
    }

    if (Request::ip() !== $form->ip) {
      Response::answer(null, 'error', I18n::translate('form.forbidden'), 403);
    }

    $timestampNow = time();
    $timestampCreated = strtotime($form->date_created);
    $timestampDiff = $timestampNow - $timestampCreated;

    if ($timestampDiff > Config::getProperty('form', 'lifetime')) {
      Response::answer(null, 'error', I18n::translate('form.inactive'), 405);
    }

    Module::loadHooks();
    Module::setName($form->module);

    self::initRoute();

    new User();

    Form::execute($form->action, $form->form_name, $form->item_id, $form->is_translation);

    Response::answer();

    return true;
  }

  protected static function checkRoutes()
  {
    foreach (Module::list() as $module) {
      if (!$module['isEnabled']) {
        continue;
      }

      if (isset($module['routes']) && !empty($module['routes'])) {
        foreach ($module['routes'] as $route) {
          if (self::checkRoute($module['name'], $route)) {
            return true;
          }
        }
      }
    }

    return false;
  }

  protected static function checkRoute($module, $route)
  {
    Module::setName($module);

    $language = Request::uriParts(0);
    if (I18n::has($language)) {
      I18n::setCurrent($language);
    }

    $routeMethod = strtolower(trim($route['method'] ?? ''));
    $requestMethod = Request::method();
    if (
      ($routeMethod === 'any' || $routeMethod === $requestMethod)
      && self::isRouteMatched($route['path'])
    ) {
      foreach ($route as $key => $value) {
        self::$route[$key] = $value;
      }

      Module::loadHooks();
      Module::setName($module);

      return true;
    }

    Module::setName(null);

    self::$route = [];

    return false;
  }

  protected static function isRouteMatched($route)
  {
    $parameter = [];

    self::$route['parameter'] = $parameter;

    $routeUri = $route === '/' ? $route : rtrim($route ?? '', '/');
    $requestUri = Request::uri();
    if ($routeUri === '/' && $requestUri === '/') {
      return true;
    }

    $routeParts = explode('/', ltrim($routeUri ?? '', '/'));
    $requestParts = Request::uriParts();

    if (I18n::has($requestParts[0])) {
      array_shift($requestParts);

      if ($routeUri === '/' && count($requestParts) === 0) {
        return true;
      }
    }

    if (count($routeParts) !== count($requestParts)) {
      return false;
    }

    for ($__i__ = 0; $__i__ < count($routeParts); $__i__++) {
      $routePart = $routeParts[$__i__];

      if (preg_match('/^[$]/', $routePart)) {
        $foundVariable = ltrim($routePart ?? '', '$');
        $parameter[$foundVariable] = $requestParts[$__i__];
      } else if ($routeParts[$__i__] !== $requestParts[$__i__]) {
        return false;
      }
    }

    self::$route['parameter'] = $parameter;

    return true;
  }

  protected static function check404()
  {
    if (empty(self::$route)) {
      if (Request::method() !== 'get') {
        Response::answer(null, 'error', 'Not found', 404);
      }

      $moduleName = 'frontend';

      foreach (Module::list() as $module) {
        Module::setName($module['name']);

        $requestParts = Request::uriParts();
        if (I18n::has($requestParts[0])) {
          array_shift($requestParts);
        }

        if (empty($requestParts)) {
          continue;
        }

        if ($requestParts[0] === $module['name']) {
          $moduleName = $module['name'];
        } else if ($requestParts[0] === $module['extends']) {
          $moduleName = $module['extends'];
        }
      }

      Module::setName($moduleName);

      self::$route['module'] = $moduleName;
      self::$route['method'] = Request::method();
      self::$route['path'] = Request::uri();
      self::$route['controller'] = 'Error';
      self::$route['action'] = 'error';
      self::$route['name'] = '404';
      self::$route['option'] = [];
      self::$route['parameter'] = [];

      $controllerName = self::$route['controller'];
      $controllerDirectory = Path::file('controller', $moduleName);
      $controllerFile = "$controllerDirectory/$controllerName.php";

      if (is_file($controllerFile)) {
        return true;
      }

      $controllerContent = "<?php\n\n";
      $controllerContent .= "namespace module\\$moduleName\\controller;\n\n";
      $controllerContent .= "use engine\\router\\Controller;\n\n";
      $controllerContent .= "class $controllerName extends Controller {}\n";

      File::createFile($controllerFile, $controllerContent);

      return true;
    }

    return false;
  }

  protected static function initRoute()
  {
    foreach (self::$route as $key => $value) {
      Route::set($key, $value);
    }

    Route::set('query', Request::get());

    return true;
  }

  protected static function setController()
  {
    self::initRoute();

    if (isClosure(self::$route['controller'])) {
      self::$route['controller'](self::$route['parameter'], self::$route['option'], self::$route);

      return true;
    }

    $controllerClass = Path::class('controller') . '\\' . self::$route['controller'];

    if (class_exists($controllerClass)) {
      $controllerAction = self::$route['action'];

      if (method_exists($controllerClass, $controllerAction)) {
        $controller = new $controllerClass;
        $controller->$controllerAction();
      } else {
        throw new \Exception(sprintf('Action %s does not exist in %s.', $controllerAction, $controllerClass));
      }
    } else {
      throw new \Exception(sprintf('Controller %s does not exist.', $controllerClass));
    }

    return true;
  }
}
