<?php
require_once __DIR__ . '/../../core/AuthMiddleware.php';

class HomeController extends Controller
{
    public function index()
    {
        AuthMiddleware::verificarLogin();
        $this->view('home');
    }
}

