<h2 class="text-light mb-3">Gerenciar Torneio - <?= htmlspecialchars($torneio["nome_torneio"]) ?></h2>

<?php
$numJogadores = count($torneioModel->listarParticipantes($torneio["id_torneio"]));
$maxRodadas = ceil(log($numJogadores, 2));
?>

<p class="text-light">
    <strong>Cardgame:</strong> <?= htmlspecialchars($torneio["cardgame"]) ?> |
    <strong>Tipo:</strong> <?= htmlspecialchars($torneio["tipo_legivel"] ?? "") ?><br>
    <strong>Participantes:</strong> <?= $numJogadores ?><br>
    <strong>Total de Rodadas:</strong> <?= $maxRodadas ?>
</p>

<?php if (str_contains($torneio["tipo_torneio"], "suico")): ?>
    <button class="btn btn-sm btn-info mb-3" data-bs-toggle="modal" data-bs-target="#popupRegrasSuico">
        📖 Regras do Torneio Suíço
    </button>
<?php endif; ?>

<hr class="border-secondary">

<?php if (!empty($torneio["tempo_rodada"])): ?>
    <div class="mb-3">
        <div id="timer" class="fw-bold text-danger">Tempo restante: <span id="time"></span></div>
        <button id="startBtn" class="btn btn-success btn-sm">Iniciar</button>
        <button id="pauseBtn" class="btn btn-warning btn-sm">Pausar</button>
    </div>
    <script>
        var minutes = <?= (int) $torneio["tempo_rodada"] ?>;
        var seconds = minutes * 60;
        var interval = null;
        function updateTimerDisplay() {
            var min = Math.floor(seconds / 60);
            var sec = seconds % 60;
            document.getElementById('time').textContent = min + ":" + (sec < 10 ? "0" + sec : sec);
        }
        function startTimer() {
            if (interval) return;
            interval = setInterval(function() {
                if (seconds <= 0) { clearInterval(interval); interval = null; document.getElementById('time').textContent = "Tempo esgotado!"; return; }
                seconds--; updateTimerDisplay();
            }, 1000);
        }
        function pauseTimer() { clearInterval(interval); interval = null; }
        document.getElementById('startBtn').addEventListener('click', startTimer);
        document.getElementById('pauseBtn').addEventListener('click', pauseTimer);
        updateTimerDisplay();
    </script>
<?php endif; ?>

