<?php

require_once __DIR__ . '/../../core/Database.php';

use Core\Database;

class Torneio
{
    private $db;

    public function __construct()
    {
        // Conexão original do repositório
        $this->db = Database::getInstance();
    }

    /* =========================
       CRUD BÁSICO DE TORNEIOS
    ========================== */

    public function listarPorLoja($id_loja)
    {
        $sql = "SELECT t.*, cg.nome AS cardgame
                FROM torneios t
                INNER JOIN cardgames cg ON cg.id_cardgame = t.id_cardgame
                WHERE t.id_loja = :id_loja
                ORDER BY t.data_criacao DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_loja' => $id_loja]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criar($dados)
    {
        $sql = "INSERT INTO torneios (id_loja, id_cardgame, nome_torneio, tipo_torneio, tempo_rodada)
                VALUES (:id_loja, :id_cardgame, :nome_torneio, :tipo_torneio, :tempo_rodada)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_loja'      => $dados['id_loja'],
            'id_cardgame'  => $dados['id_cardgame'],
            'nome_torneio' => $dados['nome_torneio'],
            'tipo_torneio' => $dados['tipo_torneio'],
            'tempo_rodada' => $dados['tempo_rodada']
        ]);
        return $this->db->lastInsertId();
    }

public function buscar($id_torneio, $id_loja)
{
    $sql = "SELECT t.*, cg.nome AS cardgame
            FROM torneios t
            INNER JOIN cardgames cg ON cg.id_cardgame = t.id_cardgame
            WHERE t.id_torneio = :id_torneio AND t.id_loja = :id_loja";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        'id_torneio' => $id_torneio,
        'id_loja'    => $id_loja
    ]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


    public function adicionarParticipantes($id_torneio, $participantes)
    {
        foreach ($participantes as $id_cliente) {
            $sql = "INSERT INTO torneio_participantes (id_torneio, id_cliente)
                    VALUES (:id_torneio, :id_cliente)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'id_torneio' => $id_torneio,
                'id_cliente' => $id_cliente
            ]);
        }
    }

    public function listarParticipantes($id_torneio)
    {
        $sql = "SELECT c.id_cliente AS id_jogador, c.nome
                FROM torneio_participantes tp
                INNER JOIN clientes c ON c.id_cliente = tp.id_cliente
                WHERE tp.id_torneio = :id_torneio
                ORDER BY c.nome ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_torneio' => $id_torneio]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarRodadas($id_torneio)
    {
        $sql = "SELECT * FROM torneio_rodadas WHERE id_torneio = :id_torneio ORDER BY numero_rodada ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_torneio' => $id_torneio]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function finalizarRodada($id_rodada)
    {
        $sql = "UPDATE torneio_rodadas SET status = 'finalizada' WHERE id_rodada = :id_rodada";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_rodada' => $id_rodada]);
    }

    public function salvarResultadoPartida($id_partida, $resultado)
    {
        $sql = "UPDATE torneio_partidas SET resultado = :resultado WHERE id_partida = :id_partida";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'resultado'  => $resultado,
            'id_partida' => $id_partida
        ]);
    }

    public function listarPareamentos($id_torneio, $rodada)
    {
        $sql = "SELECT tp.id_partida, c1.nome AS jogador1, c2.nome AS jogador2, tp.resultado
                FROM torneio_partidas tp
                INNER JOIN clientes c1 ON c1.id_cliente = tp.id_jogador1
                LEFT JOIN clientes c2 ON c2.id_cliente = tp.id_jogador2
                INNER JOIN torneio_rodadas tr ON tr.id_rodada = tp.id_rodada
                WHERE tr.id_torneio = :id_torneio AND tr.numero_rodada = :rodada
                ORDER BY tp.id_partida ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_torneio' => $id_torneio,
            'rodada'     => $rodada
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /* =========================
       PAREAMENTO SUÍÇO
    ========================== */

public function gerarPareamentosSuico($id_torneio, $jogadores, $numeroRodada)
{
    $pareamentos = [];

    // 1. Criar registro da rodada
    $sqlRodada = "INSERT INTO torneio_rodadas (id_torneio, numero_rodada, status)
                  VALUES (:id_torneio, :numero_rodada, 'em_andamento')";
    $stmtRodada = $this->db->prepare($sqlRodada);
    $stmtRodada->execute([
        'id_torneio'    => $id_torneio,
        'numero_rodada' => $numeroRodada
    ]);
    $idRodada = $this->db->lastInsertId();

    // 2. Buscar classificação parcial até a rodada anterior
    $classificacao = $this->classificacaoSuicoParcial($id_torneio, $numeroRodada - 1);

    // 3. Criar mapa de pontos
    $mapaPontos = [];
    foreach ($classificacao as $linha) {
        $mapaPontos[$linha['id_cliente']] = [
            'pontos' => $linha['pontos'],
            'forca'  => $linha['forca_oponentes']
        ];
    }

    // 4. Ordenar jogadores
    if ($numeroRodada == 1) {
        // Primeira rodada: embaralhar aleatoriamente
        shuffle($jogadores);
    } else {
        // Rodadas seguintes: ordenar por pontos e força
        usort($jogadores, function ($a, $b) use ($mapaPontos) {
            $pa = $mapaPontos[$a['id_jogador']]['pontos'] ?? 0;
            $pb = $mapaPontos[$b['id_jogador']]['pontos'] ?? 0;
            if ($pa == $pb) {
                $fa = $mapaPontos[$a['id_jogador']]['forca'] ?? 0;
                $fb = $mapaPontos[$b['id_jogador']]['forca'] ?? 0;
                return $fb <=> $fa;
            }
            return $pb <=> $pa;
        });
    }

    // 5. Se número de jogadores for ímpar, atribuir BYE
    if (count($jogadores) % 2 !== 0) {
        // Buscar quem já recebeu bye
        $sqlByeCheck = "SELECT DISTINCT id_jogador1
                        FROM torneio_partidas
                        WHERE id_rodada IN (
                            SELECT id_rodada FROM torneio_rodadas WHERE id_torneio = :id_torneio
                        ) AND id_jogador2 IS NULL";
        $stmtByeCheck = $this->db->prepare($sqlByeCheck);
        $stmtByeCheck->execute(['id_torneio' => $id_torneio]);
        $byeJogadores = $stmtByeCheck->fetchAll(PDO::FETCH_COLUMN);

        // Escolher jogador que ainda não recebeu bye
        $byePlayer = null;
        foreach ($jogadores as $index => $jogador) {
            if (!in_array($jogador['id_jogador'], $byeJogadores)) {
                $byePlayer = $jogador;
                unset($jogadores[$index]);
                break;
            }
        }

        // Se todos já receberam bye (caso raro), pegar o último
        if (!$byePlayer) {
            $byePlayer = array_pop($jogadores);
        }

        // Registrar vitória automática no banco
        $sqlBye = "INSERT INTO torneio_partidas (id_rodada, id_jogador1, id_jogador2, resultado)
                   VALUES (:id_rodada, :id_jogador1, NULL, 'jogador1_vitoria')";
        $stmtBye = $this->db->prepare($sqlBye);
        $stmtBye->execute([
            'id_rodada'   => $idRodada,
            'id_jogador1' => $byePlayer['id_jogador']
        ]);
        $idPartidaBye = $this->db->lastInsertId();

        $pareamentos[] = [
            'id_partida' => $idPartidaBye,
            'jogador1'   => $byePlayer['nome'],
            'jogador2'   => 'BY',
            'resultado'  => 'jogador1_vitoria'
        ];
    }

    // 6. Parear jogadores sequencialmente
    $jogadores = array_values($jogadores); // reindexar após remover bye
    for ($i = 0; $i < count($jogadores); $i += 2) {
        if (isset($jogadores[$i + 1])) {
            $sql = "INSERT INTO torneio_partidas (id_rodada, id_jogador1, id_jogador2, resultado)
                    VALUES (:id_rodada, :id_jogador1, :id_jogador2, NULL)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'id_rodada'   => $idRodada,
                'id_jogador1' => $jogadores[$i]['id_jogador'],
                'id_jogador2' => $jogadores[$i + 1]['id_jogador']
            ]);
            $idPartida = $this->db->lastInsertId();

            $pareamentos[] = [
                'id_partida' => $idPartida,
                'jogador1'   => $jogadores[$i]['nome'],
                'jogador2'   => $jogadores[$i + 1]['nome'],
                'resultado'  => null
            ];
        }
    }

    return $pareamentos;
}



