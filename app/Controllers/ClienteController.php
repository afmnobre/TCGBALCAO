<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/AuthMiddleware.php';
require_once __DIR__ . '/../Models/Cliente.php';

class ClienteController extends Controller
{
    public function index()
    {
        AuthMiddleware::verificarLogin();

        $clienteModel = new Cliente();
        $clientes = $clienteModel->listarPorLoja($_SESSION['LOJA']['id_loja']);

        // Carregar cardgames de cada cliente
        foreach ($clientes as &$cliente) {
            $cliente['cardgames'] = $clienteModel->listarCardgames($cliente['id_cliente']);
        }

        $this->view('cliente/index', [
            'clientes' => $clientes
        ]);
    }

    public function criar()
    {
        AuthMiddleware::verificarLogin();

        // Carrega todos os cardgames
        require_once __DIR__ . '/../Models/CardGame.php';
        $cardgameModel = new CardGame();
        $cardgames = $cardgameModel->listarTodos();

        $this->view('cliente/form', [
            'cardgames' => $cardgames
        ]);
    }

    public function editar($id)
    {
        AuthMiddleware::verificarLogin();

        require_once __DIR__ . '/../Models/CardGame.php';
        $cardgameModel = new CardGame();
        $cardgames = $cardgameModel->listarTodos();

        $clienteModel = new Cliente();
        $cliente = $clienteModel->buscar($id, $_SESSION['LOJA']['id_loja']);

        // Carregar cardgames já vinculados ao cliente
        $cardgamesCliente = $clienteModel->listarCardgames($id);

        $this->view('cliente/form', [
            'cliente'          => $cliente,
            'cardgames'        => $cardgames,
            'cardgamesCliente' => $cardgamesCliente
        ]);
    }

    public function salvar()
    {
        $clienteModel = new Cliente();

        $dadosCliente = [
            'nome'     => $_POST['nome'],
            'email'    => $_POST['email'],
            'telefone' => $_POST['telefone']
        ];

        $id_loja   = $_SESSION['LOJA']['id_loja'];
        $cardgames = $_POST['cardgames'] ?? [];

        $resultado = $clienteModel->criarOuVincular($dadosCliente, $id_loja, $cardgames);

        if ($resultado['novo']) {
            $_SESSION['flash'] = "Cliente cadastrado com sucesso!";
        } else {
            $_SESSION['flash'] = "Cliente já existia, apenas vinculado à loja.";
        }

        header('Location: /cliente');
        exit;
    }

    public function atualizar($id)
    {
        $clienteModel = new Cliente();

        $dadosCliente = [
            'nome'     => $_POST['nome'],
            'email'    => $_POST['email'],
            'telefone' => $_POST['telefone']
        ];

        $cardgames = $_POST['cardgames'] ?? [];

        $clienteModel->atualizar($id, $dadosCliente, $cardgames);

        $_SESSION['flash'] = "Cliente atualizado com sucesso!";
        header('Location: /cliente');
        exit;
    }

    public function excluir($id)
    {
        $clienteModel = new Cliente();
        $clienteModel->excluir($id, $_SESSION['LOJA']['id_loja']);

        header('Location: /cliente');
        exit;
    }
}

