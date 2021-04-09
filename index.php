<?php
    require_once('vendor/autoload.php');

    $requestURI = preg_split('/\/|\?/', $_SERVER['REQUEST_URI']);
    $conrollerName = (isset($requestURI[1]) && $requestURI[1] != "") ? $requestURI[1] : 'blog';
    $actionName = (isset($requestURI[2]) && $requestURI[2] !== "") ? $requestURI[2] : 'index';
    $controllerPath = 'controllers/' . ucfirst($conrollerName) . 'Controller.php';

    try {
        if (file_exists($controllerPath)) {
            $controllerClassName = '\\controllers\\' . ucfirst($conrollerName) . 'Controller';
            $controller = new $controllerClassName;
            $methodName = 'action' . ucfirst($actionName);
            if (method_exists($controller, $methodName)) {
                $controller->$methodName();
            } else {
                throw new Exception ("Method $methodName in controller class $controllerClassName not found in file $controllerPath");
            }
        } else {
            throw new Exception ("Controller file not found file name: $controllerPath");
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }