<?php

class Router
{
    public function dispatch()
    {
        // Parse l'URL
        //
        $url = isset($_GET['url']) ? explode('/', $_GET['url']) : [];

        $controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'AuthController';
        $method = $url[1] ?? 'login';
        $params = array_slice($url, 2);

        $controllerFile = "../app/controllers/$controllerName.php";

        if (!file_exists($controllerFile)) {
            die("Fichier contrôleur introuvable: $controllerName");
        }

        require_once $controllerFile;

        $controller = new $controllerName();

        if (!method_exists($controller, $method)) {
            die("Méthode introuvable: $method");
        }

        call_user_func_array([$controller, $method], $params);
    }
}
