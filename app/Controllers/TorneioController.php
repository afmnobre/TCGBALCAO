<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/AuthMiddleware.php';
require_once __DIR__ . '/../Models/Torneio.php';
require_once __DIR__ . '/../Models/Loja.php';

class TorneioController extends Controller
{
    public function index()
    {
        AuthMiddleware::verificarLogin();

        $torneioModel = new Torneio();
        $torneios = $torneioModel->listarPorLoja($_SESSION['LOJA']['id_loja']);

        $this->view('torneio/index', [
            'torneios' => $torneios
        ]);
    }

    public function criar()
    {
        AuthMiddleware::verificarLogin();

        // Carrega todos os cardgames
        require_once __DIR__ . '/../Models/CardGame.php';
        $cardgameModel = new CardGame();
        $cardgames = $cardgameModel->listarTodos();

        $this->view('torneio/configurar', [
            'cardgames' => $cardgames
        ]);
    }

    public function salvar()
    {
        AuthMiddleware::verificarLogin();

        $torneioModel = new Torneio();

        $dadosTorneio = [
            'id_loja'      => $_SESSION['LOJA']['id_loja'],
            'id_cardgame'  => $_POST['id_cardgame'],
            'nome_torneio' => $_POST['nome_torneio'],
            'tipo_torneio' => $_POST['tipo_torneio'],
            'tempo_rodada' => $_POST['tempo_rodada'] ?? 50
        ];

        $idTorneio = $torneioModel->criar($dadosTorneio);

        $_SESSION['flash'] = "Torneio criado com sucesso!";
        header("Location: /torneio/participantes/$idTorneio");
        exit;
    }

    public function participantes($id)
    {
        AuthMiddleware::verificarLogin();

        require_once __DIR__ . '/../Models/Cliente.php';
        $clienteModel = new Cliente();

        $torneioModel = new Torneio();
        $torneio = $torneioModel->buscar($id, $_SESSION['LOJA']['id_loja']);

        // Mapeamento do tipo de torneio para texto legível
        $tiposTorneio = [
            'suico_bo1'      => 'Suíço - Melhor de 1',
            'suico_bo3'      => 'Suíço - Melhor de 3',
            'elim_dupla_bo1' => 'Eliminação Dupla - Melhor de 1',
            'elim_dupla_bo3' => 'Eliminação Dupla - Melhor de 3'
        ];

        $torneio['tipo_legivel'] = $tiposTorneio[$torneio['tipo_torneio']] ?? $torneio['tipo_torneio'];

        // Buscar clientes vinculados à loja e ao cardgame do torneio
        $clientes = $clienteModel->listarPorLojaECardgame($_SESSION['LOJA']['id_loja'], $torneio['id_cardgame']);

        $this->view('torneio/participantes', [
            'torneio'  => $torneio,
            'clientes' => $clientes
        ]);
    }

