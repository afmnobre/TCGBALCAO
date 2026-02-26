<?php
require_once __DIR__ . '/Torneio.php';

class TorneioEliminacao extends Torneio {

    /**
     * Busca dados básicos do torneio
     */
    public function buscarTorneio($id) {
        $stmt = $this->db->prepare("SELECT * FROM torneios WHERE id_torneio = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Identifica qual a rodada ativa para controle de fluxo e UI
     */
    public function buscarRodadaAtual($id) {
        $stmt = $this->db->prepare("
            SELECT * FROM torneio_rodadas
            WHERE id_torneio = ?
            AND status = 'A'
            ORDER BY numero_rodada ASC, tipo_chave ASC LIMIT 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Inicializa a estrutura de Double Elimination (WB, LB e GF)
     */
    public function iniciarTorneio($id_torneio) {
        try {
            $this->db->beginTransaction();

            $stmtP = $this->db->prepare("SELECT id_cliente FROM torneio_participantes WHERE id_torneio = ?");
            $stmtP->execute([$id_torneio]);
            $participantes = $stmtP->fetchAll(PDO::FETCH_COLUMN);

            if (count($participantes) < 2) return false;

            shuffle($participantes);
            $total = count($participantes);
            $vagas = pow(2, ceil(log($total, 2)));
            $numRodadasWB = (int)log($vagas, 2);

            // 1. Criar Rodadas Winners
            $rodadasWB = [];
            for ($r = 1; $r <= $numRodadasWB; $r++) {
                $status = ($r == 1) ? 'A' : 'nao_iniciada';
                $stmt = $this->db->prepare("INSERT INTO torneio_rodadas (id_torneio, numero_rodada, status, tipo_chave) VALUES (?, ?, ?, 'WB')");
                $stmt->execute([$id_torneio, $r, $status]);
                $rodadasWB[$r] = $this->db->lastInsertId();
            }

            // 2. Criar Partidas Winners e tratar BYEs
            foreach ($rodadasWB as $numR => $id_r) {
                $numPartidas = $vagas / pow(2, $numR);
                for ($p = 0; $p < $numPartidas; $p++) {
                    $p1 = ($numR == 1) ? ($participantes[$p * 2] ?? null) : null;
                    $p2 = ($numR == 1) ? ($participantes[$p * 2 + 1] ?? null) : null;

                    $stmtPartida = $this->db->prepare("INSERT INTO torneio_partidas (id_rodada, id_jogador1, id_jogador2) VALUES (?, ?, ?)");
                    $stmtPartida->execute([$id_r, $p1, $p2]);
                    $id_nova_partida = $this->db->lastInsertId();

                    if ($numR == 1 && $p1 && !$p2) {
                        $this->db->prepare("UPDATE torneio_partidas SET vencedor_id = ?, resultado = 'BYE' WHERE id_partida = ?")
                                 ->execute([$p1, $id_nova_partida]);
                        $this->enviarParaProximaPartida($id_torneio, $p1, 2, 'WB');
                    }
                }
            }

            // 3. Criar Rodadas e Partidas Losers
            $numRodadasLB = ($numRodadasWB - 1) * 2;
            for ($r = 1; $r <= $numRodadasLB; $r++) {
                $stmt = $this->db->prepare("INSERT INTO torneio_rodadas (id_torneio, numero_rodada, status, tipo_chave) VALUES (?, ?, 'nao_iniciada', 'LB')");
                $stmt->execute([$id_torneio, $r]);
                $id_rlb = $this->db->lastInsertId();

                $nPartidasLB = pow(2, floor(($numRodadasLB - $r) / 2));
                for($i=0; $i < $nPartidasLB; $i++) {
                    $this->db->prepare("INSERT INTO torneio_partidas (id_rodada) VALUES (?)")->execute([$id_rlb]);
                }
            }

            // 4. Criar Grande Final
            $proximaRodadaGF = $numRodadasWB + 1;
            $stmtGF = $this->db->prepare("INSERT INTO torneio_rodadas (id_torneio, numero_rodada, status, tipo_chave) VALUES (?, ?, 'nao_iniciada', 'GF')");
            $stmtGF->execute([$id_torneio, $proximaRodadaGF]);
            $id_rgf = $this->db->lastInsertId();
            $this->db->prepare("INSERT INTO torneio_partidas (id_rodada) VALUES (?)")->execute([$id_rgf]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            return false;
        }
    }

    /**
     * Processa o resultado individual de uma partida
     */
    public function processarResultado($id_partida, $res) {
        $stmt = $this->db->prepare("
            SELECT p.*, r.numero_rodada, r.tipo_chave, r.id_torneio
            FROM torneio_partidas p
            JOIN torneio_rodadas r ON p.id_rodada = r.id_rodada
            WHERE p.id_partida = ?
        ");
        $stmt->execute([$id_partida]);
        $p = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$p) return false;

        if ($res === 'BYE') {
            $venc = $p['id_jogador1'];
        } else {
            $venc = (strpos($res, 'jogador1') !== false) ? $p['id_jogador1'] : $p['id_jogador2'];
        }

        $stmtUpdate = $this->db->prepare("UPDATE torneio_partidas SET vencedor_id = ?, resultado = ? WHERE id_partida = ?");
        $stmtUpdate->execute([$venc, $res, $id_partida]);

        $this->avancarJogadorNasChaves($id_partida, $venc);
        return true;
    }

    /**
     * Lógica central de movimentação de jogadores (Vencedores e Perdedores)
     */
    public function avancarJogadorNasChaves($id_partida_atual, $vencedor_id) {
        $stmt = $this->db->prepare("
            SELECT p.*, r.numero_rodada, r.tipo_chave, r.id_torneio
            FROM torneio_partidas p
            JOIN torneio_rodadas r ON p.id_rodada = r.id_rodada
            WHERE p.id_partida = ?
        ");
        $stmt->execute([$id_partida_atual]);
        $partida = $stmt->fetch(PDO::FETCH_ASSOC);

        $perdedor_id = ($vencedor_id == $partida['id_jogador1']) ? $partida['id_jogador2'] : $partida['id_jogador1'];
        $id_torneio = $partida['id_torneio'];

        if ($partida['tipo_chave'] == 'WB') {
            // VENCEDOR WB: Próxima WB ou Grande Final
            if ($partida['numero_rodada'] == 3) {
                $this->enviarParaProximaPartida($id_torneio, $vencedor_id, 1, 'GF');
            } else {
                $this->enviarParaProximaPartida($id_torneio, $vencedor_id, $partida['numero_rodada'] + 1, 'WB');
            }

            // PERDEDOR WB: Queda para Losers (Correção de Rodadas Alternadas)
            if ($perdedor_id) {
                $rodadaDestinoLB = 0;
                switch ($partida['numero_rodada']) {
                    case 1: $rodadaDestinoLB = 1; break;
                    case 2: $rodadaDestinoLB = 2; break;
                    case 3: $rodadaDestinoLB = 4; break;
                }
                if ($rodadaDestinoLB > 0) {
                    $this->enviarParaProximaPartida($id_torneio, $perdedor_id, $rodadaDestinoLB, 'LB');
                }
            }
        } elseif ($partida['tipo_chave'] == 'LB') {
            // VENCEDOR LB: Próxima LB ou Grande Final
            if ($partida['numero_rodada'] == 4) {
                $this->enviarParaProximaPartida($id_torneio, $vencedor_id, 1, 'GF');
            } else {
                $this->enviarParaProximaPartida($id_torneio, $vencedor_id, $partida['numero_rodada'] + 1, 'LB');
            }
        }
    }

    /**
     * Aloca o jogador na próxima vaga disponível da rodada alvo
     */
    private function enviarParaProximaPartida($id_torneio, $jogador_id, $proxima_rodada_num, $tipo_chave) {
        if (!$jogador_id) return;

        $stmtR = $this->db->prepare("SELECT id_rodada FROM torneio_rodadas WHERE id_torneio = ? AND numero_rodada = ? AND tipo_chave = ?");
        $stmtR->execute([$id_torneio, $proxima_rodada_num, $tipo_chave]);
        $id_rodada_destino = $stmtR->fetchColumn();

        if (!$id_rodada_destino) return;

        $stmtCheck = $this->db->prepare("SELECT id_partida FROM torneio_partidas WHERE id_rodada = ? AND (id_jogador1 = ? OR id_jogador2 = ?)");
        $stmtCheck->execute([$id_rodada_destino, $jogador_id, $jogador_id]);
        if ($stmtCheck->fetch()) return;

        $stmtVaga = $this->db->prepare("
            SELECT id_partida, id_jogador1
            FROM torneio_partidas
            WHERE id_rodada = ?
            AND (id_jogador1 IS NULL OR id_jogador2 IS NULL)
            ORDER BY id_partida ASC LIMIT 1
        ");
        $stmtVaga->execute([$id_rodada_destino]);
        $vaga = $stmtVaga->fetch(PDO::FETCH_ASSOC);

        if ($vaga) {
            $coluna = ($vaga['id_jogador1'] === null) ? 'id_jogador1' : 'id_jogador2';
            $this->db->prepare("UPDATE torneio_partidas SET $coluna = ? WHERE id_partida = ?")
                     ->execute([$jogador_id, $vaga['id_partida']]);
        }
    }

    /**
     * Busca partidas com nomes dos jogadores para exibição e log
     */
    public function buscarPartidasComNomes($id_torneio) {
        $sql = "SELECT p.*, r.numero_rodada, r.tipo_chave, c1.nome as nome_j1, c2.nome as nome_j2
                FROM torneio_partidas p
                JOIN torneio_rodadas r ON p.id_rodada = r.id_rodada
                LEFT JOIN clientes c1 ON p.id_jogador1 = c1.id_cliente
                LEFT JOIN clientes c2 ON p.id_jogador2 = c2.id_cliente
                WHERE r.id_torneio = ?
                ORDER BY r.numero_rodada ASC, p.id_partida ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_torneio]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * REGISTRADOR DE LOG: Captura o estado completo do torneio e da UI
     */
public function registrarLogEstado($id_torneio, $acao) {
    try {
        $partidas = $this->buscarPartidasComNomes($id_torneio);
        $rodadaAtiva = $this->buscarRodadaAtual($id_torneio);

        $snapshot = [
            'info_torneio' => [
                'rodada_ativa_numero' => $rodadaAtiva['numero_rodada'] ?? 'N/A',
                'rodada_ativa_chave'  => $rodadaAtiva['tipo_chave'] ?? 'N/A'
            ],
            'pareamentos' => []
        ];

        if ($partidas) {
            foreach ($partidas as $p) {
                $rodadaBate = ($p['numero_rodada'] == ($rodadaAtiva['numero_rodada'] ?? 0));
                $chaveBate  = ($p['tipo_chave'] == ($rodadaAtiva['tipo_chave'] ?? ''));
                $semVencedor = empty($p['vencedor_id']);
                $temJogador  = !empty($p['id_jogador1']) || !empty($p['id_jogador2']);

                $snapshot['pareamentos'][] = [
                    'id' => $p['id_partida'],
                    'ch' => $p['tipo_chave'],
                    'rd' => $p['numero_rodada'],
                    'ui' => ($rodadaBate && $chaveBate && $semVencedor && $temJogador) ? 'BOTÃO' : 'NÃO',
                    'p1' => $p['nome_j1'] ?? 'TBD',
                    'p2' => $p['nome_j2'] ?? 'TBD',
                    'v'  => $p['vencedor_id'] ?? '-'
                ];
            }
        }

        // JSON_UNESCAPED_UNICODE evita problemas com acentos (ex: Marta Nobre Maciel)
        $detalhesJson = json_encode($snapshot, JSON_UNESCAPED_UNICODE);

        $sql = "INSERT INTO torneio_debug_logs (id_torneio, acao, detalhes) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $executou = $stmt->execute([$id_torneio, $acao, $detalhesJson]);

        if (!$executou) {
            // Se não salvou, escreve o erro no log do PHP do servidor
            error_log("ERRO SQL LOG: " . implode(" - ", $stmt->errorInfo()));
        }

    } catch (Exception $e) {
        error_log("ERRO EXCEPTION LOG: " . $e->getMessage());
    }
}
}
