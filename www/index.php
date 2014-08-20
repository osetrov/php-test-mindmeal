<?php

//phpinfo();

define('PATH_CONFIGS',			realpath('../configs').DIRECTORY_SEPARATOR);
define("PATH_CONTROLLERS",		realpath("../controllers").DIRECTORY_SEPARATOR);
define("PATH_CLASSES",			realpath("../classes").DIRECTORY_SEPARATOR);
define("PATH_VIEWS",			realpath("../views").DIRECTORY_SEPARATOR);
define("PATH_LIBS",				realpath("../libs").DIRECTORY_SEPARATOR);
define("PATH_LANG",				realpath("../lang").DIRECTORY_SEPARATOR);
define("PATH_I18N",				realpath("../i18n").DIRECTORY_SEPARATOR);
define("PATH_WWW",				realpath("../www").DIRECTORY_SEPARATOR);

require_once PATH_LIBS.'function.php';
require_once PATH_CONFIGS.'main.php';
require_once PATH_CLASSES.'cache.php'; // Memcache

require PATH_CLASSES.'core'.DIRECTORY_SEPARATOR.'/loader.php';
spl_autoload_register('Loader::load');

require_once PATH_CLASSES.'base.controller.php';
require_once PATH_CLASSES.'db.php';
require_once PATH_CLASSES.'auth.php';

$request = strtolower($_SERVER['REQUEST_URI']);
$request = explode('?', $request);
$request = explode('/', $request[0]);

$require_path = PATH_CONTROLLERS;
$controller = 'index';
$action = 'index';
$params = array();
$mode = 0;

if (isset($request[1]) && $request[1] != '') {
    if ($request[1][0] == 'u') {
        $temp = substr($request[1], 1);
        if (is_numeric($temp) && $temp > 0) {
            switch ($request[1][0]) {
                case 'u':
                    $mode = 1;
                    $controller = CONFIG_USER_DEFAULT_CONTROLLER;
                    break;
            }
            $params['id'] = (int)$temp;
        } else {
            array_unshift($request, '1');
        }
    } else if($request[1] == 'admin') {
        $mode = 4;
    } else {
        array_unshift($request, '1');
    }
    unset($request[0]);
    unset($request[1]);
    if (isset($request[2]) && $request[2] != '' ) {
        $controller = $request[2];
        unset($request[2]);
    }
    if (isset($request[3]) && $request[3] != '') {
        if (is_numeric($request[3])) {
            $params['element'] = $request[3];
            if (isset($request[4]) && $request[4] != '') {
                $action = $request[4];
                unset($request[4]);
            }
        } else {
            $action = $request[3];
        }
        unset($request[3]);
    }
    if (! empty($request)) {
        if (! empty($params)) {
            $params = $params + array_slice($request, 0);
        } else {
            $params = array_slice($request, 0);
        }
    }
}

switch ($mode) {
    case 1:
        $require_path .= 'user'.DIRECTORY_SEPARATOR;
        break;
}

if (file_exists($require_path.$controller.'.php')) {

    require_once $require_path.$controller.'.php';
    $controller = $controller.'_Controller';
    if (class_exists($controller)) {

        $object = new $controller($params, $action, 'ru_RU');
        $action = 'action_'.$action;

        $object->before();

        if (is_callable(array($object, $action))) {

            $object->$action();
            $object->after();
        }
    }
}
