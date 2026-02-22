<?php

// Models
require_once __DIR__ . '/../Models/Torneio.php';
require_once __DIR__ . '/../Models/Cliente.php';

// Core
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/AuthMiddleware.php';
require_once __DIR__ . '/../../core/Database.php';

use Core\Database;

class TorneioeliminacaoController extends Controller
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

        require_once __DIR__ . '/../Models/Torneio.php';
        $model = new Torneio();
        $torneio = $model->buscar($id_torneio, $id_loja);

        if (!$torneio) {
            header("Location: /torneio/index?erro=nao_encontrado");
            exit;
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM torneio_participantes WHERE id_torneio = ?");
        $stmt->execute([$id_torneio]);
        $totalJogadores = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Na Eliminação Dupla a lógica de rodadas é diferente (exemplo base)
        $totalRodadas = ($totalJogadores > 0) ? (ceil(log($totalJogadores, 2)) * 2) : 0;

        $dados = [
            'torneio' => $torneio,
            'totalJogadores' => $totalJogadores,
            'totalRodadas' => $totalRodadas
        ];

        $this->view('torneio/gerenciarTorneioEliminacao', $dados);
    }
}
