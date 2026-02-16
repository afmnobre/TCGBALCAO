<?php
require_once __DIR__ . '/../../core/Database.php';
use Core\Database;

class Relatorio
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // 🔹 MÉDIAS DE VENDAS
    public function mediaVendasDia($id_loja) {
        $sql = "SELECT DATE(data_pedido) AS dia, AVG(valor_variado) AS media_dia
                FROM pedidos
                WHERE id_loja = :id_loja AND pedido_pago = 1
                GROUP BY DATE(data_pedido)
                ORDER BY dia ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_loja' => $id_loja]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function mediaVendasSemana($id_loja) {
        $sql = "SELECT YEARWEEK(data_pedido) AS semana, AVG(valor_variado) AS media_semana
                FROM pedidos
                WHERE id_loja = :id_loja AND pedido_pago = 1
                GROUP BY YEARWEEK(data_pedido)
                ORDER BY semana ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_loja' => $id_loja]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function mediaVendasMes($id_loja) {
        $sql = "SELECT YEAR(data_pedido) AS ano, MONTH(data_pedido) AS mes, AVG(valor_variado) AS media_mes
                FROM pedidos
                WHERE id_loja = :id_loja AND pedido_pago = 1
                GROUP BY ano, mes
                ORDER BY ano DESC, mes ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_loja' => $id_loja]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function mediaVendasAno($id_loja) {
        $sql = "SELECT YEAR(data_pedido) AS ano, AVG(valor_variado) AS media_ano
                FROM pedidos
                WHERE id_loja = :id_loja AND pedido_pago = 1
                GROUP BY ano
                ORDER BY ano DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_loja' => $id_loja]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    // 🔹 TOP CLIENTES
    public function topClientes($id_loja) {
        $sql = "SELECT c.nome, SUM(p.valor_variado) AS total
                FROM pedidos p
                JOIN clientes c ON c.id_cliente = p.id_cliente
                WHERE p.id_loja = :id_loja AND p.pedido_pago = 1
                GROUP BY c.id_cliente
                ORDER BY total DESC
                LIMIT 5";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_loja' => $id_loja]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function topClientesPorMes($id_loja, $ano) {
        $sql = "SELECT c.nome, SUM(p.valor_variado) AS total, MONTH(p.data_pedido) AS mes
                FROM pedidos p
                JOIN clientes c ON c.id_cliente = p.id_cliente
                WHERE p.id_loja = :id_loja AND p.pedido_pago = 1
                  AND YEAR(p.data_pedido) = :ano
                GROUP BY c.id_cliente, mes
                ORDER BY mes ASC, total DESC
                LIMIT 5";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_loja' => $id_loja, 'ano' => $ano]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function topClientesPorAno($id_loja, $ano) {
        $sql = "SELECT c.nome, SUM(p.valor_variado) AS total
                FROM pedidos p
                JOIN clientes c ON c.id_cliente = p.id_cliente
                WHERE p.id_loja = :id_loja AND p.pedido_pago = 1
                  AND YEAR(p.data_pedido) = :ano
                GROUP BY c.id_cliente
                ORDER BY total DESC
                LIMIT 5";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_loja' => $id_loja, 'ano' => $ano]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    // 🔹 TOP PRODUTOS
    public function topProdutos($id_loja) {
        $sql = "SELECT pr.nome, SUM(pi.quantidade) AS total_vendido
                FROM pedidos_itens pi
                JOIN produtos pr ON pr.id_produto = pi.id_produto
                JOIN pedidos p ON p.id_pedido = pi.id_pedido
                WHERE p.id_loja = :id_loja AND p.pedido_pago = 1
                GROUP BY pr.id_produto
                ORDER BY total_vendido DESC
                LIMIT 5";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_loja' => $id_loja]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function topProdutosPorMes($id_loja, $ano) {
        $sql = "SELECT pr.nome, SUM(pi.quantidade) AS total_vendido, MONTH(p.data_pedido) AS mes
                FROM pedidos_itens pi
                JOIN produtos pr ON pr.id_produto = pi.id_produto
                JOIN pedidos p ON p.id_pedido = pi.id_pedido
                WHERE p.id_loja = :id_loja AND p.pedido_pago = 1
                  AND YEAR(p.data_pedido) = :ano
                GROUP BY pr.id_produto, mes
                ORDER BY mes ASC, total_vendido DESC
                LIMIT 5";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_loja' => $id_loja, 'ano' => $ano]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function topProdutosPorAno($id_loja, $ano) {
        $sql = "SELECT pr.nome, SUM(pi.quantidade) AS total_vendido
                FROM pedidos_itens pi
                JOIN produtos pr ON pr.id_produto = pi.id_produto
                JOIN pedidos p ON p.id_pedido = pi.id_pedido
                WHERE p.id_loja = :id_loja AND p.pedido_pago = 1
                  AND YEAR(p.data_pedido) = :ano
                GROUP BY pr.id_produto
                ORDER BY total_vendido DESC
                LIMIT 5";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_loja' => $id_loja, 'ano' => $ano]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    // 🔹 MÉTRICAS MENSAIS
    public function listarMetricasMensais($id_loja, $ano) {
        $sql = "SELECT
                    YEAR(data_pedido) AS ano,
                    MONTH(data_pedido) AS mes,
                    SUM(valor_variado) AS total_mes,
                    COUNT(DISTINCT DATE(data_pedido)) AS dias_com_venda,
                    COUNT(DISTINCT YEARWEEK(data_pedido)) AS semanas_com_venda,
                    COUNT(*) AS pedidos_mes
                FROM pedidos
                WHERE id_loja = :id_loja AND pedido_pago = 1
                  AND YEAR(data_pedido) = :ano
                GROUP BY ano, mes
                ORDER BY ano DESC, mes ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_loja' => $id_loja, 'ano' => $ano]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}

