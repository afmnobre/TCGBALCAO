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
                    COUNT(DISTINCT id_pedido) AS pedidos_mes
                FROM pedidos
                WHERE id_loja = :id_loja AND pedido_pago = 1
                  AND YEAR(data_pedido) = :ano
                GROUP BY ano, mes
                ORDER BY ano DESC, mes ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_loja' => $id_loja, 'ano' => $ano]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function comparativoAnual($id_loja, $ano, $mes = null, $id_cliente = null)
	{
		$filtroMes = $mes ? " AND MONTH(p.data_pedido) = :mes " : "";
		$filtroCliente = $id_cliente ? " AND p.id_cliente = :id_cliente " : "";

		$sql = "
			SELECT
				YEAR(p.data_pedido) AS ano,
				MONTH(p.data_pedido) AS mes,
				SUM(p.valor_variado) AS total,
				COUNT(DISTINCT p.id_pedido) AS pedidos
			FROM pedidos p
			WHERE p.id_loja = :id_loja
			  AND p.pedido_pago = 1
			  AND YEAR(p.data_pedido) IN (:ano, :ano_anterior)
			  $filtroMes
			  $filtroCliente
			GROUP BY ano, mes
			ORDER BY mes ASC
		";

		$stmt = $this->db->prepare($sql);

		$params = [
			'id_loja' => $id_loja,
			'ano' => $ano,
			'ano_anterior' => $ano - 1
		];

		if ($mes) $params['mes'] = $mes;
		if ($id_cliente) $params['id_cliente'] = $id_cliente;

		$stmt->execute($params);

		return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
	}

    public function vendasPorPagamento($id_loja, $ano, $mes = null)
	{
		$filtroMes = $mes ? " AND MONTH(p.data_pedido) = :mes " : "";

		$sql = "
			SELECT
				tp.nome,
				SUM(pp.valor) AS total
			FROM pedido_pagamento pp
			JOIN pedidos p ON p.id_pedido = pp.id_pedido
			JOIN tipos_pagamento tp ON tp.id_pagamento = pp.id_pagamento
			WHERE p.id_loja = :id_loja
			  AND p.pedido_pago = 1
			  AND YEAR(p.data_pedido) = :ano
			  $filtroMes
			GROUP BY tp.id_pagamento
			ORDER BY total DESC
		";

		$stmt = $this->db->prepare($sql);

		$params = [
			'id_loja' => $id_loja,
			'ano' => $ano
		];

		if ($mes) $params['mes'] = $mes;

		$stmt->execute($params);

		return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
	}

	public function getMetricas($idLoja, $ano)
	{
		$sql = "
			SELECT
				YEAR(data_pedido) as ano,
				MONTH(data_pedido) as mes,
				SUM(valor_variado) as total_mes,
				COUNT(*) as pedidos_mes
			FROM pedidos
			WHERE id_loja = :idLoja
			AND YEAR(data_pedido) = :ano
			GROUP BY YEAR(data_pedido), MONTH(data_pedido)
			ORDER BY mes
		";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([
			':idLoja' => $idLoja,
			':ano' => $ano
		]);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getComparativo($idLoja, $ano)
	{
		$anoAnterior = $ano - 1;

		$sql = "
			SELECT
				YEAR(p.data_pedido) AS ano,
				MONTH(p.data_pedido) AS mes,
				SUM(p.valor_variado + IFNULL(pi.total_itens,0)) AS total
			FROM pedidos p
			LEFT JOIN (
				SELECT id_pedido, SUM(valor_unitario * quantidade) AS total_itens
				FROM pedidos_itens
				GROUP BY id_pedido
			) pi ON pi.id_pedido = p.id_pedido
			WHERE p.id_loja = :idLoja
			  AND (YEAR(p.data_pedido) = :ano OR YEAR(p.data_pedido) = :anoAnterior)
			GROUP BY YEAR(p.data_pedido), MONTH(p.data_pedido)
			ORDER BY ano, mes
		";

		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':idLoja', $idLoja, PDO::PARAM_INT);
		$stmt->bindValue(':ano', $ano, PDO::PARAM_INT);
		$stmt->bindValue(':anoAnterior', $anoAnterior, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}



	public function getTopClientes($idLoja, $ano)
	{
		$sql = "
			SELECT c.nome, SUM(pp.valor) as total
			FROM pedidos p
			JOIN clientes c ON c.id_cliente = p.id_cliente
			JOIN pedido_pagamento pp ON p.id_pedido = pp.id_pedido
			WHERE p.id_loja = :idLoja
			AND YEAR(p.data_pedido) = :ano
			GROUP BY c.id_cliente, c.nome
			ORDER BY total DESC
			LIMIT 5
		";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([
			':idLoja' => $idLoja,
			':ano'    => $ano
		]);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	public function getTopProdutos($idLoja, $ano)
	{
		$sql = "
			SELECT
				pr.nome,
				SUM(pi.quantidade) as total_vendido,
				SUM(pi.quantidade * pi.valor_unitario) as total_valor
			FROM pedidos p
			JOIN pedidos_itens pi ON pi.id_pedido = p.id_pedido
			JOIN produtos pr ON pr.id_produto = pi.id_produto
			WHERE p.id_loja = :id_loja
			AND YEAR(p.data_pedido) = :ano
			AND p.pedido_pago = 1
			GROUP BY pr.id_produto
			ORDER BY total_vendido DESC
			LIMIT 5
		";

		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':id_loja', $idLoja);
		$stmt->bindValue(':ano', $ano);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	public function getPagamentos($idLoja, $ano)
	{
		$sql = "
			SELECT tp.nome, SUM(pp.valor) as total
			FROM pedidos p
			JOIN pedido_pagamento pp ON pp.id_pedido = p.id_pedido
			JOIN tipos_pagamento tp ON tp.id_pagamento = pp.id_pagamento
			WHERE p.id_loja = :idLoja
			AND YEAR(p.data_pedido) = :ano
			GROUP BY tp.id_pagamento
			ORDER BY total DESC
		";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([
			':idLoja' => $idLoja,
			':ano' => $ano
		]);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getDesempenhoAnual($idLoja, $ano)
	{
		$sql = "
			SELECT
				MONTH(p.data_pedido) as mes,
				p.id_cliente,
				(
					COALESCE(p.valor_variado, 0) +
					COALESCE(SUM(pi.quantidade * pi.valor_unitario), 0)
				) as total_pedido
			FROM pedidos p
			LEFT JOIN pedidos_itens pi ON pi.id_pedido = p.id_pedido
			WHERE p.id_loja = :id_loja
			AND YEAR(p.data_pedido) = :ano
			AND p.pedido_pago = 1
			GROUP BY p.id_pedido
		";

		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':id_loja', $idLoja);
		$stmt->bindValue(':ano', $ano);
		$stmt->execute();

		$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$resultado = [];

		// Inicializa os 12 meses
		for ($m = 1; $m <= 12; $m++) {
			$resultado[$m] = [
				"total_mes" => 0,
				"pedidos" => 0,
				"clientes" => [],
				"menor_pedido" => null,
				"maior_pedido" => 0
			];
		}

		foreach ($dados as $row) {

			$mes = (int)$row['mes'];
			$total = (float)$row['total_pedido'];

			$resultado[$mes]['total_mes'] += $total;
			$resultado[$mes]['pedidos']++;
			$resultado[$mes]['clientes'][] = $row['id_cliente'];

			if ($resultado[$mes]['menor_pedido'] === null || $total < $resultado[$mes]['menor_pedido']) {
				$resultado[$mes]['menor_pedido'] = $total;
			}

			if ($total > $resultado[$mes]['maior_pedido']) {
				$resultado[$mes]['maior_pedido'] = $total;
			}
		}

		// Finaliza cálculos
		foreach ($resultado as $mes => &$dadosMes) {

			$diasNoMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
			$clientesUnicos = count(array_unique($dadosMes['clientes']));

			$total = $dadosMes['total_mes'];
			$pedidos = $dadosMes['pedidos'];

			$dadosMes['media_dia'] = $diasNoMes > 0 ? round($total / $diasNoMes, 2) : 0;
			$dadosMes['media_semana'] = round($total / 4.33, 2);
			$dadosMes['media_pedido'] = $pedidos > 0 ? round($total / $pedidos, 2) : 0;
			$dadosMes['media_cliente'] = $clientesUnicos > 0 ? round($total / $clientesUnicos, 2) : 0;

			$dadosMes['menor_pedido'] = round($dadosMes['menor_pedido'] ?? 0, 2);
			$dadosMes['maior_pedido'] = round($dadosMes['maior_pedido'], 2);
			$dadosMes['total_mes'] = round($total, 2);

			unset($dadosMes['clientes']); // remove array auxiliar
		}

		return $resultado;
	}

   	public function getComparativoAnual($idLoja, $ano)
	{
		$anoAnterior = $ano - 1;

		$sql = "
			SELECT
				YEAR(p.data_pedido) as ano,
				MONTH(p.data_pedido) as mes,
				SUM(
					p.valor_variado +
					IFNULL((
						SELECT SUM(pi.valor_unitario * pi.quantidade)
						FROM pedidos_itens pi
						WHERE pi.id_pedido = p.id
					), 0)
				) as total
			FROM pedidos p
			WHERE p.id_loja = :id_loja
			AND YEAR(p.data_pedido) IN (:ano, :anoAnterior)
			GROUP BY YEAR(p.data_pedido), MONTH(p.data_pedido)
			ORDER BY ano, mes
		";

		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':id_loja', $idLoja);
		$stmt->bindValue(':ano', $ano);
		$stmt->bindValue(':anoAnterior', $anoAnterior);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}



}

