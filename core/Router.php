<?php
require_once __DIR__ . '/Autoload.php';

class Router
{
	public function run()
	{
		$urlParam = $_GET['url'] ?? '';
		$url = explode('/', trim($urlParam, '/'));

		$isAdmin = false;

		// Detecta prefixo admin
		if (!empty($url[0]) && strtolower($url[0]) === 'admin') {
			$isAdmin = true;
			array_shift($url); // remove 'admin'
		}

		// Se depois de remover admin não sobrar nada
		if (empty($url[0])) {
			$controllerBase = $isAdmin ? 'auth' : 'home';
		} else {
			$controllerBase = strtolower($url[0]);
		}

		$controllerName = ucfirst($controllerBase) . 'Controller';
		$method = $url[1] ?? 'index';

		// Define caminho correto
		if ($isAdmin) {
			$controllerPath = __DIR__ . "/../admin/Controllers/{$controllerName}.php";
		} else {
			$controllerPath = __DIR__ . "/../app/Controllers/{$controllerName}.php";
		}

		if (!file_exists($controllerPath)) {
			die("Controller não encontrado: {$controllerName}");
		}

		require_once $controllerPath;

		$controller = new $controllerName;


		// Middleware automático

		require_once __DIR__ . '/AuthAdmin.php';
		require_once __DIR__ . '/AuthLoja.php';

		// Rotas públicas que não exigem login
		$rotasPublicasApp = ['auth'];
		$rotasPublicasAdmin = ['auth'];

		if ($isAdmin) {

			if (!in_array(strtolower($controllerBase), $rotasPublicasAdmin)) {
				AuthAdmin::verificarLogin();
			}

		} else {

			if (!in_array(strtolower($controllerBase), $rotasPublicasApp)) {
				AuthLoja::verificarLogin();
			}
		}

		if (!method_exists($controller, $method)) {
			die("Método não encontrado: {$method}");
		}

		$params = array_slice($url, 2);

		call_user_func_array([$controller, $method], $params);
	}

}