<?php $temRodadaComCombo = false; ?>
<?php foreach ($rodadas as $rodada): ?>
    <div class="card bg-dark text-light mb-4 border-secondary">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <strong>Rodada <?= $rodada["numero_rodada"] ?></strong>
                <span class="badge bg-<?= $rodada["status"] == "em_andamento" ? "success" : ($rodada["status"] == "finalizada" ? "secondary" : "warning") ?>">
                    <?= ucfirst($rodada["status"]) ?>
                </span>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#pareamentoRodada<?= $rodada["numero_rodada"] ?>">👁️ Ver Pareamento</button>

                <?php if ($rodada["status"] === "finalizada"): ?>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#pontuacaoRodada<?= $rodada["numero_rodada"] ?>">👁️ Ver Pontuação</button>
                <?php endif; ?>

                <button class="btn btn-sm btn-info" onclick="window.open('/torneio/verPareamento/<?= $torneio['id_torneio'] ?>/<?= $rodada['numero_rodada'] ?>','_blank')">📡 Transmitir Pareamento</button>

                <?php if ($rodada["status"] === "finalizada"): ?>
                    <button class="btn btn-sm btn-success" onclick="window.open('/torneio/verPontuacao/<?= $torneio['id_torneio'] ?>/<?= $rodada['numero_rodada'] ?>?tipo_torneio=<?= $torneio['tipo_torneio'] ?>','_blank')">📡 Transmitir Pontuação</button>
                <?php endif; ?>
            </div>
        </div>

        <div class="card-body">
            <?php $pareamentos = $torneioModel->listarPareamentos($torneio["id_torneio"], $rodada["numero_rodada"]); ?>
            <?php if (!empty($pareamentos)): ?>
                <?php if ($rodada["status"] == "finalizada"): ?>
                    <table class="table table-dark table-bordered align-middle">
                        <thead><tr><th>Jogador 1</th><th>Jogador 2</th><th>Resultado</th></tr></thead>
                        <tbody>
                            <?php foreach ($pareamentos as $partida): ?>
                                <tr>
                                    <td><?= htmlspecialchars($partida["jogador1"] ?? "") ?></td>
                                    <td><?= $partida["jogador2"] ? htmlspecialchars($partida["jogador2"]) : "BYE" ?></td>
                                    <td>
                                        <span class="text-info fw-bold">
                                            <?php
                                            $res = $partida["resultado"];
                                            if (empty($partida["jogador2"])) {
                                                echo htmlspecialchars($partida["jogador1"]) . " - Vitória(Bye)";
                                            } elseif ($res == 'empate') {
                                                echo "Empate";
                                            } elseif (str_contains($res, 'jogador1')) {
                                                $placar = str_replace(['jogador1_', 'vitoria'], '', $res);
                                                echo htmlspecialchars($partida["jogador1"]) . " - Vitória" . ($placar ? "($placar)" : "");
                                            } elseif (str_contains($res, 'jogador2')) {
                                                $placar = str_replace(['jogador2_', 'vitoria'], '', $res);
                                                echo htmlspecialchars($partida["jogador2"]) . " - Vitória" . ($placar ? "($placar)" : "");
                                            } else {
                                                echo $res;
                                            }
                                            ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php elseif (!$temRodadaComCombo): ?>
                    <form action="/torneio/salvarResultado/<?= $torneio["id_torneio"] ?>/<?= $rodada["id_rodada"] ?>" method="POST">
                        <table class="table table-dark table-bordered align-middle">
                            <thead><tr><th>Jogador 1</th><th>Jogador 2</th><th>Resultado</th></tr></thead>
                            <tbody>
                                <?php foreach ($pareamentos as $partida): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($partida["jogador1"] ?? "") ?></td>
                                        <td><?= $partida["jogador2"] ? htmlspecialchars($partida["jogador2"]) : "BYE" ?></td>
                                        <td>
                                            <?php if (empty($partida["jogador2"])): ?>
                                                <input type="hidden" name="resultados[<?= $partida["id_partida"] ?>]" value="jogador1_2x1">
                                                <span class="badge bg-success">Vitória (BYE)</span>
                                            <?php else: ?>
                                                <select name="resultados[<?= $partida["id_partida"] ?>]" class="form-select bg-dark text-light border-secondary" required>
                                                    <option value="">Selecione...</option>
                                                    <?php if (str_contains($torneio["tipo_torneio"], "bo3")): ?>
                                                        <option value="jogador1_2x0">Vitória <?= htmlspecialchars($partida["jogador1"]) ?> (2x0)</option>
                                                        <option value="jogador2_2x0">Vitória <?= htmlspecialchars($partida["jogador2"]) ?> (2x0)</option>
                                                        <option value="jogador1_2x1">Vitória <?= htmlspecialchars($partida["jogador1"]) ?> (2x1)</option>
                                                        <option value="jogador2_2x1">Vitória <?= htmlspecialchars($partida["jogador2"]) ?> (2x1)</option>
                                                        <option value="empate">Empate</option>
                                                    <?php else: ?>
                                                        <option value="jogador1_vitoria">Vitória <?= htmlspecialchars($partida["jogador1"]) ?></option>
                                                        <option value="jogador2_vitoria">Vitória <?= htmlspecialchars($partida["jogador2"]) ?></option>
                                                        <option value="empate">Empate</option>
                                                    <?php endif; ?>
                                                </select>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">Salvar Resultados</button>
                    </form>
                    <?php $temRodadaComCombo = true; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>

