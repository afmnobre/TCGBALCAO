<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/AuthAdmin.php';

// Aqui futuramente entra o login do admin
// understood: após login, $_SESSION['admin_id'] estará setado

$router = new Router();
$router->run();

