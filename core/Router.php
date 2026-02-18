<?php

class Router
{
    public function run()
    {
        $urlParam = $_GET['url'] ?? 'home/index';
        $url = explode('/', trim($urlParam, '/'));

        $controllerBase = strtolower($url[0]);
        $controllerName = ucfirst($controllerBase) . 'Controller';
        $method = $url[1] ?? 'index'; // corrigido

        $controllerPath = __DIR__ . "/../app/Controllers/{$controllerName}.php";

        if (!file_exists($controllerPath)) {
            die("Controller não encontrado: {$controllerName}");
        }

        require_once $controllerPath;

        $controller = new $controllerName;

        if (!method_exists($controller, $method)) {
            die("Método não encontrado: {$method}");
        }

        $params = array_slice($url, 2);

        if (!empty($params)) {
            call_user_func_array([$controller, $method], $params);
        } else {
            call_user_func([$controller, $method]);
        }
    }
}