<?php foreach ($rodadas as $rodada): ?>
    <div class="modal fade" id="pareamentoRodada<?= $rodada["numero_rodada"] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-dark text-light">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Pareamento Rodada <?= $rodada["numero_rodada"] ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php $pModal = $torneioModel->listarPareamentos($torneio["id_torneio"], $rodada["numero_rodada"]); ?>
                    <table class="table table-dark table-striped">
                        <thead><tr><th>Jogador 1</th><th>vs</th><th>Jogador 2</th></tr></thead>
                        <tbody>
                            <?php foreach ($pModal as $p): ?>
                                <tr><td><?= htmlspecialchars($p["jogador1"]) ?></td><td>vs</td><td><?= htmlspecialchars($p["jogador2"] ?? "BYE") ?></td></tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php if ($rodada["status"] === "finalizada"): ?>
        <div class="modal fade" id="pontuacaoRodada<?= $rodada["numero_rodada"] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content bg-dark text-light">
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title">Classificação - Rodada <?= $rodada["numero_rodada"] ?></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <?php
                        $classificacao = str_contains($torneio["tipo_torneio"], "suico")
                            ? $torneioModel->classificacaoSuicoParcial($torneio["id_torneio"], $rodada["numero_rodada"])
                            : $torneioModel->classificacaoElimDuplaParcial($torneio["id_torneio"], $rodada["numero_rodada"]);
                        ?>
                        <div class="table-responsive">
                            <table class="table table-dark table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>Posição</th><th>Jogador</th><th>Vitórias</th><th>Derrotas</th><th>Empates</th><th>BYE</th><th>Pontos</th><th>Força dos Oponentes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $posicao = 1; foreach ($classificacao as $linha): ?>
                                        <tr>
                                            <td><?= $posicao ?></td>
                                            <td><?= htmlspecialchars($linha['nome']) ?> <?php if ($posicao === 1): ?><span class="badge bg-warning text-dark">🏆 Campeão</span><?php endif; ?></td>
                                            <td><?= $linha['vitorias'] ?? 0 ?></td>
                                            <td><?= $linha['derrotas'] ?? 0 ?></td>
                                            <td><?= $linha['empates'] ?? 0 ?></td>
                                            <td><?= $linha['bye'] ?? 0 ?></td>
                                            <td><?= $linha['pontos'] ?></td>
                                            <td><?= $linha['forca_oponentes'] ?></td>
                                        </tr>
                                    <?php $posicao++; endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>

<?php
$todasFinalizadas = true;
foreach ($rodadas as $r) { if ($r["status"] !== "finalizada") { $todasFinalizadas = false; break; } }
if ($todasFinalizadas):
    $classificacaoFinal = str_contains($torneio["tipo_torneio"], "suico") ? $torneioModel->classificacaoSuico($torneio["id_torneio"]) : $torneioModel->classificacaoElimDupla($torneio["id_torneio"]);
?>
    <div class="card bg-dark text-light mt-4 border-warning">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>🏆 Classificação Final</strong>
            <button class="btn btn-sm btn-primary" onclick="window.open('/torneio/verResultadoSuico/<?= $torneio['id_torneio'] ?>','_blank')">🏆 Resultado</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Posição</th><th>Jogador</th><th>Vitórias</th><th>Derrotas</th><th>Empates</th><th>BYE</th><th>Pontos</th><th>Força dos Oponentes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $posicao = 1; foreach ($classificacaoFinal as $linha): ?>
                            <tr>
                                <td><?= $posicao ?></td>
                                <td><?= htmlspecialchars($linha['nome']) ?> <?php if ($posicao === 1): ?><span class="badge bg-warning text-dark">🏆 Campeão</span><?php endif; ?></td>
                                <td><?= $linha['vitorias'] ?? 0 ?></td>
                                <td><?= $linha['derrotas'] ?? 0 ?></td>
                                <td><?= $linha['empates'] ?? 0 ?></td>
                                <td><?= $linha['bye'] ?? 0 ?></td>
                                <td><?= $linha['pontos'] ?></td>
                                <td><?= $linha['forca_oponentes'] ?></td>
                            </tr>
                        <?php $posicao++; endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="modal fade" id="popupRegrasSuico" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">Regras do Torneio Suíço</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h4>Melhor de 1 (MD1)</h4>
                <ul>
                    <li>Vitória: 3 pontos</li>
                    <li>Derrota: 0 pontos</li>
                    <li>Empate: 1 ponto para cada jogador</li>
                    <li>BYE: 2 pontos (vitória automática)</li>
                </ul>
                <h4>Melhor de 3 (MD3)</h4>
                <ul>
                    <li>Vitória por 2x0: 3 pontos</li>
                    <li>Vitória por 2x1: 2 pontos</li>
                    <li>Derrota por 1x2: 1 ponto</li>
                    <li>Derrota por 0x2: 0 pontos</li>
                    <li>Empate: 1 ponto para cada jogador</li>
                    <li>BYE: 2 pontos (vitória automática)</li>
                </ul>
                <h4>Critérios de desempate</h4>
                <ol>
                    <li>Pontos totais</li>
                    <li>Força dos oponentes (Buchholz)</li>
                    <li>Número de vitórias por 2x0</li>
                    <li>Número total de vitórias</li>
                    <li>Confronto direto</li>
                </ol>
            </div>
        </div>
    </div>
    </div>
<br><br><br>