    public function salvarParticipantes($id)
    {
        AuthMiddleware::verificarLogin();

        $torneioModel = new Torneio();
        $participantes = $_POST['participantes'] ?? [];

        $torneioModel->adicionarParticipantes($id, $participantes);

        $_SESSION['flash'] = "Participantes adicionados com sucesso!";
        header("Location: /torneio/gerenciar/$id");
        exit;
    }

public function gerenciar($id)
{
    AuthMiddleware::verificarLogin();

    $torneioModel = new Torneio();
    $torneio = $torneioModel->buscar($id, $_SESSION['LOJA']['id_loja']);

    $tiposTorneio = [
        'suico_bo1'      => 'Suíço - Melhor de 1',
        'suico_bo3'      => 'Suíço - Melhor de 3',
        'elim_dupla_bo1' => 'Eliminação Dupla - Melhor de 1',
        'elim_dupla_bo3' => 'Eliminação Dupla - Melhor de 3'
    ];
    $torneio['tipo_legivel'] = $tiposTorneio[$torneio['tipo_torneio']]
        ?? ($torneio['tipo_torneio'] ?? 'Não definido');

    // Buscar rodadas existentes
    $rodadas = $torneioModel->listarRodadas($torneio['id_torneio']);

    // Calcular número máximo de rodadas (exemplo suíço)
    $participantes = $torneioModel->listarParticipantes($torneio['id_torneio']);
    $numJogadores = count($participantes);
    $maxRodadas = ceil(log($numJogadores, 2));

    // Se não houver rodadas, criar apenas a primeira
    if (empty($rodadas)) {
        $torneioModel->gerarPareamentosSuico($torneio['id_torneio'], 1);
        $rodadas = $torneioModel->listarRodadas($torneio['id_torneio']);
    } else {
        $ultimaRodada = end($rodadas);

        // Só cria nova rodada se a última estiver finalizada e não atingiu o máximo
        if ($ultimaRodada['status'] === 'finalizada' && $ultimaRodada['numero_rodada'] < $maxRodadas) {
            $numeroRodada = $ultimaRodada['numero_rodada'] + 1;

            if (!empty($torneio['tipo_torneio']) && str_starts_with($torneio['tipo_torneio'], 'suico')) {
                $torneioModel->gerarPareamentosSuico($torneio['id_torneio'], $numeroRodada);
            } elseif (!empty($torneio['tipo_torneio']) && str_starts_with($torneio['tipo_torneio'], 'elim_dupla')) {
                $torneioModel->gerarPareamentosElimDupla($torneio['id_torneio'], $numeroRodada);
            }

            $rodadas = $torneioModel->listarRodadas($torneio['id_torneio']);
        }
    }

    $this->view('torneio/gerenciar', [
        'torneio'      => $torneio,
        'rodadas'      => $rodadas,
        'torneioModel' => $torneioModel,
        'maxRodadas'   => $maxRodadas
    ]);
}



public function salvarResultado($id_torneio, $id_rodada)
{
    AuthMiddleware::verificarLogin();

    $torneioModel = new Torneio();

    // Salvar resultados enviados
    foreach ($_POST['resultados'] as $id_partida => $resultado) {
        $torneioModel->salvarResultadoPartida($id_partida, $resultado);
    }

    // Finalizar rodada
    $torneioModel->finalizarRodada($id_rodada);

    // Buscar dados do torneio e rodadas
    $torneio = $torneioModel->buscar($id_torneio, $_SESSION['LOJA']['id_loja']);
    $rodadas = $torneioModel->listarRodadas($id_torneio);

    // Calcular número máximo de rodadas (exemplo suíço)
    $participantes = $torneioModel->listarParticipantes($id_torneio);
    $numJogadores = count($participantes);
    $maxRodadas = ceil(log($numJogadores, 2));

    $ultimaRodada = end($rodadas);

    // Se ainda não chegou na última rodada, gerar próxima
    if ($ultimaRodada['numero_rodada'] < $maxRodadas) {
        $numeroRodada = $ultimaRodada['numero_rodada'] + 1;

        if (str_starts_with($torneio['tipo_torneio'], 'suico')) {
            $torneioModel->gerarPareamentosSuico($id_torneio, $numeroRodada);
        } elseif (str_starts_with($torneio['tipo_torneio'], 'elim_dupla')) {
            $torneioModel->gerarPareamentosElimDupla($id_torneio, $numeroRodada);
        }
    }

    // Redirecionar para gerenciar
    header("Location: /torneio/gerenciar/$id_torneio");
    exit;
}


    public function resultado($id)
    {
        AuthMiddleware::verificarLogin();

        $torneioModel = new Torneio();
        $resultado = $torneioModel->classificacaoFinal($id);

        $this->view('torneio/resultado', [
            'resultado' => $resultado
        ]);
    }

    public function pareamentos($id, $rodada)
    {
        AuthMiddleware::verificarLogin();

        $torneioModel = new Torneio();
        $pareamentos = $torneioModel->listarPareamentos($id, $rodada);

        $this->view('torneio/pareamentos', [
            'pareamentos' => $pareamentos,
            'rodada'      => $rodada
        ]);
    }

public function verPareamento($id_torneio, $numero_rodada)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $torneio = new Torneio();
    $pareamentos = $torneio->listarPareamentos($id_torneio, $numero_rodada);

    // Buscar dados da loja ativa a partir da sessão
    $loja = null;
    if (!empty($_SESSION['LOJA']['id_loja'])) {
        $loja = Loja::buscarPorId($_SESSION['LOJA']['id_loja']);
    }

    if (!$loja || !is_array($loja)) {
        $loja = [
            'id_loja' => 0,
            'nome_loja' => 'Loja não encontrada',
            'endereco' => '',
            'cnpj' => '',
            'logo' => '',
            'numero_contrato' => ''
        ];
    }

    require __DIR__ . '/../Views/torneio/pareamento.php';
}


public function verPontuacao($id_torneio, $numero_rodada)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $torneio = new Torneio();

    if (str_starts_with($_GET['tipo_torneio'], 'suico')) {
        $classificacao = $torneio->classificacaoSuicoParcial($id_torneio, $numero_rodada);
    } elseif (str_starts_with($_GET['tipo_torneio'], 'elim_dupla')) {
        $classificacao = $torneio->classificacaoElimDuplaParcial($id_torneio, $numero_rodada);
    } else {
        $classificacao = [];
    }

    // Buscar dados da loja ativa a partir da sessão
    $loja = null;
    if (!empty($_SESSION['LOJA']['id_loja'])) {
        $loja = Loja::buscarPorId($_SESSION['LOJA']['id_loja']);
    }

    if (!$loja || !is_array($loja)) {
        $loja = [
            'id_loja' => 0,
            'nome_loja' => 'Loja não encontrada',
            'endereco' => '',
            'cnpj' => '',
            'logo' => '',
            'numero_contrato' => ''
        ];
    }

    require __DIR__ . '/../Views/torneio/pontuacao.php';
}
}

