<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';

$uri = trim($_SERVER['REQUEST_URI'], '/');

if ($uri === 'login') {
    require '../app/Controllers/AuthController.php';
    (new AuthController)->login();
    exit;
}

if ($uri === 'autenticar') {
    require '../app/Controllers/AuthController.php';
    (new AuthController)->autenticar();
    exit;
}

if ($uri === 'logout') {
    require '../app/Controllers/AuthController.php';
    (new AuthController)->logout();
    exit;
}

$router = new Router();
$router->run();

