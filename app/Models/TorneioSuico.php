<?php

require_once __DIR__ . '/Torneio.php';

class TorneioSuico extends Torneio
{
    public function listarParticipantesSuico($id_torneio)
    {
        $sql = "SELECT c.id_cliente, c.nome
                FROM torneio_participantes tp
                JOIN clientes c ON tp.id_cliente = c.id_cliente
                WHERE tp.id_torneio = ?
                ORDER BY c.nome ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_torneio]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarRodadaAtual($id_torneio)
    {
        $sql = "SELECT * FROM torneio_rodadas WHERE id_torneio = ? ORDER BY numero_rodada DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_torneio]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarPartidasDaRodada($id_rodada)
    {
        $sql = "SELECT p.*, c1.nome as nome_j1, c2.nome as nome_j2
                FROM torneio_partidas p
                JOIN clientes c1 ON p.id_jogador1 = c1.id_cliente
                LEFT JOIN clientes c2 ON p.id_jogador2 = c2.id_cliente
                WHERE p.id_rodada = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_rodada]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function gerarRodadaInicial($id_torneio)
    {
        $participantes = $this->listarParticipantesSuico($id_torneio);
        shuffle($participantes);

        $this->db->beginTransaction();
        try {
            // 1. Criar a Rodada
            $sqlRodada = "INSERT INTO torneio_rodadas (id_torneio, numero_rodada, status) VALUES (?, 1, 'em_andamento')";
            $this->db->prepare($sqlRodada)->execute([$id_torneio]);
            $id_rodada = $this->db->lastInsertId();

            // 2. Criar as Partidas
            while (count($participantes) > 1) {
                $j1 = array_shift($participantes)['id_cliente'];
                $j2 = array_shift($participantes)['id_cliente'];

                $sqlPartida = "INSERT INTO torneio_partidas (id_rodada, id_jogador1, id_jogador2) VALUES (?, ?, ?)";
                $this->db->prepare($sqlPartida)->execute([$id_rodada, $j1, $j2]);
            }

            // 3. Tratar o BYE (Ímpar)
            if (!empty($participantes)) {
                $bye = array_shift($participantes)['id_cliente'];
                $sqlBye = "INSERT INTO torneio_partidas (id_rodada, id_jogador1, id_jogador2, resultado) VALUES (?, ?, NULL, 'jogador1_vitoria')";
                $this->db->prepare($sqlBye)->execute([$id_rodada, $bye]);
            }

            $this->db->commit();
            return $id_rodada;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

	public function calcularRanking($id_torneio) {
		$participantes = $this->listarParticipantesSuico($id_torneio);
		$ranking = [];

		foreach ($participantes as $p) {
			$id = $p['id_cliente'];

			$sql = "SELECT p.* FROM torneio_partidas p
					JOIN torneio_rodadas r ON p.id_rodada = r.id_rodada
					WHERE r.id_torneio = ? AND (p.id_jogador1 = ? OR p.id_jogador2 = ?)";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([$id_torneio, $id, $id]);
			$partidas = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$pontos = 0;
			$vitorias = 0;
			$derrotas = 0;
			$empates = 0;
			$byes = 0;
			$vitorias2x0 = 0;
			$oponentesIds = [];

			foreach ($partidas as $pt) {
				$isJ1 = ($pt['id_jogador1'] == $id);
				$res = $pt['resultado'];
				$oponente = $isJ1 ? $pt['id_jogador2'] : $pt['id_jogador1'];

				if (empty($res)) continue;

				// 1. Identificar se foi BYE (Jogador 2 é nulo)
				if (empty($oponente)) {
					$pontos += 3;
					$vitorias++;
					$byes++;
					continue; // Pula para a próxima partida
				}

				$oponentesIds[] = $oponente;

				// 2. Lógica de Pontuação detalhada
				if ($res == 'empate') {
					$pontos += 1;
					$empates++;
				} else {
					// Verifica se este jogador é o vencedor
					$venceu = ($pt['vencedor_id'] == $id);

					if ($venceu) {
						$pontos += 3;
						$vitorias++;
						if ($res == 'jogador1_2x0' || $res == 'jogador2_2x0') {
							$vitorias2x0++;
						}
					} else {
						$derrotas++;
					}
				}
			}

			$ranking[$id] = [
				'id_cliente' => $id,
				'nome' => $p['nome'],
				'pontos' => $pontos,
				'vitorias' => $vitorias,
				'derrotas' => $derrotas,
				'empates' => $empates,
				'byes' => $byes,
				'vitorias2x0' => $vitorias2x0,
				'oponentes' => $oponentesIds,
				'buchholz' => 0
			];
		}

		// Cálculo do Buchholz
		foreach ($ranking as $id => &$dados) {
			foreach ($dados['oponentes'] as $opID) {
				if (isset($ranking[$opID])) {
					$dados['buchholz'] += $ranking[$opID]['pontos'];
				}
			}
		}
		unset($dados);

		// Ordenação
		usort($ranking, function($a, $b) {
			if ($b['pontos'] != $a['pontos']) return $b['pontos'] - $a['pontos'];
			if ($b['buchholz'] != $a['buchholz']) return $b['buchholz'] - $a['buchholz'];
			if ($b['vitorias'] != $a['vitorias']) return $b['vitorias'] - $a['vitorias'];
			return $b['vitorias2x0'] - $a['vitorias2x0'];
		});

		return $ranking;
	}

	public function gerarProximaRodada($id_torneio) {
		$ranking = $this->calcularRanking($id_torneio);
		$rodadaAtual = $this->buscarRodadaAtual($id_torneio);

		// Cálculo do total de rodadas
		$totalJogadores = count($ranking);
		$totalRodadasPrevistas = ($totalJogadores > 0) ? ceil(log($totalJogadores, 2)) : 0;

		$novoNumeroRodada = $rodadaAtual['numero_rodada'] + 1;

		// SE JÁ ATINGIMOS O LIMITE DE RODADAS, FINALIZA O TORNEIO
		if ($novoNumeroRodada > $totalRodadasPrevistas) {
			$sql = "UPDATE torneios SET status = 'finalizado' WHERE id_torneio = ?";
			return $this->db->prepare($sql)->execute([$id_torneio]);
		}

		// Busca histórico de confrontos para não repetir
		$sqlH = "SELECT id_jogador1, id_jogador2 FROM torneio_partidas p
				 JOIN torneio_rodadas r ON p.id_rodada = r.id_rodada WHERE r.id_torneio = ?";
		$stmtH = $this->db->prepare($sqlH);
		$stmtH->execute([$id_torneio]);
		$historico = $stmtH->fetchAll(PDO::FETCH_ASSOC);

		$confrontosRealizados = [];
		foreach ($historico as $h) {
			$confrontosRealizados[$h['id_jogador1']][] = $h['id_jogador2'];
			$confrontosRealizados[$h['id_jogador2']][] = $h['id_jogador1'];
		}

		$this->db->beginTransaction();
		try {
			$sqlR = "INSERT INTO torneio_rodadas (id_torneio, numero_rodada, status) VALUES (?, ?, 'em_andamento')";
			$this->db->prepare($sqlR)->execute([$id_torneio, $novoNumeroRodada]);
			$id_rodada = $this->db->lastInsertId();

			$jogadores = $ranking;

			while (count($jogadores) > 0) {
				$j1 = array_shift($jogadores);
				$j2_index = -1;

				// Tenta encontrar o oponente mais próximo no ranking que ainda não enfrentou
				foreach ($jogadores as $index => $j2_potencial) {
					if (!isset($confrontosRealizados[$j1['id_cliente']]) ||
						!in_array($j2_potencial['id_cliente'], $confrontosRealizados[$j1['id_cliente']])) {
						$j2_index = $index;
						break;
					}
				}

				if ($j2_index !== -1) {
					$j2 = array_splice($jogadores, $j2_index, 1)[0];
					$sqlP = "INSERT INTO torneio_partidas (id_rodada, id_jogador1, id_jogador2) VALUES (?, ?, ?)";
					$this->db->prepare($sqlP)->execute([$id_rodada, $j1['id_cliente'], $j2['id_cliente']]);
				} else {
					// Se não achou ninguém (sobrou ímpar ou todos já jogaram entre si), vira BYE
					$sqlP = "INSERT INTO torneio_partidas (id_rodada, id_jogador1, id_jogador2, resultado) VALUES (?, ?, NULL, 'jogador1_vitoria')";
					$this->db->prepare($sqlP)->execute([$id_rodada, $j1['id_cliente']]);
				}
			}

			$this->db->commit();
			return true;
		} catch (Exception $e) {
			$this->db->rollBack();
			return false;
		}
	}

	public function atualizarResultadoPartida($id_partida, $resultado) {
		// 1. Busca os dados da partida para saber quem são os jogadores
		$stmt = $this->db->prepare("SELECT id_jogador1, id_jogador2 FROM torneio_partidas WHERE id_partida = ?");
		$stmt->execute([$id_partida]);
		$p = $stmt->fetch(PDO::FETCH_ASSOC);

		if (!$p) return false;

		// 2. Define o vencedor_id com base no texto do enum
		$vencedor_id = null;
		if (strpos($resultado, 'jogador1') !== false) {
			$vencedor_id = $p['id_jogador1'];
		} elseif (strpos($resultado, 'jogador2') !== false) {
			$vencedor_id = $p['id_jogador2'];
		}

		// 3. Salva no banco
		$sql = "UPDATE torneio_partidas SET resultado = ?, vencedor_id = ? WHERE id_partida = ?";
		return $this->db->prepare($sql)->execute([$resultado, $vencedor_id, $id_partida]);
	}

	public function buscarLoja($id_loja) {
		$stmt = $this->db->prepare("SELECT * FROM lojas WHERE id_loja = ?");
		$stmt->execute([$id_loja]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function buscarRodadaPorNumero($id_torneio, $numero_rodada) {
		$sql = "SELECT id_rodada FROM torneio_rodadas WHERE id_torneio = ? AND numero_rodada = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$id_torneio, $numero_rodada]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
    }

	public function resetarPartida($id_partida) {
		$sql = "UPDATE torneio_partidas SET resultado = NULL, vencedor_id = NULL WHERE id_partida = ?";
		$stmt = $this->db->prepare($sql);
		return $stmt->execute([$id_partida]);
	}
}
