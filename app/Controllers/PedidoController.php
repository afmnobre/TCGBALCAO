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

        $clientes = $clienteModel->listarPorLoja($_SESSION['LOJA']['id_loja']);
        $produtos = $produtoModel->listarAtivosPorLoja($_SESSION['LOJA']['id_loja']);
        $cardgames = $pedidoModel->listarCardgames();
        $pedidos = $pedidoModel->listarPedidos();
        $tipos_pagamento = $pedidoModel->listarTiposPagamento();

        $hoje = date('Y-m-d');
        $dataSelecionada = $_GET['data'] ?? $hoje;

        $pedidos = $pedidoModel->listarPorLojaDataTodos($_SESSION['LOJA']['id_loja'], $dataSelecionada);

        // 🔹 Remove pedidos zerados (sem itens, valor 0 e não pago)
        foreach ($pedidos as $key => $p) {
            $itens = $pedidoModel->listarItensPorPedido($p['id_pedido']);
            $valorVariado = (float)($p['valor_variado'] ?? 0);

            $temItens = false;
            foreach ($itens as $item) {
                if ((int)$item['quantidade'] > 0) {
                    $temItens = true;
                    break;
                }
            }

            if (!$temItens && $valorVariado == 0 && $p['pedido_pago'] == 0) {
                $pedidoModel->excluir($p['id_pedido']);
                unset($pedidos[$key]);
            } else {
                $p['itens'] = $itens;
                $pedidos[$key] = $p;
            }
        }

        $pedidosPorCliente = [];
        foreach ($pedidos as $p) {
            $pedidosPorCliente[$p['id_cliente']][] = $p;
        }

        $mapaPrecos = [];
        foreach ($produtos as $prod) {
            $mapaPrecos[$prod['id_produto']] = (float)$prod['valor_venda'];
        }

        foreach ($clientes as &$cliente) {
            $cliente['cardgames'] = $pedidoModel->listarCardgamesPorCliente($cliente['id_cliente']);
            $id = $cliente['id_cliente'];
            $classeTotal = '';

            if (isset($pedidosPorCliente[$id])) {
                $pedido = reset($pedidosPorCliente[$id]);
                $valorTotal = (float)($pedido['valor_variado'] ?? 0);

                if (!empty($pedido['itens'])) {
                    foreach ($pedido['itens'] as $item) {
                        $idProd = $item['id_produto'];
                        $preco  = $mapaPrecos[$idProd] ?? 0;
                        $valorTotal += ($item['quantidade'] * $preco);
                    }
                }

                if ($valorTotal > 0) {
                    $classeTotal = ($pedido['pedido_pago'] == 1) ? 'total-pago' : 'total-aberto';
                }
            }

            $cliente['classe_total'] = $classeTotal;
        }
        unset($cliente);

        $clientesAbertos = [];
        $clientesPagos   = [];
        $clientesSem     = [];

        foreach ($clientes as $cliente) {
            $id = $cliente['id_cliente'];
            if (isset($pedidosPorCliente[$id])) {
                $pedido = reset($pedidosPorCliente[$id]);
                if ($pedido['pedido_pago'] == 0) {
                    $clientesAbertos[] = $cliente;
                } else {
                    $clientesPagos[] = $cliente;
                }
            } else {
                $clientesSem[] = $cliente;
            }
        }

        usort($clientesAbertos, fn($a, $b) => strcmp($a['nome'], $b['nome']));
        usort($clientesPagos, fn($a, $b) => strcmp($a['nome'], $b['nome']));
        usort($clientesSem, fn($a, $b) => strcmp($a['nome'], $b['nome']));

        $clientesOrdenados = array_merge($clientesAbertos, $clientesPagos, $clientesSem);

        $this->view('pedido/index', [
            'clientes'          => $clientesOrdenados,
            'produtos'          => $produtos,
            'pedidosPorCliente' => $pedidosPorCliente,
            'dataSelecionada'   => $dataSelecionada,
            'datasPendentes'    => $pedidoModel->listarDatasPendentes($_SESSION['LOJA']['id_loja']),
            'cardgames'         => $cardgames,
            'tipos_pagamento'   => $tipos_pagamento
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

        $loja = Loja::buscarPorId($_SESSION['LOJA']['id_loja']);

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
        $dataSelecionada = $dados['dataSelecionada'] ?? date('Y-m-d');

        foreach ($dados['itens'] as $idCliente => $produtos) {
            $idPedido = $dados['id_pedido'][$idCliente] ?? null;

            // 🔹 Normaliza valor variado
            $variado = $dados['variado'][$idCliente] ?? 0;
            $variado = str_replace(',', '.', $variado);
            if ($variado === '' || $variado === null) {
                $variado = 0;
            }
            $variado = (float)$variado;

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
                        'id_loja'           => $_SESSION['LOJA']['id_loja'],
                        'valor_variado'     => $variado,
                        'observacao_variado'=> $observacaoVariado,
                        'pedido_pago'       => $pago,
                        'itens'             => $produtos,
                        'data_pedido'       => $dataSelecionada
                    ]);
                }
            }
        }



