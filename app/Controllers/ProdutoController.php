<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../Models/Produto.php';
require_once __DIR__ . '/../../core/AuthMiddleware.php';

class ProdutoController extends Controller
{
    public function index()
    {
        // Usa o middleware para verificar login
        AuthMiddleware::verificarLogin();

        $produtoModel = new Produto();
        $produtos = $produtoModel->listarTodosPorLoja($_SESSION['LOJA']['id']);

        $this->view('produto/index', [
            'produtos' => $produtos
        ]);
    }

    public function criar()
    {
        $this->view('produto/form');
    }

    public function salvar()
    {
        $produtoModel = new Produto();

    $dados = [
        'id_loja'           => $_SESSION['loja_id'],
        'nome'              => $_POST['nome'],
        'emoji'             => $_POST['emoji'],
        'valor_venda'       => str_replace(',', '.', str_replace('.', '', $_POST['valor_venda'])),
        'valor_compra'      => str_replace(',', '.', str_replace('.', '', $_POST['valor_compra'])),
        'controlar_estoque' => isset($_POST['controla_estoque']) ? 1 : 0,
        'estoque_atual'     => $_POST['estoque_atual'] ?? 0,
        'id_fornecedor'     => $_POST['id_fornecedor'] ?? null,
        'ativo'             => 1
    ];

        $produtoModel->criar($dados);

        header('Location: /produto');
        exit;
    }

    public function editar($id_produto)
    {
        $produtoModel = new Produto();
        $produto = $produtoModel->buscar($id_produto, $_SESSION['loja_id']);

        $this->view('produto/form', [
            'produto' => $produto
        ]);
    }

    public function atualizar($id)
    {
        $dados = [
            'id_produto'        => $id,
            'nome'              => $_POST['nome'],
            'emoji'             => $_POST['emoji'],
            'valor_venda'       => str_replace(',', '.', str_replace('.', '', $_POST['valor_venda'])),
            'valor_compra'      => str_replace(',', '.', str_replace('.', '', $_POST['valor_compra'])),
            'controlar_estoque' => isset($_POST['controlar_estoque']) ? 1 : 0,
            'estoque_atual'     => $_POST['estoque_atual'] ?? 0,
            'estoque_alerta'    => $_POST['estoque_alerta'] ?? 0,
            'ordem_exibicao'    => $_POST['ordem_exibicao'] ?? 0,
            'id_fornecedor'     => $_POST['id_fornecedor'] ?? null
        ];

        $produto = new Produto();
        $produto->atualizar($dados);

        header("Location: /produto");
        exit;
    }

    public function ativar($id) {
        $produto = new Produto();
        $produto->ativar($id, $_SESSION['loja_id']);
        header("Location: /produto");
        exit;
    }

    public function desativar($id_produto)
    {
        $produtoModel = new Produto();
        $produtoModel->desativar($id_produto, $_SESSION['loja_id']);

        header('Location: /produto');
        exit;
    }
    public function salvarOrdem()
    {
        $ordens = $_POST['ordem'] ?? [];

        // Verifica se existem números repetidos
        if (count($ordens) !== count(array_unique($ordens))) {
            $_SESSION['flash'] = "Erro: não pode haver números repetidos na ordem!";
            header("Location: /produto");
            exit;
        }

        $produtoModel = new Produto();
        foreach ($ordens as $id_produto => $ordem) {
            $produtoModel->atualizarOrdem($id_produto, $ordem);
        }

        $_SESSION['flash'] = "Ordem de produtos atualizada com sucesso!";
        header("Location: /produto");
        exit;
    }


}

