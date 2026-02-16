<?php

require_once __DIR__ . '/../../core/Database.php';

use Core\Database;

class Cliente
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function listarPorLoja($id_loja)
    {
        $sql = "SELECT * FROM clientes_lojas cl
                INNER JOIN clientes c ON c.id_cliente = cl.id_cliente
                WHERE cl.id_loja = :id_loja
                ORDER BY c.nome ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_loja' => $id_loja]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criarOuVincular($dadosCliente, $id_loja, $cardgames)
    {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE telefone = :telefone");
        $stmt->execute(['telefone' => $dadosCliente['telefone']]);
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        $novo = false;

        if ($cliente) {
            $id_cliente = $cliente['id_cliente'];

            $stmt2 = $this->db->prepare("
                INSERT IGNORE INTO clientes_lojas (id_cliente, id_loja, status)
                VALUES (:id_cliente, :id_loja, 'ativo')
            ");
            $stmt2->execute([
                'id_cliente' => $id_cliente,
                'id_loja'    => $id_loja
            ]);
        } else {
            $stmt = $this->db->prepare("
                INSERT INTO clientes (nome, email, telefone)
                VALUES (:nome, :email, :telefone)
            ");
            $stmt->execute($dadosCliente);
            $id_cliente = $this->db->lastInsertId();

            $stmt2 = $this->db->prepare("
                INSERT INTO clientes_lojas (id_cliente, id_loja, status)
                VALUES (:id_cliente, :id_loja, 'ativo')
            ");
            $stmt2->execute([
                'id_cliente' => $id_cliente,
                'id_loja'    => $id_loja
            ]);

            $novo = true;
        }

        foreach ($cardgames as $id_cardgame) {
            $stmt3 = $this->db->prepare("
                INSERT IGNORE INTO clientes_cardgames (id_cliente, id_cardgame)
                VALUES (:id_cliente, :id_cardgame)
            ");
            $stmt3->execute([
                'id_cliente'  => $id_cliente,
                'id_cardgame' => $id_cardgame
            ]);
        }

        return ['id_cliente' => $id_cliente, 'novo' => $novo];
    }

    public function buscar($id, $id_loja)
    {
        $sql = "
            SELECT c.*
            FROM clientes c
            INNER JOIN clientes_lojas cl ON c.id_cliente = cl.id_cliente
            WHERE c.id_cliente = :id
              AND cl.id_loja = :id_loja
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id'      => $id,
            'id_loja' => $id_loja
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $dadosCliente, $cardgames)
    {
        $stmt = $this->db->prepare("
            UPDATE clientes
            SET nome = :nome, email = :email, telefone = :telefone
            WHERE id_cliente = :id
        ");
        $stmt->execute([
            'nome'     => $dadosCliente['nome'],
            'email'    => $dadosCliente['email'],
            'telefone' => $dadosCliente['telefone'],
            'id'       => $id
        ]);

        $stmtDel = $this->db->prepare("DELETE FROM clientes_cardgames WHERE id_cliente = :id_cliente");
        $stmtDel->execute(['id_cliente' => $id]);

        foreach ($cardgames as $id_cardgame) {
            $stmtIns = $this->db->prepare("
                INSERT INTO clientes_cardgames (id_cliente, id_cardgame)
                VALUES (:id_cliente, :id_cardgame)
            ");
            $stmtIns->execute([
                'id_cliente'  => $id,
                'id_cardgame' => $id_cardgame
            ]);
        }

        return true;
    }

    public function excluir($id, $id_loja)
    {
        $stmt = $this->db->prepare("
            DELETE FROM clientes_lojas
            WHERE id_cliente = :id AND id_loja = :id_loja
        ");
        $stmt->execute([
            'id'      => $id,
            'id_loja' => $id_loja
        ]);

        $stmt2 = $this->db->prepare("
            DELETE FROM clientes_cardgames
            WHERE id_cliente = :id
        ");
        $stmt2->execute(['id' => $id]);

        $stmt3 = $this->db->prepare("
            SELECT COUNT(*) FROM clientes_lojas WHERE id_cliente = :id
        ");
        $stmt3->execute(['id' => $id]);
        $total = $stmt3->fetchColumn();

        if ($total == 0) {
            $stmt4 = $this->db->prepare("DELETE FROM clientes WHERE id_cliente = :id");
            $stmt4->execute(['id' => $id]);
        }
    }

    public function listarCardgames($id_cliente)
    {
        $sql = "
            SELECT cg.*
            FROM cardgames cg
            INNER JOIN clientes_cardgames ccg ON cg.id_cardgame = ccg.id_cardgame
            WHERE ccg.id_cliente = :id_cliente
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_cliente' => $id_cliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

