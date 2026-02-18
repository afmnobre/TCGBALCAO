<?php

require_once __DIR__ . '/../../core/Database.php';
use Core\Database;

class Pedido
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function listarPorLojaData($id_loja, $dataSelecionada)
    {
        $sql = "SELECT * FROM pedidos
                WHERE id_loja = :id_loja
                  AND data_pedido = :data_pedido
                  AND pedido_pago = 1
                ORDER BY id_pedido ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_loja' => $id_loja,
            'data_pedido' => $dataSelecionada
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarPorLojaDataTodos($id_loja, $dataSelecionada)
    {
        $sql = "SELECT * FROM pedidos
                WHERE id_loja = :id_loja
                  AND data_pedido = :data_pedido
                ORDER BY id_pedido ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_loja' => $id_loja,
            'data_pedido' => $dataSelecionada
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarPorLojaDataPagos($id_loja, $dataSelecionada)
    {
        $sql = "SELECT * FROM pedidos
                WHERE id_loja = :id_loja
                  AND data_pedido = :data_pedido
                  AND pedido_pago = 1
                ORDER BY id_pedido ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_loja' => $id_loja,
            'data_pedido' => $dataSelecionada
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarDatasPendentes($id_loja)
    {
        $sql = "SELECT DISTINCT DATE(p.data_pedido) as data
                FROM pedidos p
                LEFT JOIN pedidos_itens pi ON pi.id_pedido = p.id_pedido
                WHERE p.id_loja = :id_loja
                  AND p.pedido_pago = 0
                  AND (pi.quantidade > 0 OR p.valor_variado > 0)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_loja' => $id_loja]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function salvar($dados)
    {
        $stmtCheck = $this->db->prepare("
            SELECT id_pedido FROM pedidos
            WHERE id_cliente = :id_cliente
              AND id_loja = :id_loja
              AND data_pedido = :data_pedido
            LIMIT 1
        ");
        $stmtCheck->execute([
            'id_cliente' => $dados['id_cliente'],
            'id_loja'    => $dados['id_loja'],
            'data_pedido'=> $dados['data_pedido']
        ]);
        $id_pedido_existente = $stmtCheck->fetchColumn();

        if ($id_pedido_existente) {
            $stmt = $this->db->prepare("
                UPDATE pedidos SET
                    valor_variado = :valor_variado,
                    observacao_variado = :observacao_variado,
                    pedido_pago   = :pedido_pago
                WHERE id_pedido = :id_pedido
            ");
            $stmt->execute([
                'valor_variado'      => $dados['valor_variado'],
                'observacao_variado' => $dados['observacao_variado'] ?? null,
                'pedido_pago'        => $dados['pedido_pago'],
                'id_pedido'          => $id_pedido_existente
            ]);

            $this->atualizarItens($id_pedido_existente, $dados['itens']);
            return $id_pedido_existente;
        } else {
            $stmt = $this->db->prepare("
                INSERT INTO pedidos (id_cliente, id_loja, valor_variado, observacao_variado, pedido_pago, data_pedido)
                VALUES (:id_cliente, :id_loja, :valor_variado, :observacao_variado, :pedido_pago, :data_pedido)
            ");
            $stmt->execute([
                'id_cliente'        => $dados['id_cliente'],
                'id_loja'           => $dados['id_loja'],
                'valor_variado'     => $dados['valor_variado'],
                'observacao_variado'=> $dados['observacao_variado'] ?? null,
                'pedido_pago'       => $dados['pedido_pago'],
                'data_pedido'       => $dados['data_pedido']
            ]);

            $id_pedido = $this->db->lastInsertId();
            $this->atualizarItens($id_pedido, $dados['itens']);
            return $id_pedido;
        }
    }

public function atualizarItens($id_pedido, $itens) {
    // antes de deletar, recuperar itens antigos para devolver estoque
    $stmtOld = $this->db->prepare("SELECT id_produto, quantidade FROM pedidos_itens WHERE id_pedido = :id_pedido");
    $stmtOld->execute(['id_pedido' => $id_pedido]);
    $itensAntigos = $stmtOld->fetchAll(PDO::FETCH_ASSOC);

    $produtoModel = new Produto();
    foreach ($itensAntigos as $itemAntigo) {
        if ($itemAntigo['quantidade'] > 0) {
            $produtoModel->atualizarEstoque($itemAntigo['id_produto'], $itemAntigo['quantidade']); // devolve estoque
        }
    }

    // limpa itens
    $stmtDel = $this->db->prepare("DELETE FROM pedidos_itens WHERE id_pedido = :id_pedido");
    $stmtDel->execute(['id_pedido' => $id_pedido]);

    // insere novos itens e retira do estoque
    foreach ($itens as $id_produto => $quantidade) {
        if ($quantidade > 0) {
            $stmtProd = $this->db->prepare("SELECT valor_venda FROM produtos WHERE id_produto = :id_produto");
            $stmtProd->execute(['id_produto' => $id_produto]);
            $valor_unitario = $stmtProd->fetchColumn();

            $stmtItem = $this->db->prepare("
                INSERT INTO pedidos_itens (id_pedido, id_produto, quantidade, valor_unitario)
                VALUES (:id_pedido, :id_produto, :quantidade, :valor_unitario)
            ");
            $stmtItem->execute([
                'id_pedido'      => $id_pedido,
                'id_produto'     => $id_produto,
                'quantidade'     => $quantidade,
                'valor_unitario' => $valor_unitario
            ]);

            $produtoModel->atualizarEstoque($id_produto, -$quantidade); // retira estoque
        }
    }
}


    public function zerarPedido($id_pedido)
    {
        $stmt = $this->db->prepare("
            UPDATE pedidos SET
                valor_variado = 0,
                observacao_variado = NULL,
                pedido_pago = 0
            WHERE id_pedido = :id_pedido
        ");
        $stmt->execute(['id_pedido' => $id_pedido]);

        $stmtItens = $this->db->prepare("
            UPDATE pedidos_itens SET quantidade = 0
            WHERE id_pedido = :id_pedido
        ");
        $stmtItens->execute(['id_pedido' => $id_pedido]);
    }

public function excluir($id_pedido)
{
    // devolve estoque antes de excluir
    $stmtItens = $this->db->prepare("SELECT id_produto, quantidade FROM pedidos_itens WHERE id_pedido = :id_pedido");
    $stmtItens->execute(['id_pedido' => $id_pedido]);
    $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

    $produtoModel = new Produto();
    foreach ($itens as $item) {
        if ($item['quantidade'] > 0) {
            $produtoModel->atualizarEstoque($item['id_produto'], $item['quantidade']); // devolve estoque
        }
    }

    // exclui itens
    $stmtDelItens = $this->db->prepare("DELETE FROM pedidos_itens WHERE id_pedido = :id_pedido");
    $stmtDelItens->execute(['id_pedido' => $id_pedido]);

    // exclui pagamentos vinculados
    $stmtDelPagamentos = $this->db->prepare("DELETE FROM pedido_pagamento WHERE id_pedido = :id_pedido");
    $stmtDelPagamentos->execute(['id_pedido' => $id_pedido]);

    // exclui pedido
    $stmtPedido = $this->db->prepare("DELETE FROM pedidos WHERE id_pedido = :id_pedido");
    $stmtPedido->execute(['id_pedido' => $id_pedido]);
}



    public function buscarPorIdRecibo($id_pedido)
    {
        $sql = "SELECT
                    p.id_pedido,
                    p.data_pedido,
                    p.valor_variado,
                    p.observacao_variado,
                    p.pedido_pago,
                    c.nome
                FROM pedidos p
                JOIN clientes c ON c.id_cliente = p.id_cliente
                WHERE p.id_pedido = :id_pedido";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_pedido' => $id_pedido]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarItensPorRecibo($id_pedido)
    {
        $sql = "SELECT
                    pr.nome,
                    pi.quantidade,
                    pi.valor_unitario
                FROM pedidos_itens pi
                JOIN produtos pr ON pr.id_produto = pi.id_produto
                WHERE pi.id_pedido = :id_pedido
                  AND pi.quantidade > 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_pedido' => $id_pedido]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarItensPorPedido($id_pedido)
    {
        $sql = "SELECT pi.id_item, pi.id_produto, pi.quantidade, pi.valor_unitario, pr.nome
                FROM pedidos_itens pi
                JOIN produtos pr ON pr.id_produto = pi.id_produto
                WHERE pi.id_pedido = :id_pedido";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_pedido' => $id_pedido]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizar($id_pedido, $dados)
    {
        $stmt = $this->db->prepare("
            UPDATE pedidos SET
                valor_variado = :valor_variado,
                observacao_variado = :observacao_variado,
                pedido_pago = :pedido_pago
            WHERE id_pedido = :id_pedido
        ");

        $stmt->execute([
            'valor_variado'      => (float)($dados['valor_variado'] ?? 0),
            'observacao_variado' => $dados['observacao_variado'] ?? null,
            'pedido_pago'        => (int)($dados['pedido_pago'] ?? 0),
            'id_pedido'          => (int)$id_pedido
        ]);

    }


    /**
     * Lista todos os cardgames cadastrados
     */
    public function listarCardgames()
    {
        $sql = "SELECT id_cardgame, nome, imagem_fundo_card, imagem_card_game
                FROM cardgames
                ORDER BY nome ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lista os cardgames vinculados a um cliente
     */
    public function listarCardgamesPorCliente($id_cliente)
    {
        $sql = "SELECT cg.id_cardgame
                FROM clientes_cardgames cc
                JOIN cardgames cg ON cg.id_cardgame = cc.id_cardgame
                WHERE cc.id_cliente = :id_cliente";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_cliente' => $id_cliente]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
     public function listarTiposPagamento() {
     $sql = "SELECT * FROM tipos_pagamento ORDER BY nome ASC";
     $stmt = $this->db->query($sql);
     return $stmt->fetchAll(PDO::FETCH_ASSOC);
     }
        // Buscar todos os pedidos
    public function listarPedidos() {
        $sql = "SELECT * FROM pedidos ORDER BY id_pedido DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function salvarTiposPagamento($idPedido, $pagamentos) {
    $sqlDelete = "DELETE FROM pedido_pagamento WHERE id_pedido = :id_pedido";
    $stmtDelete = $this->db->prepare($sqlDelete);
    $stmtDelete->bindValue(':id_pedido', $idPedido, PDO::PARAM_INT);
    $stmtDelete->execute();

    $sqlInsert = "INSERT INTO pedido_pagamento (id_pedido, id_pagamento) VALUES (:id_pedido, :id_pagamento)";
    $stmtInsert = $this->db->prepare($sqlInsert);

    foreach ($pagamentos as $idPagamento) {
        $stmtInsert->bindValue(':id_pedido', $idPedido, PDO::PARAM_INT);
        $stmtInsert->bindValue(':id_pagamento', $idPagamento, PDO::PARAM_INT);
        $stmtInsert->execute();
    }
}

}

