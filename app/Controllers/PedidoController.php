<?php
require_once __DIR__ . '/../Models/Pedido.php';
require_once __DIR__ . '/../Models/Cliente.php';
require_once __DIR__ . '/../Models/Produto.php';
require_once __DIR__ . '/../Models/Loja.php';
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/AuthMiddleware.php';

class PedidoController extends Controller
{

    public function index()
    {
        AuthMiddleware::verificarLogin();

        $pedidoModel  = new Pedido();
        $clienteModel = new Cliente();
        $produtoModel = new Produto();

        $clientes = $clienteModel->listarPorLoja($_SESSION['LOJA']['id']);
        $produtos = $produtoModel->listarAtivosPorLoja($_SESSION['LOJA']['id']);
        $cardgames = $pedidoModel->listarCardgames(); // 🔹 lista todos os cardgames

        $hoje = date('Y-m-d');
        $dataSelecionada = $_GET['data'] ?? $hoje;

        $pedidos = $pedidoModel->listarPorLojaDataTodos($_SESSION['LOJA']['id'], $dataSelecionada);

        $pedidosPorCliente = [];
        foreach ($pedidos as $p) {
            $p['itens'] = $pedidoModel->listarItensPorPedido($p['id_pedido']);
            $pedidosPorCliente[$p['id_cliente']][] = $p;
        }

        // 🔹 Vincula cardgames a cada cliente
        foreach ($clientes as &$cliente) {
            $cliente['cardgames'] = $pedidoModel->listarCardgamesPorCliente($cliente['id_cliente']);
        }

        $this->view('pedido/index', [
            'clientes'          => $clientes,
            'produtos'          => $produtos,
            'pedidosPorCliente' => $pedidosPorCliente,
            'dataSelecionada'   => $dataSelecionada,
            'datasPendentes'    => $pedidoModel->listarDatasPendentes($_SESSION['LOJA']['id']),
            'cardgames'         => $cardgames
        ]);
    }

    public function recibo($id)
    {
        AuthMiddleware::verificarLogin();

        $pedidoModel = new Pedido();
        $pedido = $pedidoModel->buscarPorIdRecibo($id);
        $itens  = $pedidoModel->listarItensPorRecibo($id);

        if (!$pedido) {
            die('Pedido não encontrado');
        }

        // Buscar dados completos da loja
        $loja = Loja::buscarPorId($_SESSION['LOJA']['id']);

        $this->rawView('pedido/recibo', [
            'pedido' => $pedido,
            'itens'  => $itens,
            'loja'   => $loja
        ]);
    }

    public function salvar() {
        AuthMiddleware::verificarLogin();

        $pedidoModel = new Pedido();
        $dados = $_POST;

        // Data selecionada do calendário
        $dataSelecionada = $dados['dataSelecionada'] ?? date('Y-m-d');

        foreach ($dados['itens'] as $idCliente => $produtos) {
            $idPedido = $dados['id_pedido'][$idCliente] ?? null;
            $variado = str_replace(',', '.', $dados['variado'][$idCliente] ?? 0);
            $pago = isset($dados['pago'][$idCliente]) ? 1 : 0;
            $observacaoVariado = $dados['observacao_variado'][$idCliente] ?? null;

            $temValores = false;
            foreach ($produtos as $qtd) {
                if ($qtd > 0) { $temValores = true; break; }
            }
            if ($variado > 0) $temValores = true;

            if ($idPedido) {
                if ($temValores) {
                    $pedidoModel->atualizar($idPedido, [
                        'valor_variado'      => $variado,
                        'observacao_variado' => $observacaoVariado,
                        'pedido_pago'        => $pago
                    ]);
                    $pedidoModel->atualizarItens($idPedido, $produtos);
                } else {
                    $pedidoModel->zerarPedido($idPedido);
                    if ($_SESSION['USUARIO']['perfil'] === 'GERENTE') {
                        $pedidoModel->excluir($idPedido);
                    }
                }
            } else {
                if ($temValores) {
                    $pedidoModel->salvar([
                        'id_cliente'        => $idCliente,
                        'id_loja'           => $_SESSION['LOJA']['id'],
                        'valor_variado'     => $variado,
                        'observacao_variado'=> $observacaoVariado,
                        'pedido_pago'       => $pago,
                        'itens'             => $produtos,
                        'data_pedido'       => $dataSelecionada
                    ]);
                }
            }
        }

        // 🔹 Mantém também os filtros de cardgames selecionados
        $queryParams = ['data' => $dataSelecionada];

        // Se houver cardgames selecionados no POST (hidden inputs), preserva
        if (!empty($dados['cardgamesSelecionados'])) {
            $queryParams['cardgames'] = $dados['cardgamesSelecionados'];
        }

        header("Location: /pedido/index?" . http_build_query($queryParams));
    }
}


