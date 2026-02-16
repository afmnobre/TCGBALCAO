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
            // exclui do banco
            $pedidoModel->excluir($p['id_pedido']);
            unset($pedidos[$key]); // remove do array
        } else {
            $p['itens'] = $itens;
            $pedidos[$key] = $p;
        }
    }

    $pedidosPorCliente = [];
    foreach ($pedidos as $p) {
        $pedidosPorCliente[$p['id_cliente']][] = $p;
    }

    // cria mapa de preços por produto
    $mapaPrecos = [];
    foreach ($produtos as $prod) {
        $mapaPrecos[$prod['id_produto']] = (float)$prod['valor_venda'];
    }

    // Vincula cardgames e calcula classe de total
    foreach ($clientes as &$cliente) {
        $cliente['cardgames'] = $pedidoModel->listarCardgamesPorCliente($cliente['id_cliente']);

        $id = $cliente['id_cliente'];
        $classeTotal = '';

        if (isset($pedidosPorCliente[$id])) {
            $pedido = reset($pedidosPorCliente[$id]);

            // calcula o valor total do pedido (variado + itens)
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

    // Separar clientes em grupos
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

    // Ordenar alfabeticamente cada grupo
    usort($clientesAbertos, fn($a, $b) => strcmp($a['nome'], $b['nome']));
    usort($clientesPagos, fn($a, $b) => strcmp($a['nome'], $b['nome']));
    usort($clientesSem, fn($a, $b) => strcmp($a['nome'], $b['nome']));

    // Juntar na ordem desejada
    $clientesOrdenados = array_merge($clientesAbertos, $clientesPagos, $clientesSem);

    $this->view('pedido/index', [
        'clientes'          => $clientesOrdenados,
        'produtos'          => $produtos,
        'pedidosPorCliente' => $pedidosPorCliente,
        'dataSelecionada'   => $dataSelecionada,
        'datasPendentes'    => $pedidoModel->listarDatasPendentes($_SESSION['LOJA']['id_loja']),
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

        // 🔹 Mantém também os filtros de cardgames selecionados
        $queryParams = ['data' => $dataSelecionada];

        // Se houver cardgames selecionados no POST (hidden inputs), preserva
        if (!empty($dados['cardgamesSelecionados'])) {
            $queryParams['cardgames'] = $dados['cardgamesSelecionados'];
        }

        header("Location: /pedido/index?" . http_build_query($queryParams));
    }
}


