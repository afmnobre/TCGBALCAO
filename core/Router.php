<?php

class Router
{
    public function run()
    {
        $url = $_GET['url'] ?? 'home/index';
        $url = explode('/', trim($url, '/'));

        // DEBUG VISUAL (remover depois)
        echo "<div style='background:#000;color:#0f0;padding:5px;font-size:12px'>
        URL: ".implode('/', $url)."</div>";

        $controllerBase = strtolower($url[0]);
        $controllerName = ucfirst($controllerBase) . 'Controller';
        $method = $url[1] ?? 'index';

        echo "<div style='background:#000;color:#0ff;padding:5px;font-size:12px'>
        Controller: {$controllerName} | Método: {$method}</div>";

        $controllerPath = __DIR__ . "/../app/Controllers/{$controllerName}.php";

        if (!file_exists($controllerPath)) {
            die("Controller não encontrado: {$controllerName}");
        }

        require_once $controllerPath;

        $controller = new $controllerName;

        if (!method_exists($controller, $method)) {
            die("Método não encontrado: {$method}");
        }

        // Captura parâmetros adicionais da URL (ex: /produtos/editar/3)
        $params = array_slice($url, 2);

        if (!empty($params)) {
            call_user_func_array([$controller, $method], $params);
        } else {
            call_user_func([$controller, $method]);
        }
    }
}