private function registrarBye($id_jogador, $idRodada)
{
    $sql = "INSERT INTO torneio_partidas (id_rodada, id_jogador1, id_jogador2, resultado)
            VALUES (:id_rodada, :id_jogador1, NULL, 'jogador1_vitoria')";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        'id_rodada'   => $idRodada,
        'id_jogador1' => $id_jogador
    ]);
}




    /* =========================
       CLASSIFICAÇÃO SUÍÇO
    ========================== */
public function classificacaoSuicoParcial($id_torneio, $numero_rodada)
{
    // Buscar todas as partidas até a rodada informada
    $sql = "SELECT tp.id_jogador1, tp.id_jogador2, tp.resultado
            FROM torneio_partidas tp
            INNER JOIN torneio_rodadas tr ON tr.id_rodada = tp.id_rodada
            WHERE tr.id_torneio = :id_torneio
              AND tr.numero_rodada <= :numero_rodada";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        'id_torneio'    => $id_torneio,
        'numero_rodada' => $numero_rodada
    ]);
    $partidas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Buscar todos os jogadores do torneio
    $sqlJogadores = "SELECT c.id_cliente, c.nome
                     FROM torneio_participantes tpart
                     INNER JOIN clientes c ON c.id_cliente = tpart.id_cliente
                     WHERE tpart.id_torneio = :id_torneio";
    $stmtJogadores = $this->db->prepare($sqlJogadores);
    $stmtJogadores->execute(['id_torneio' => $id_torneio]);
    $jogadores = $stmtJogadores->fetchAll(PDO::FETCH_ASSOC);

    // Inicializar mapa de pontos e estatísticas
    $mapaPontos = [];
    $stats = [];
    foreach ($jogadores as $j) {
        $mapaPontos[$j['id_cliente']] = 0;
        $stats[$j['id_cliente']] = [
            'vitorias'     => 0,
            'derrotas'     => 0,
            'empates'      => 0,
            'bye'          => 0,
            'vitorias_2x0' => 0 // novo campo para critério de desempate
        ];
    }

    // Calcular pontos e estatísticas por resultado
    foreach ($partidas as $p) {
        switch ($p['resultado']) {
            // MD3
            case 'jogador1_2x0':
                $mapaPontos[$p['id_jogador1']] += 3;
                $stats[$p['id_jogador1']]['vitorias']++;
                $stats[$p['id_jogador1']]['vitorias_2x0']++;
                $stats[$p['id_jogador2']]['derrotas']++;
                break;
            case 'jogador2_2x0':
                $mapaPontos[$p['id_jogador2']] += 3;
                $stats[$p['id_jogador2']]['vitorias']++;
                $stats[$p['id_jogador2']]['vitorias_2x0']++;
                $stats[$p['id_jogador1']]['derrotas']++;
                break;
            case 'jogador1_2x1':
                $mapaPontos[$p['id_jogador1']] += 2;
                $mapaPontos[$p['id_jogador2']] += 1;
                $stats[$p['id_jogador1']]['vitorias']++;
                $stats[$p['id_jogador2']]['derrotas']++;
                break;
            case 'jogador2_2x1':
                $mapaPontos[$p['id_jogador2']] += 2;
                $mapaPontos[$p['id_jogador1']] += 1;
                $stats[$p['id_jogador2']]['vitorias']++;
                $stats[$p['id_jogador1']]['derrotas']++;
                break;

            // MD1
            case 'jogador1_vitoria':
                if ($p['id_jogador2'] === null) {
                    // BYE
                    $mapaPontos[$p['id_jogador1']] += 2;
                    $stats[$p['id_jogador1']]['bye']++;
                    $stats[$p['id_jogador1']]['vitorias']++;
                } else {
                    // Vitória simples MD1
                    $mapaPontos[$p['id_jogador1']] += 3;
                    $stats[$p['id_jogador1']]['vitorias']++;
                    $stats[$p['id_jogador2']]['derrotas']++;
                }
                break;
            case 'jogador2_vitoria':
                $mapaPontos[$p['id_jogador2']] += 3;
                $stats[$p['id_jogador2']]['vitorias']++;
                $stats[$p['id_jogador1']]['derrotas']++;
                break;

            // Empate
            case 'empate':
                $mapaPontos[$p['id_jogador1']] += 1;
                $mapaPontos[$p['id_jogador2']] += 1;
                $stats[$p['id_jogador1']]['empates']++;
                $stats[$p['id_jogador2']]['empates']++;
                break;
        }
    }

    // Montar classificação inicial
    $classificacao = [];
    foreach ($jogadores as $j) {
        $classificacao[] = [
            'id_cliente'      => $j['id_cliente'],
            'nome'            => $j['nome'],
            'pontos'          => $mapaPontos[$j['id_cliente']],
            'vitorias'        => $stats[$j['id_cliente']]['vitorias'],
            'derrotas'        => $stats[$j['id_cliente']]['derrotas'],
            'empates'         => $stats[$j['id_cliente']]['empates'],
            'bye'             => $stats[$j['id_cliente']]['bye'],
            'vitorias_2x0'    => $stats[$j['id_cliente']]['vitorias_2x0'],
            'forca_oponentes' => 0
        ];
    }

    // Calcular força dos oponentes (Buchholz)
    foreach ($classificacao as &$jogador) {
        $sqlOpp = "SELECT
                        CASE WHEN tp.id_jogador1 = :id_cliente1 THEN tp.id_jogador2
                             WHEN tp.id_jogador2 = :id_cliente2 THEN tp.id_jogador1
                        END AS adversario
                   FROM torneio_partidas tp
                   INNER JOIN torneio_rodadas tr ON tr.id_rodada = tp.id_rodada
                   WHERE tr.id_torneio = :id_torneio
                     AND tr.numero_rodada <= :numero_rodada
                     AND (tp.id_jogador1 = :id_cliente3 OR tp.id_jogador2 = :id_cliente4)";
        $stmtOpp = $this->db->prepare($sqlOpp);
        $stmtOpp->execute([
            'id_torneio'    => $id_torneio,
            'numero_rodada' => $numero_rodada,
            'id_cliente1'   => $jogador['id_cliente'],
            'id_cliente2'   => $jogador['id_cliente'],
            'id_cliente3'   => $jogador['id_cliente'],
            'id_cliente4'   => $jogador['id_cliente']
        ]);
        $adversarios = $stmtOpp->fetchAll(PDO::FETCH_COLUMN);

        $forca = 0;
        foreach ($adversarios as $adv) {
            if ($adv && isset($mapaPontos[$adv])) {
                $forca += $mapaPontos[$adv];
            }
        }
        $jogador['forca_oponentes'] = $forca;
    }

    // Ordenar por pontos, força dos oponentes e vitórias 2x0
    usort($classificacao, function ($a, $b) {
        if ($a['pontos'] == $b['pontos']) {
            if ($a['forca_oponentes'] == $b['forca_oponentes']) {
                return $b['vitorias_2x0'] <=> $a['vitorias_2x0'];
            }
            return $b['forca_oponentes'] <=> $a['forca_oponentes'];
        }
        return $b['pontos'] <=> $a['pontos'];
    });

    return $classificacao;
}

/* =========================
   CLASSIFICAÇÃO SUÍÇO FINAL
========================== */
public function classificacaoSuico($id_torneio)
{
    $rodadas = $this->listarRodadas($id_torneio);
    $ultimaRodada = !empty($rodadas) ? end($rodadas)['numero_rodada'] : 0;

    // Retorna a classificação parcial da última rodada
    return $this->classificacaoSuicoParcial($id_torneio, $ultimaRodada);
}
}

