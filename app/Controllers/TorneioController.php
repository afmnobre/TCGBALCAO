<?php

// Removida a subpasta /Torneio/ pois o arquivo está direto em Models
require_once __DIR__ . '/../Models/Torneio.php';
require_once __DIR__ . '/../Models/Cliente.php'; // Adicionei caso precise listar participantes
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/AuthMiddleware.php';

class TorneioController extends Controller
{
    public function index()
    {
        AuthMiddleware::verificarLogin();

        $model = new Torneio();
        $id_loja = $_SESSION['LOJA']['id_loja'];

        // Garanta que este método existe no seu Torneio.php atual
        $torneios = $model->listarTodos($id_loja);

        // Carrega a view usando o padrão do seu sistema
        $this->view('torneio/index', ['torneios' => $torneios]);
    }

    // Atalho para a rota /torneio/criar
    public function criar()
    {
        // Apenas chama o configuracao sem ID, o que limpa o formulário
        $this->configuracao();
    }

    public function configuracao($id = null)
    {
        AuthMiddleware::verificarLogin();
        $model = new Torneio();

        // Model de CardGames para preencher o SELECT
        require_once __DIR__ . '/../Models/CardGame.php';
        $cardGameModel = new CardGame();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dadosParaSalvar = $_POST;
            $dadosParaSalvar['id_loja'] = $_SESSION['LOJA']['id_loja'];
            // Se for novo (sem id_torneio), o status é aberto
            if(empty($dadosParaSalvar['id_torneio'])) {
                $dadosParaSalvar['status'] = 'aberto';
            }

            $id_salvo = $model->salvar($dadosParaSalvar);

            if ($id_salvo) {
                header("Location: /torneio/participante/$id_salvo");
                exit;
            }
        }

        $dados = [
            'torneio' => $id ? $model->buscar($id, $_SESSION['LOJA']['id_loja']) : null,
            'cardgames' => $cardGameModel->listarTodos()
        ];

        $this->view('torneio/configurar', $dados);
    }

    // Gerencia quem vai jogar (participante.php)
    public function participante($id_torneio)
    {
        AuthMiddleware::verificarLogin();
        $id_loja = $_SESSION['LOJA']['id_loja'];
        $model = new Torneio();

        // 1. Busca os dados do torneio para saber qual o id_cardgame
        $torneio = $model->buscar($id_torneio, $id_loja);

        if (!$torneio) {
            header("Location: /torneio/index?erro=torneio_nao_encontrado");
            exit;
        }

        // 2. Busca os clientes filtrados por LOJA e por CARDGAME do torneio
        $clientes = $model->listarClientesParaTorneio($id_loja, $torneio['id_cardgame']);

        $this->view('torneio/participantes', [
            'torneio' => $torneio,
            'clientes' => $clientes
        ]);
    }

    // O DIRECIONADOR: Decide qual gerenciador abrir
    public function gerenciar($id_torneio)
    {
        AuthMiddleware::verificarLogin();
        $model = new Torneio();
        $torneio = $model->buscar($id_torneio, $_SESSION['LOJA']['id_loja']);

        if (strpos($torneio['tipo_torneio'], 'suico') !== false) {
            header("Location: /torneioSuico/gerenciar/$id_torneio");
        } else {
            header("Location: /torneioEliminacao/gerenciar/$id_torneio");
        }
        exit;
    }

    public function salvarConfiguracao()
    {
        AuthMiddleware::verificarLogin();
        $model = new Torneio();

        $dados = [
            'id_torneio'    => $_POST['id_torneio'] ?? null,
            'id_loja'       => $_SESSION['LOJA']['id_loja'],
            'nome_torneio'  => $_POST['nome_torneio'],
            'id_cardgame'   => $_POST['id_cardgame'],
            'tipo_torneio'  => $_POST['tipo_torneio'],
            'tempo_rodada'  => $_POST['tempo_rodada'] ?? 50,
            'status'        => 'aberto' // Status inicial antes de começar as rodadas
        ];

        $id_salvo = $model->salvar($dados);

        // Redireciona para o método ListarParticipantes
        header("Location: /torneio/ListarParticipantes/$id_salvo");
        exit;
    }

	public function ListarParticipantes($id_torneio)
	{
		AuthMiddleware::verificarLogin();
		$model = new Torneio();
		$id_loja = $_SESSION['LOJA']['id_loja'];

		// 1. Busca os dados brutos do torneio
		$torneio = $model->buscar($id_torneio, $id_loja);

		if (!$torneio) {
			header("Location: /torneio/index?erro=nao_encontrado");
			exit;
		}

		// 2. BUSCA O NOME DO CARDGAME (Para resolver a linha 3 da view)
		// Se você já tiver um método getNomeCardGame, use-o, senão podemos injetar manualmente:
		require_once __DIR__ . '/../Models/CardGame.php';
		$cgModel = new CardGame();
		$dadosCG = $cgModel->buscar($torneio['id_cardgame']); // Ajuste o nome do método conforme seu model
		$torneio['cardgame'] = $dadosCG['nome'] ?? 'N/A';

		// 3. CRIA O TIPO LEGÍVEL (Para resolver a linha 4 da view)
		$tipos = [
			'suico_bo1' => 'Suíço (MD1)',
			'suico_bo3' => 'Suíço (MD3)',
			'elim_dupla_bo1' => 'Eliminação Dupla (MD1)',
			'elim_dupla_bo3' => 'Eliminação Dupla (MD3)'
		];
		$torneio['tipo_legivel'] = $tipos[$torneio['tipo_torneio']] ?? $torneio['tipo_torneio'];

		// 4. Busca os clientes filtrados
		$clientes = $model->listarClientesParaTorneio($id_loja, $torneio['id_cardgame']);

		$this->view('torneio/participantes', [
			'torneio' => $torneio,
			'clientes' => $clientes
		]);
	}

	public function salvarParticipantes()
	{
		AuthMiddleware::verificarLogin();
		$model = new Torneio();

		$id_torneio = $_POST['id_torneio'];
		$selecionados = $_POST['participantes'] ?? [];

		if (empty($selecionados)) {
			header("Location: /torneio/ListarParticipantes/$id_torneio?erro=selecione_ao_menos_um");
			exit;
		}

		// 1. Salva os participantes na tabela intermediária (id_torneio, id_cliente)
		$model->vincularParticipantes($id_torneio, $selecionados);

		// 2. Busca o tipo do torneio para saber para onde redirecionar
		$torneio = $model->buscar($id_torneio, $_SESSION['LOJA']['id_loja']);

		// 3. Redirecionamento Inteligente
		if (strpos($torneio['tipo_torneio'], 'suico') !== false) {
			// Envia para o Controller do Suíço
			header("Location: /Torneiosuico/gerenciar/$id_torneio");
		} else {
			// Envia para o Controller da Eliminatória
			header("Location: /Torneioeliminacao/gerenciar/$id_torneio");
		}
		exit;
	}

	public function excluir($id) {
		AuthMiddleware::verificarLogin();
		$id_loja = $_SESSION['LOJA']['id_loja'];

		$model = new Torneio();

		// Tenta a exclusão completa
		if ($model->excluirTorneioCompleto($id, $id_loja)) {
			// Você pode usar uma variável de sessão para exibir alertas na View
			$_SESSION['msg_sucesso'] = "Torneio e todos os dados vinculados foram removidos.";
		} else {
			$_SESSION['msg_erro'] = "Erro crítico ao tentar excluir o torneio.";
		}

		header("Location: /torneio");
		exit;
	}
}