$queryParams = ['data' => $dataSelecionada];

if (!empty($dados['cardgamesSelecionados'])) {
    // usa o mesmo nome "cardgames" que a view espera
    $queryParams['cardgames'] = $dados['cardgamesSelecionados'];
}

header("Location: /pedido/index?" . http_build_query($queryParams, '', '&'));
exit;

    }

public function salvarPagamento() {
    AuthMiddleware::verificarLogin();

    $pedidoModel = new Pedido();
    $dados = $_POST;

    $idCliente = (int)($dados['id_cliente'] ?? 0);
    $idPedido  = $dados['id_pedido'] ?? null;

    // 🔑 Correção: pegar os valores rateados do modal
    $pagamentosSelecionados = $dados['valor'] ?? [];

    // Normaliza valor variado
    $variado = $dados['variado'][$idCliente] ?? 0;
    $variado = str_replace('.', '', $variado);
    $variado = str_replace(',', '.', $variado);
    if ($variado === '' || $variado === null) {
        $variado = 0;
    }
    $variado = (float)$variado;

    // Observação
    $observacaoVariado = trim($dados['observacao_variado'][$idCliente] ?? '');

    if ($idPedido) {
        // Atualiza pedido existente
        $pedidoModel->atualizar($idPedido, [
            'valor_variado'      => $variado,
            'observacao_variado' => $observacaoVariado,
            'pedido_pago'        => 1
        ]);

        if (!empty($dados['itens'][$idCliente])) {
            $pedidoModel->atualizarItens($idPedido, $dados['itens'][$idCliente]);
        }

        if (!empty($pagamentosSelecionados)) {
            $pedidoModel->salvarTiposPagamento($idPedido, $pagamentosSelecionados);
        }
    } else {
        // 🔹 Cria novo pedido já como pago
        $novoId = $pedidoModel->salvar([
            'id_cliente'         => $idCliente,
            'id_loja'            => $_SESSION['LOJA']['id_loja'],
            'valor_variado'      => $variado,
            'observacao_variado' => $observacaoVariado,
            'pedido_pago'        => 1,
            'data_pedido'        => $dados['dataSelecionada'] ?? date('Y-m-d')
        ]);

        // 🔹 Grava itens vinculados ao novo pedido
        if (!empty($dados['itens'][$idCliente])) {
            $pedidoModel->atualizarItens($novoId, $dados['itens'][$idCliente]);
        }

        // 🔹 Grava métodos de pagamento com valor
        if (!empty($pagamentosSelecionados)) {
            $pedidoModel->salvarTiposPagamento($novoId, $pagamentosSelecionados);
        }
    }

    // Redireciona de volta para a tela de pedidos mantendo filtros
    $queryParams = ['data' => $dados['dataSelecionada'] ?? date('Y-m-d')];

    if (!empty($dados['cardgamesSelecionados'])) {
        foreach ($dados['cardgamesSelecionados'] as $cg) {
            $queryParams['cardgames'][] = $cg;
        }
    }

    header("Location: /pedido/index?" . http_build_query($queryParams, '', '&'));
    exit;
}




}
