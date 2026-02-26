<?php
require_once __DIR__ . '/../Models/Relatorio.php';
require_once __DIR__ . '/../Models/Cliente.php';
require_once __DIR__ . '/../Models/Produto.php';
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/AuthMiddleware.php';

class RelatorioController extends Controller
{
    public function index()
    {
        AuthMiddleware::verificarLogin();

        $relatorioModel = new Relatorio();
        $produtoModel   = new Produto();

        $idLoja   = $_SESSION['LOJA']['id_loja'];
        $anoAtual = date('Y');

        $mediaDia     = $relatorioModel->mediaVendasDia($idLoja);
        $mediaSemana  = $relatorioModel->mediaVendasSemana($idLoja);
        $mediaMes     = $relatorioModel->mediaVendasMes($idLoja);
        $mediaAno     = $relatorioModel->mediaVendasAno($idLoja);

        $topClientesGeral = $relatorioModel->topClientes($idLoja);
        $topClientesMes   = $relatorioModel->topClientesPorMes($idLoja, $anoAtual);
        $topClientesAno   = $relatorioModel->topClientesPorAno($idLoja, $anoAtual);

        $topProdutosGeral = $relatorioModel->topProdutos($idLoja);
        $topProdutosMes   = $relatorioModel->topProdutosPorMes($idLoja, $anoAtual);
        $topProdutosAno   = $relatorioModel->topProdutosPorAno($idLoja, $anoAtual);

        $estoqueAtual     = $produtoModel->estoqueAtual($idLoja);


        // 🔹 Busca dados reais
        $metricasMensaisRaw = $relatorioModel->listarMetricasMensais($idLoja, $anoAtual);

        // 🔹 Inicializa array com 12 meses zerados
        $metricasMensais = [];
        for ($mes = 1; $mes <= 12; $mes++) {
            $metricasMensais[$mes] = [
                'ano'              => $anoAtual,
                'mes'              => $mes,
                'total_mes'        => 0,
                'dias_com_venda'   => 0,
                'semanas_com_venda'=> 0,
                'pedidos_mes'      => 0
            ];
        }

        // 🔹 Substitui pelos dados reais quando existirem
        foreach ($metricasMensaisRaw as $m) {
            $mes = (int)$m['mes'];
            $metricasMensais[$mes] = array_merge($metricasMensais[$mes], $m);
        }

        // 🔹 Agora sim, envia para a view
        $this->view('relatorio/index', [
            'mediaDia'        => $mediaDia,
            'mediaSemana'     => $mediaSemana,
            'mediaMes'        => $mediaMes,
            'mediaAno'        => $mediaAno,
            'topClientesGeral'=> $topClientesGeral,
            'topClientesMes'  => $topClientesMes,
            'topClientesAno'  => $topClientesAno,
            'topProdutosGeral'=> $topProdutosGeral,
            'topProdutosMes'  => $topProdutosMes,
            'topProdutosAno'  => $topProdutosAno,
            'estoqueAtual'    => $estoqueAtual,
            'metricasMensais' => $metricasMensais,
            'anoAtual'        => $anoAtual
        ]);
    }

	public function dados()
	{
		header('Content-Type: application/json');

		$ano = $_GET['ano'] ?? date('Y');
		$idLoja = $_SESSION['LOJA']['id_loja'];

		$relatorioModel = new Relatorio();

		$metricas     = $relatorioModel->getMetricas($idLoja, $ano);
		$comparativo  = $relatorioModel->getComparativo($idLoja, $ano);
		$topClientes  = $relatorioModel->getTopClientes($idLoja, $ano);
		$topProdutos  = $relatorioModel->getTopProdutos($idLoja, $ano);
		$pagamentos   = $relatorioModel->getPagamentos($idLoja, $ano);
		$desempenho   = $relatorioModel->getDesempenhoAnual($idLoja, $ano);

		echo json_encode([
			"metricas"    => $metricas,
			"comparativo" => $comparativo,
			"topClientes" => $topClientes,
			"topProdutos" => $topProdutos,
			"pagamentos"  => $pagamentos,
			"desempenho"  => $desempenho
		]);

		exit;
	}








}



