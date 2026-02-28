<?php
require_once __DIR__ . '/../../core/Database.php';
use Core\Database;

class Relatorio {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAnosComPedidos($idLoja) {
        $sql = "SELECT DISTINCT YEAR(data_pedido) as ano FROM pedidos WHERE id_loja = :idLoja ORDER BY ano DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':idLoja' => $idLoja]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [date('Y')];
    }

	public function getMetricas($idLoja, $ano, $mes) {
		// Se mes for 0, não filtramos por mês na query
		$filtroMes = ($mes > 0) ? " AND MONTH(p.data_pedido) = :mes " : "";

		$sql = "SELECT YEAR(p.data_pedido) as ano,
					   SUM(pp.valor) as total_mes,
					   COUNT(DISTINCT p.id_pedido) as pedidos_mes
				FROM pedidos p
				JOIN pedido_pagamento pp ON p.id_pedido = pp.id_pedido
				WHERE p.id_loja = :idLoja AND YEAR(p.data_pedido) = :ano
				$filtroMes
				GROUP BY YEAR(p.data_pedido)";

		$stmt = $this->db->prepare($sql);
		$params = [':idLoja' => $idLoja, ':ano' => $ano];
		if ($mes > 0) $params[':mes'] = $mes;

		$stmt->execute($params);
		return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [['total_mes'=>0, 'pedidos_mes'=>0]];
	}

    public function getComparativo($idLoja, $ano) {
        $anoAnterior = $ano - 1;
        $sql = "SELECT YEAR(p.data_pedido) AS ano, MONTH(p.data_pedido) AS mes, SUM(pp.valor) AS total
                FROM pedidos p
                JOIN pedido_pagamento pp ON p.id_pedido = pp.id_pedido
                WHERE p.id_loja = :idLoja AND YEAR(p.data_pedido) IN (:ano, :anoAnterior)
                GROUP BY ano, mes ORDER BY ano, mes";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':idLoja' => $idLoja, ':ano' => $ano, ':anoAnterior' => $anoAnterior]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

	public function getTopClientes($idLoja, $ano, $mes) {
		$filtroMes = ($mes > 0) ? " AND MONTH(p.data_pedido) = :mes " : "";

		$sql = "SELECT c.nome, SUM(pp.valor) as total
				FROM pedidos p
				JOIN clientes c ON c.id_cliente = p.id_cliente
				JOIN pedido_pagamento pp ON p.id_pedido = pp.id_pedido
				WHERE p.id_loja = :idLoja AND YEAR(p.data_pedido) = :ano
				$filtroMes
				GROUP BY c.id_cliente ORDER BY total DESC LIMIT 5";

		$stmt = $this->db->prepare($sql);
		$params = [':idLoja' => $idLoja, ':ano' => $ano];
		if ($mes > 0) $params[':mes'] = $mes;

		$stmt->execute($params);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getTopProdutos($idLoja, $ano, $mes) {
		$filtroMes = ($mes > 0) ? " AND MONTH(p.data_pedido) = :mes " : "";

		$sql = "SELECT pr.nome, SUM(pi.quantidade) as total_vendido, SUM(pi.quantidade * pi.valor_unitario) as total_valor
				FROM pedidos_itens pi
				JOIN pedidos p ON p.id_pedido = pi.id_pedido
				JOIN produtos pr ON pr.id_produto = pi.id_produto
				WHERE p.id_loja = :idLoja AND YEAR(p.data_pedido) = :ano
				$filtroMes
				GROUP BY pr.id_produto ORDER BY total_vendido DESC LIMIT 5";

		$stmt = $this->db->prepare($sql);
		$params = [':idLoja' => $idLoja, ':ano' => $ano];
		if ($mes > 0) $params[':mes'] = $mes;

		$stmt->execute($params);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getFaturamentoPorPagamento($idLoja, $ano, $mes) {
		$filtroMes = ($mes > 0) ? " AND MONTH(p.data_pedido) = :mes " : "";

		$sql = "SELECT tp.nome, SUM(pp.valor) as total
				FROM pedido_pagamento pp
				JOIN pedidos p ON p.id_pedido = pp.id_pedido
				JOIN tipos_pagamento tp ON tp.id_pagamento = pp.id_pagamento
				WHERE p.id_loja = :idLoja AND YEAR(p.data_pedido) = :ano
				$filtroMes
				GROUP BY tp.id_pagamento";

		$stmt = $this->db->prepare($sql);
		$params = [':idLoja' => $idLoja, ':ano' => $ano];
		if ($mes > 0) $params[':mes'] = $mes;

		$stmt->execute($params);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getDesempenhoAnual($idLoja, $ano) {
		// Note o parâmetro corrigido para :idLoja
		$sql = "SELECT MONTH(p.data_pedido) as mes, p.id_pedido, SUM(pp.valor) as total_pedido, p.id_cliente
				FROM pedidos p
				JOIN pedido_pagamento pp ON p.id_pedido = pp.id_pedido
				WHERE p.id_loja = :idLoja AND YEAR(p.data_pedido) = :ano
				GROUP BY p.id_pedido";
		$stmt = $this->db->prepare($sql);
		// Corrigido aqui também
		$stmt->execute([':idLoja' => $idLoja, ':ano' => $ano]);
		$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $resultado = [];
        $mesesNomes = [1=>"Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"];
        foreach($mesesNomes as $num => $nome) {
            $resultado[$nome] = ["total_mes"=>0,"pedidos"=>0,"clientes"=>[],"menor_pedido"=>null,"maior_pedido"=>0,"media_dia"=>0,"media_semana"=>0,"media_pedido"=>0,"media_cliente"=>0];
        }

        foreach ($dados as $row) {
            $nomeMes = $mesesNomes[(int)$row['mes']];
            $total = (float)$row['total_pedido'];
            $resultado[$nomeMes]['total_mes'] += $total;
            $resultado[$nomeMes]['pedidos']++;
            $resultado[$nomeMes]['clientes'][] = $row['id_cliente'];
            if ($resultado[$nomeMes]['menor_pedido'] === null || $total < $resultado[$nomeMes]['menor_pedido']) $resultado[$nomeMes]['menor_pedido'] = $total;
            if ($total > $resultado[$nomeMes]['maior_pedido']) $resultado[$nomeMes]['maior_pedido'] = $total;
        }

        foreach ($resultado as $nome => &$d) {
            $idxMes = array_search($nome, $mesesNomes);
            $diasNoMes = cal_days_in_month(CAL_GREGORIAN, $idxMes, $ano);
            $clientesUnicos = count(array_unique($d['clientes']));
            if($d['pedidos'] > 0) {
                $d['media_dia'] = $d['total_mes'] / $diasNoMes;
                $d['media_semana'] = $d['total_mes'] / 4.33;
                $d['media_pedido'] = $d['total_mes'] / $d['pedidos'];
                $d['media_cliente'] = $d['total_mes'] / $clientesUnicos;
            }
            unset($d['clientes']);
        }
        return $resultado;
    }
}
