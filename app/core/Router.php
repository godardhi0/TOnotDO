<?php

class Router
{
    public function dispatch()
    {
        $url = isset($_GET['url']) ? explode('/', $_GET['url']) : [];

        $controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'AuthController';
        $method = $url[1] ?? 'login';
        $params = array_slice($url, 2);

        $controllerFile = "../app/controllers/$controllerName.php";

        if (!file_exists($controllerFile)) {
            die("Controller not found: $controllerName");
        }

        require_once $controllerFile;
        $controller = new $controllerName();

        if (!method_exists($controller, $method)) {
            die("Method not found: $method");
        }

        call_user_func_array([$controller, $method], $params);
    }
}
