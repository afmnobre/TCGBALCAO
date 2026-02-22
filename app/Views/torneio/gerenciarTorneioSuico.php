<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-light mb-3">Gerenciar Suíço: <?= htmlspecialchars($torneio['nome_torneio']) ?></h2>
            <div class="card bg-dark text-light border-secondary mb-4">
					<div class="card-body">
						<strong>Participantes:</strong> <?= count($participantes) ?> |
						<strong>Rodada Atual:</strong> <?= $rodada['numero_rodada'] ?> de <?= $totalRodadas ?> |
						<strong>Status:</strong>
						<?php if ($torneio['status'] === 'finalizado'): ?>
							<span class="badge bg-success">FINALIZADO</span>
						<?php else: ?>
							<span class="badge bg-primary">EM ANDAMENTO</span>
						<?php endif; ?>
					</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card bg-dark text-light border-secondary">
                <div class="card-header border-secondary"><h5>Ranking Atual</h5></div>
                <div class="card-body p-0">
                    <table class="table table-dark table-sm table-hover mb-0">
                        <thead>
                            <tr class="small text-muted">
                                <th>Pos</th>
                                <th>Nome</th>
                                <th class="text-center">Pts</th>
                                <th class="text-center">BH</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($participantes as $index => $p): ?>
                            <tr>
                                <td><?= $index + 1 ?>º</td>
                                <td><?= htmlspecialchars($p['nome']) ?></td>
                                <td class="text-center fw-bold text-success"><?= $p['pontos'] ?></td>
                                <td class="text-center small"><?= $p['buchholz'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card bg-dark text-light border-secondary">
                <div class="card-header border-secondary d-flex justify-content-between align-items-center">
                    <h5>Partidas - Rodada <?= $rodada['numero_rodada'] ?></h5>
                    <span class="badge bg-info"><?= strtoupper($rodada['status']) ?></span>
                </div>
						<div class="mb-3">
							<button class="btn btn-sm btn-info" onclick="window.open('/torneiosuico/verPareamento/<?= $torneio['id_torneio'] ?>/<?= $rodada['numero_rodada'] ?>','_blank')">
								<i class="fas fa-broadcast-tower"></i> Transmitir Pareamento R<?= $rodada['numero_rodada'] ?>
							</button>
                            <?php if ($rodada['numero_rodada'] > 1 || $todasConcluidas): ?>
                                <button class="btn btn-sm btn-success" onclick="window.open('/torneiosuico/verPontuacao/<?= $torneio['id_torneio'] ?>','_blank')">
                                    <i class="fas fa-chart-line"></i>
                                    <?= ($torneio['status'] === 'finalizado') ? 'Transmitir Classificação Final' : 'Transmitir Classificação Parcial R' . $rodada['numero_rodada'] ?>
                                </button>
                            <?php endif; ?>
						</div>
                <div class="card-body">
						<?php foreach ($partidas as $partida): ?>
							<div class="p-3 mb-3 border border-secondary rounded bg-black">
								<div class="row align-items-center">
									<div class="col-md-4 text-center">
										<h6><?= htmlspecialchars($partida['nome_j1']) ?></h6>
									</div>
									<div class="col-md-4 text-center text-warning h5">VS</div>
									<div class="col-md-4 text-center">
										<h6><?= $partida['id_jogador2'] ? htmlspecialchars($partida['nome_j2']) : 'BYE' ?></h6>
									</div>
								</div>

								<div class="mt-3 text-center border-top border-secondary pt-2">
									<?php if (!$partida['resultado']): ?>
										<form action="/torneiosuico/salvarResultado" method="POST" class="btn-group">
											<input type="hidden" name="id_partida" value="<?= $partida['id_partida'] ?>">
											<input type="hidden" name="id_torneio" value="<?= $torneio['id_torneio'] ?>">

											<?php if ($torneio['tipo_torneio'] == 'suico_bo3'): ?>
												<button name="resultado" value="jogador1_2x0" class="btn btn-sm btn-outline-success">2x0</button>
												<button name="resultado" value="jogador1_2x1" class="btn btn-sm btn-outline-success">2x1</button>
												<button name="resultado" value="empate" class="btn btn-sm btn-outline-warning">Emp</button>
												<button name="resultado" value="jogador2_2x1" class="btn btn-sm btn-outline-primary">2x1</button>
												<button name="resultado" value="jogador2_2x0" class="btn btn-sm btn-outline-primary">2x0</button>
											<?php else: ?>
												<button name="resultado" value="jogador1_vitoria" class="btn btn-sm btn-success">Vit J1</button>
												<button name="resultado" value="empate" class="btn btn-sm btn-warning">Empate</button>
												<button name="resultado" value="jogador2_vitoria" class="btn btn-sm btn-primary">Vit J2</button>
											<?php endif; ?>
										</form>

									<?php else: ?>
										<?php
											$score = '';
											if (strpos($partida['resultado'], '2x0') !== false) $score = ' (2x0)';
											if (strpos($partida['resultado'], '2x1') !== false) $score = ' (2x1)';

											if ($partida['resultado'] === 'empate') {
												$statusTexto = '<span class="badge bg-warning text-dark">Empate' . $score . '</span>';
											} elseif (empty($partida['id_jogador2'])) {
												$statusTexto = '<span class="badge bg-info text-dark">' . htmlspecialchars($partida['nome_j1'] ?? '') . ' - Vitória por BYE</span>';
											} else {
												$nomeVencedor = ($partida['vencedor_id'] == $partida['id_jogador1']) ? $partida['nome_j1'] : $partida['nome_j2'];
												$statusTexto = '<span class="badge bg-success">' . htmlspecialchars($nomeVencedor ?? '') . ' - Vitória' . $score . '</span>';
											}
										?>
										<?= $statusTexto ?>

											<?php
												$statusAtual = strtolower($torneio['status'] ?? '');
												if ($statusAtual === 'em_andamento' || $statusAtual === 'andamento'):
											?>
												<a href="/torneiosuico/limparResultado/<?= $partida['id_partida'] ?>/<?= $torneio['id_torneio'] ?>"
												   class="ms-2 btn btn-xs btn-outline-secondary"
												   onclick="return confirm('Deseja zerar o resultado desta partida?')"
												   style="padding: 0px 5px; font-size: 10px;">
												   <i class="fas fa-undo"></i> Desfazer
												</a>
											<?php endif; ?>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
						<?php if ($podeGerarProxima): ?>
							<div class="mt-4 text-center">
								<a href="/torneiosuico/proximaRodada/<?= $torneio['id_torneio'] ?>" class="btn btn-warning btn-lg shadow fw-bold">
									FINALIZAR RODADA E GERAR PRÓXIMA
								</a>
							</div>
						<?php elseif ($todasConcluidas && $torneio['status'] === 'finalizado'): ?>
							<div class="mt-4 alert alert-success text-center">
								<h4><i class="fas fa-trophy text-warning"></i> Torneio Finalizado!</h4>
								<p class="mb-0">O grande vencedor é: <strong><?= $participantes[0]['nome'] ?></strong></p>
							</div>
						<?php elseif ($todasConcluidas && $rodada['numero_rodada'] == $totalRodadas): ?>
							<div class="mt-4 text-center">
								<a href="/torneiosuico/proximaRodada/<?= $torneio['id_torneio'] ?>" class="btn btn-danger btn-lg shadow fw-bold">
									ENCERRAR TORNEIO
								</a>
							</div>
						<?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
