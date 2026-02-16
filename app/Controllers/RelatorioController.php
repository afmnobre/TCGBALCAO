<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/AuthMiddleware.php';

class RelatorioController extends Controller
{
    public function index()
    {
        AuthMiddleware::verificarLogin();

        $this->view('relatorio/index');
    }
}

