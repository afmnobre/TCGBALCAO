<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../Models/Loja.php';
require_once __DIR__ . '/../Models/Contrato.php';


class HomeController extends Controller
{
	public function index()
	{
		require_once __DIR__ . '/../Models/Loja.php';
		require_once __DIR__ . '/../Models/Contrato.php';

		$lojaModel = new Loja();
		$contratoModel = new Contrato();

		$lojas = $lojaModel->listar();
		$contratos = $contratoModel->listarAtivos();

		$totalLojas = $lojaModel->total();
		$totalContratos = $contratoModel->totalAtivos();

		ob_start();
		require __DIR__ . '/../Views/home.php';
		$content = ob_get_clean();

		$title = "Dashboard";

		require __DIR__ . '/../Views/layout/layout.php';
	}


}

