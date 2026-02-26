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
        $produtos = $produtoModel->listarTodosPorLoja($_SESSION['LOJA']['id_loja']);

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
            'id_loja'           => $_SESSION['LOJA']['id_loja'],
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
        $produto = $produtoModel->buscar($id_produto, $_SESSION['LOJA']['id_loja']);

        $this->view('produto/form', [
            'produto' => $produto
        ]);
    }

	public function atualizar($id)
	{
		$produtoModel = new Produto();

		// 1. Busca o produto atual para saber qual era a ordem dele
		$produtoAtual = $produtoModel->buscar($id, $_SESSION['LOJA']['id_loja']);

		$dados = [
			'id_produto'        => $id,
			'nome'              => $_POST['nome'],
			'emoji'             => $_POST['emoji'],
			'valor_venda'       => str_replace(',', '.', str_replace('.', '', $_POST['valor_venda'])),
			'valor_compra'      => str_replace(',', '.', str_replace('.', '', $_POST['valor_compra'])),
			'controlar_estoque' => isset($_POST['controlar_estoque']) ? 1 : 0,
			'estoque_atual'     => $_POST['estoque_atual'] ?? 0,
			'estoque_alerta'    => $_POST['estoque_alerta'] ?? 0,
			'id_fornecedor'     => $_POST['id_fornecedor'] ?? null,
			'id_loja'           => $_SESSION['LOJA']['id_loja'],
			// 2. Usa a ordem que já estava no banco de dados
			'ordem_exibicao'    => $produtoAtual['ordem_exibicao']
		];

		$produtoModel->atualizar($dados);

		header("Location: /produto");
		exit;
	}

    public function ativar($id)
    {
        $produto = new Produto();
        $produto->ativar($id, $_SESSION['LOJA']['id_loja']);
        header("Location: /produto");
        exit;
    }

    public function desativar($id_produto)
    {
        $produtoModel = new Produto();
        $produtoModel->desativar($id_produto, $_SESSION['LOJA']['id_loja']);

        header('Location: /produto');
        exit;
    }

	public function salvarOrdem()
	{
		// Recebe o array de IDs na nova ordem do formulário
		$ids_produtos = $_POST['id_produto'] ?? [];

		if (!empty($ids_produtos)) {
			$produtoModel = new Produto();
			$novaOrdem = 1;

			foreach ($ids_produtos as $id_produto) {
				$produtoModel->atualizarOrdem($id_produto, $novaOrdem);
				$novaOrdem++;
			}
			$_SESSION['flash'] = "Ordem atualizada com sucesso!";
		}

		header("Location: /produto");
		exit;
	}
}


