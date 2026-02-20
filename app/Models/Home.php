<?php
require_once __DIR__ . '/../../core/Database.php';

use Core\Database; // importa a classe com namespace

class Home
{
    private $db;

    public function __construct()
    {
        // chama corretamente a classe com namespace
        $this->db = Database::getInstance();
    }

    public function clientesInativos($idLoja)
    {
        $sql = "SELECT
                    C.id_cliente,
                    C.nome,
                    C.telefone,
                    MAX(P.data_pedido) AS ultima_compra,
                    COUNT(P.id_pedido) AS total_pedidos,
                    COALESCE(SUM(P.valor_variado), 0) AS total_gasto
                FROM clientes C
                INNER JOIN clientes_lojas CL
                    ON C.id_cliente = CL.id_cliente
                    AND CL.id_loja = :id_loja
                    AND CL.status = 'ativo'
                LEFT JOIN pedidos P
                    ON P.id_cliente = C.id_cliente
                    AND P.id_loja = CL.id_loja
                GROUP BY C.id_cliente, C.nome, C.telefone
                HAVING (MAX(P.data_pedido) IS NULL
                    OR MAX(P.data_pedido) < DATE_SUB(CURDATE(), INTERVAL 2 MONTH))
                ORDER BY ultima_compra ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_loja' => $idLoja]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}


