<?php

require_once __DIR__ . '/Torneio.php';

class TorneioEliminacao extends Torneio
{
    /**
     * Inicia o torneio gerando a primeira rodada da Winners Bracket
     */
    public function iniciarTorneio($id_torneio)
    {
        $participantes = $this->listarParticipantes($id_torneio);
        shuffle($participantes);

        try {
            $this->db->beginTransaction();

            // Criar a primeira rodada
            $sqlRodada = "INSERT INTO torneio_rodadas (id_torneio, numero_rodada, status) VALUES (?, 1, 'em_andamento')";
            $this->db->prepare($sqlRodada)->execute([$id_torneio]);
            $id_rodada = $this->db->lastInsertId();

            // Gerar partidas iniciais (Winners)
            for ($i = 0; $i < count($participantes); $i += 2) {
                $p1 = $participantes[$i]['id_cliente'];
                $p2 = isset($participantes[$i + 1]) ? $participantes[$i + 1]['id_cliente'] : null;
                $ordem = ($i / 2) + 1;

                $sqlPartida = "INSERT INTO torneio_partidas
                               (id_rodada, id_jogador1, id_jogador2, chave, ordem)
                               VALUES (?, ?, ?, 'winners', ?)";
                $stmt = $this->db->prepare($sqlPartida);
                $stmt->execute([$id_rodada, $p1, $p2, $ordem]);

                // Tratamento automático de BYE
                if ($p2 === null) {
                    $id_partida = $this->db->lastInsertId();
                    $this->db->prepare("UPDATE torneio_partidas SET resultado = 'vitoria_bye', vencedor_id = ? WHERE id_partida = ?")
                             ->execute([$p1, $id_partida]);
                }
            }

            // Atualiza status do torneio para em_andamento
            $this->db->prepare("UPDATE torneios SET status = 'em_andamento' WHERE id_torneio = ?")
                     ->execute([$id_torneio]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Lista partidas organizadas para exibição na View de Eliminatória
     */
    public function buscarPartidasDaRodada($id_rodada)
    {
        $sql = "SELECT p.*,
                       j1.nome as nome_j1,
                       j2.nome as nome_j2
                FROM torneio_partidas p
                LEFT JOIN clientes j1 ON p.id_jogador1 = j1.id_cliente
                LEFT JOIN clientes j2 ON p.id_jogador2 = j2.id_cliente
                WHERE p.id_rodada = ?
                ORDER BY p.chave DESC, p.ordem ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_rodada]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca a rodada atual do torneio
     */
    public function buscarRodadaAtual($id_torneio)
    {
        $sql = "SELECT * FROM torneio_rodadas WHERE id_torneio = ? ORDER BY numero_rodada DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_torneio]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
