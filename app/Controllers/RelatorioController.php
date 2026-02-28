<?php
// Importação dos Models necessários
require_once __DIR__ . '/../Models/Relatorio.php';

// Importação das classes do Core (Ajuste o caminho se sua estrutura for diferente)
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/AuthMiddleware.php';

class RelatorioController extends Controller {

	public function index() {
		AuthMiddleware::verificarLogin();
		$relatorioModel = new Relatorio();
		$idLoja = $_SESSION['LOJA']['id_loja'];

		// Se não vier nada na URL, o padrão pode ser o mês atual ou 0 (Todos)
		// Vamos colocar 0 como padrão para abrir o ano cheio
		$this->view('relatorio/index', [
			'mes_selecionado'  => isset($_GET['mes']) ? (int)$_GET['mes'] : 0,
			'ano_selecionado'  => $_GET['ano'] ?? date('Y'),
			'anos_disponiveis' => $relatorioModel->getAnosComPedidos($idLoja)
		]);
	}

	public function dados() {
		// Limpa qualquer saída anterior para não corromper o JSON
		if (ob_get_level()) ob_end_clean();

		header('Content-Type: application/json');
		try {
			$idLoja = $_SESSION['LOJA']['id_loja'];
			$ano = $_GET['ano'] ?? date('Y');
			$mes = $_GET['mes'] ?? date('n');

			$relatorioModel = new Relatorio();

			echo json_encode([
				"metricas"    => $relatorioModel->getMetricas($idLoja, $ano, $mes),
				"comparativo" => $relatorioModel->getComparativo($idLoja, $ano),
				"topClientes" => $relatorioModel->getTopClientes($idLoja, $ano, $mes),
				"topProdutos" => $relatorioModel->getTopProdutos($idLoja, $ano, $mes),
				"pagamentos"  => $relatorioModel->getFaturamentoPorPagamento($idLoja, $ano, $mes),
				"desempenho"  => $relatorioModel->getDesempenhoAnual($idLoja, $ano)
			]);
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(["error" => $e->getMessage()]);
		}
		exit;
	}
}
