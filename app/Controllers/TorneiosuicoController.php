<?php

// Models
require_once __DIR__ . '/../Models/Torneio.php';
require_once __DIR__ . '/../Models/Cliente.php';
require_once __DIR__ . '/../Models/TorneioSuico.php';

// Core
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/AuthMiddleware.php';
require_once __DIR__ . '/../../core/Database.php';

use Core\Database;

class TorneiosuicoController extends Controller
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

	public function gerenciar($id_torneio)
	{
		AuthMiddleware::verificarLogin();
		$id_loja = $_SESSION['LOJA']['id_loja'];
		$modelSuico = new TorneioSuico();

		// Busca os dados básicos do torneio
		$torneio = $modelSuico->buscar($id_torneio, $id_loja);
		if (!$torneio) {
			header("Location: /torneio?erro=nao_encontrado");
			exit;
		}

		// 1. Busca Rodada Atual
		$rodadaAtual = $modelSuico->buscarRodadaAtual($id_torneio);

		// 2. Se não existe rodada, gera a primeira automaticamente
		if (!$rodadaAtual) {
			$modelSuico->gerarRodadaInicial($id_torneio);
			$rodadaAtual = $modelSuico->buscarRodadaAtual($id_torneio);
		}

		// 3. Busca Partidas e Ranking
		// Note que buscamos o ranking sempre para ter os dados de desempate e pontuação atualizados
		$partidas = $modelSuico->buscarPartidasDaRodada($rodadaAtual['id_rodada']);
		$ranking = $modelSuico->calcularRanking($id_torneio);

		// 4. Verificação de Segurança: Todas as partidas da rodada têm resultado?
		$todasConcluidas = true;
		foreach ($partidas as $p) {
			if (empty($p['resultado'])) {
				$todasConcluidas = false;
				break;
			}
		}

		// 5. Cálculos de interface e lógica de progressão
		$totalJogadores = count($ranking);
		$totalRodadas = ($totalJogadores > 0) ? (int)ceil(log($totalJogadores, 2)) : 0;

		// Lógica Corrigida: Só pode gerar a próxima se:
		// 1. O torneio ainda está 'em_andamento'
		// 2. Todas as partidas da rodada atual foram preenchidas (incluindo BYEs automáticos)
		// 3. Ainda não chegamos no limite matemático de rodadas do sistema suíço
		$podeGerarProxima = ($torneio['status'] === 'em_andamento') &&
							$todasConcluidas &&
							($rodadaAtual['numero_rodada'] < $totalRodadas);

		// Retorna para a View com todos os parâmetros necessários
		$this->view('torneio/gerenciarTorneioSuico', [
			'torneio'         => $torneio,
			'rodada'          => $rodadaAtual,
			'partidas'        => $partidas,
			'participantes'   => $ranking,
			'totalRodadas'    => $totalRodadas,
			'podeGerarProxima' => $podeGerarProxima,
			'todasConcluidas' => $todasConcluidas
		]);
	}

    public function salvarResultado()
    {
        $id_partida = $_POST['id_partida'] ?? null;
        $resultado = $_POST['resultado'] ?? null;
        $id_torneio = $_POST['id_torneio'] ?? null;

        if ($id_partida && $resultado) {
            $model = new TorneioSuico();
            $model->atualizarResultadoPartida($id_partida, $resultado);
        }

        header("Location: /torneiosuico/gerenciar/$id_torneio");
        exit;
    }

    public function proximaRodada($id_torneio)
    {
        $model = new TorneioSuico();
        $model->gerarProximaRodada($id_torneio);
        header("Location: /torneiosuico/gerenciar/$id_torneio");
        exit;
    }

	public function verPareamento($id_torneio, $numero_rodada)
	{
		AuthMiddleware::verificarLogin();
		$modelSuico = new TorneioSuico();
		$id_loja = $_SESSION['LOJA']['id_loja'];

		// Agora usamos o método que acabamos de criar no model
		$loja = $modelSuico->buscarLoja($id_loja);
		$dadosTorneio = $modelSuico->buscar($id_torneio, $id_loja);

		// Busca a rodada específica usando o novo método
		$rodadaInfo = $modelSuico->buscarRodadaPorNumero($id_torneio, $numero_rodada);

		$pareamentos = [];
		if ($rodadaInfo) {
			$partidas = $modelSuico->buscarPartidasDaRodada($rodadaInfo['id_rodada']);
			foreach ($partidas as $p) {
				$pareamentos[] = [
					'jogador1' => $p['nome_j1'],
					'jogador2' => $p['nome_j2']
				];
			}
		}

		$numero_rodada = (int)$numero_rodada;
		require __DIR__ . '/../Views/torneio/pareamento.php';
	}

	public function verPontuacao($id_torneio)
	{
		AuthMiddleware::verificarLogin();
		$modelSuico = new TorneioSuico();
		$id_loja = $_SESSION['LOJA']['id_loja'];

		$loja = $modelSuico->buscarLoja($id_loja);
		$dadosTorneio = $modelSuico->buscar($id_torneio, $id_loja);
		$ranking = $modelSuico->calcularRanking($id_torneio);
		$rodadaAtual = $modelSuico->buscarRodadaAtual($id_torneio);

		// Mapeamento de números para texto
		$extenso = [1 => 'Primeira', 2 => 'Segunda', 3 => 'Terceira', 4 => 'Quarta', 5 => 'Quinta', 6 => 'Sexta'];
		$num = $rodadaAtual['numero_rodada'] ?? 1;
		$numero_rodada_texto = ($extenso[$num] ?? $num . 'ª') . " Rodada";

		// Prepara os dados para a view
		$classificacao = [];
		foreach ($ranking as $r) {
			$classificacao[] = [
				'nome' => $r['nome'],
				'vitorias' => $r['vitorias'],
				'derrotas' => $r['derrotas'],
				'empates' => $r['empates'],
				'bye' => $r['byes'],
				'pontos' => $r['pontos'],
				'forca_oponentes' => $r['buchholz'],
				'vitorias_2x0' => $r['vitorias2x0']
			];
		}

		// LÓGICA DE DECISÃO DE VIEW
		if ($dadosTorneio['status'] === 'finalizado') {
			// Usa o arquivo original de resultado final
			$classificacaoFinal = $classificacao; // nomes compatíveis com a sua view antiga
			$maxRodadas = $num;
			require __DIR__ . '/../Views/torneio/resultadosuico.php';
		} else {
			// Usa a nova view de ranking parcial
			require __DIR__ . '/../Views/torneio/ranking_parcial.php';
		}
	}

public function limparResultado($id_partida, $id_torneio) {
    AuthMiddleware::verificarLogin();

    $model = new TorneioSuico();

    // Chama o método interno do model em vez de tentar acessar o $db
    $model->resetarPartida($id_partida);

    // Redireciona de volta para a tela de gerenciamento
    header("Location: /torneiosuico/gerenciar/$id_torneio");
    exit;
}
}
