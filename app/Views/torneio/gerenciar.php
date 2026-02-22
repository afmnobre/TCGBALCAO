<h2 class="text-light mb-3">Gerenciar Torneio - <?= htmlspecialchars($torneio["nome_torneio"]) ?></h2>

<?php
$numJogadores = count($torneioModel->listarParticipantes($torneio["id_torneio"]));
// Cálculo de rodadas (ajuste conforme sua regra de negócio)
$maxRodadasGeral = ($numJogadores > 0) ? ceil(log($numJogadores, 2)) : 0;
?>

<p class="text-light">
    <strong>Cardgame:</strong> <?= htmlspecialchars($torneio["cardgame"]) ?> |
    <strong>Tipo:</strong> <?= htmlspecialchars($torneio["tipo_legivel"] ?? $torneio["tipo_torneio"]) ?><br>
    <strong>Participantes:</strong> <?= $numJogadores ?><br>
    <strong>Total de Rodadas:</strong> <?= $torneio["total_rodadas"] ?? $maxRodadasGeral ?>
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
                if (seconds <= 0) {
                    clearInterval(interval);
                    interval = null;
                    document.getElementById('time').textContent = "Tempo esgotado!";
                    return;
                }
                seconds--; updateTimerDisplay();
            }, 1000);
        }
        function pauseTimer() { clearInterval(interval); interval = null; }
        document.getElementById('startBtn').addEventListener('click', startTimer);
        document.getElementById('pauseBtn').addEventListener('click', pauseTimer);
        updateTimerDisplay();
    </script>
<?php endif; ?>

<?php if (str_contains($torneio["tipo_torneio"], "suico")): ?>

    <div class="main-content-suico">
        <?php
        $suico_jaExibiuFormulario = false;
        $suico_rodadas_list = $rodadas ?? [];
        ?>

        <?php foreach ($suico_rodadas_list as $r_suico): ?>
            <div class="card bg-dark text-light mb-4 border-secondary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Rodada <?= $r_suico["numero_rodada"] ?></strong>
                        <span class="badge bg-<?= ($r_suico["status"] == "em_andamento") ? "success" : "secondary" ?>">
                            <?= ucfirst($r_suico["status"]) ?>
                        </span>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#pareamentoSuico<?= $r_suico["numero_rodada"] ?>">👁️ Ver Pareamento</button>
                        <button class="btn btn-sm btn-outline-info" onclick="window.open('/torneio/verPareamento/<?= $torneio['id_torneio'] ?>/<?= $r_suico['numero_rodada'] ?>','_blank')">📡 Transmitir</button>
                    </div>
                </div>

                <div class="card-body">
                    <?php $pareamentos_da_rodada = $torneioModel->listarPareamentos($torneio["id_torneio"], $r_suico["numero_rodada"]); ?>

                    <?php
                    $isUltimaRodada = ($r_suico === end($suico_rodadas_list));
                    if ($r_suico["status"] == "em_andamento" && !$suico_jaExibiuFormulario && $isUltimaRodada):
                        $suico_jaExibiuFormulario = true;
                    ?>
                        <form action="/torneio/salvarResultado/<?= $torneio["id_torneio"] ?>/<?= $r_suico["id_rodada"] ?>" method="POST">
                            <table class="table table-dark align-middle">
                                <thead>
                                    <tr>
                                        <th>Jogadores</th>
                                        <th>Resultado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pareamentos_da_rodada as $partida): ?>
                                        <tr>
                                            <td>
                                                <span class="fw-bold"><?= htmlspecialchars($partida["jogador1"]) ?></span>
                                                <small class="text-muted mx-2">vs</small>
                                                <span class="fw-bold"><?= htmlspecialchars($partida["jogador2"] ?? "BYE") ?></span>
                                            </td>
                                            <td>
                                                <?php if (empty($partida["jogador2"]) || $partida["jogador2"] == "BYE"): ?>
                                                    <input type="hidden" name="resultados[<?= $partida["id_partida"] ?>]" value="jogador1_2x0">
                                                    <span class="badge bg-success">Vitória automática (BYE)</span>
                                                <?php else: ?>
                                                    <select name="resultados[<?= $partida["id_partida"] ?>]" class="form-select form-select-sm bg-dark text-light border-secondary" required>
                                                        <option value="">Lançar...</option>
                                                        <option value="jogador1_2x0">2x0 - <?= htmlspecialchars($partida["jogador1"]) ?></option>
                                                        <option value="jogador1_2x1">2x1 - <?= htmlspecialchars($partida["jogador1"]) ?></option>
                                                        <option value="jogador2_2x0">2x0 - <?= htmlspecialchars($partida["jogador2"]) ?></option>
                                                        <option value="jogador2_2x1">2x1 - <?= htmlspecialchars($partida["jogador2"]) ?></option>
                                                        <option value="empate">Empate</option>
                                                    </select>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-primary w-100 mt-2">Finalizar Rodada <?= $r_suico["numero_rodada"] ?></button>
                        </form>
                    <?php else: ?>
                        <table class="table table-dark table-sm">
                            <?php foreach ($pareamentos_da_rodada as $p): ?>
                                <tr>
                                    <td><?= htmlspecialchars($p["jogador1"]) ?> vs <?= htmlspecialchars($p["jogador2"] ?? "BYE") ?></td>
                                    <td class="text-info"><?= str_replace('_', ' ', $p["resultado"] ?? 'Pendente') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="modal fade" id="popupRegrasSuico" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content bg-dark text-light">
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title">Regras do Torneio Suíço</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <h4>Melhor de 3 (MD3)</h4>
                        <ul><li>Vitória: 3 pts | Empate: 1 pt | BYE: 3 pts</li></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php elseif (str_contains($torneio["tipo_torneio"], "eliminacao") || str_contains($torneio["tipo_torneio"], "double")): ?>

    <div class="main-content-eliminacao">
        <?php
        $chaves_unificadas = array_merge(array_keys($rodadasWB ?? []), array_keys($rodadasLB ?? []));
        $max_rodadas_ed = !empty($chaves_unificadas) ? max($chaves_unificadas) : 0;

        // Renderiza Rodadas Finalizadas
        for ($i = 1; $i <= $max_rodadas_ed; $i++):
            $ed_rWB = $rodadasWB[$i] ?? null;
            $ed_rLB = $rodadasLB[$i] ?? null;
            if (($ed_rWB && $ed_rWB['status'] === 'finalizada') || ($ed_rLB && $ed_rLB['status'] === 'finalizada')): ?>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <?php if ($ed_rWB && $ed_rWB['status'] === 'finalizada'): ?>
                            <h5 class="text-muted">Winner Bracket - Rodada <?= $i ?> (Fim)</h5>
                            <?php $rodada = $ed_rWB; include "_blocoPartidas.php"; ?>
                        <?php endif; ?>
                    </div>
                    <?php if ($ed_rLB && $ed_rLB['status'] === 'finalizada'): ?>
                        <div class="col-md-6 border-start border-secondary">
                            <h5 class="text-muted">Loser Bracket - Rodada <?= $i ?> (Fim)</h5>
                            <?php $rodada = $ed_rLB; include "_blocoPartidas.php"; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <hr class="border-secondary opacity-25">
            <?php endif; ?>
        <?php endfor; ?>

        <?php
        // Identifica rodadas em andamento
        $abertaWB = null; $abertaLB = null;
        foreach (($rodadasWB ?? []) as $r) { if ($r['status'] === 'em_andamento') $abertaWB = $r; }
        foreach (($rodadasLB ?? []) as $r) { if ($r['status'] === 'em_andamento') $abertaLB = $r; }

        if ($abertaWB || $abertaLB): ?>
            <form action="/torneio/salvarResultado/<?= $torneio['id_torneio'] ?>" method="POST">
                <div class="row mb-4 bg-dark p-3 border border-primary rounded shadow">
                    <?php if ($abertaWB): ?>
                        <div class="col-md-<?= $abertaLB ? '6' : '12' ?>">
                            <h4 class="text-primary">Winner Bracket - Rodada <?= $abertaWB['numero_rodada'] ?></h4>
                            <input type="hidden" name="id_rodada_wb" value="<?= $abertaWB['id_rodada'] ?>">
                            <?php $rodada = $abertaWB; include "_blocoPartidas.php"; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($abertaLB): ?>
                        <div class="col-md-6 border-start border-secondary">
                            <h4 class="text-danger">Loser Bracket - Rodada <?= $abertaLB['numero_rodada'] ?></h4>
                            <input type="hidden" name="id_rodada_lb" value="<?= $abertaLB['id_rodada'] ?>">
                            <?php $rodada = $abertaLB; include "_blocoPartidas.php"; ?>
                        </div>
                    <?php endif; ?>
                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-success btn-lg px-5 shadow">Salvar e Avançar</button>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <h4>🏆 Torneio Finalizado!</h4>
            </div>
            <div class="table-responsive mt-4">
                <table class="table table-dark table-striped border-primary">
                    <thead class="table-primary text-dark">
                        <tr><th>Posição</th><th>Jogador</th><th>Vitórias</th><th>Derrotas</th></tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($classificacao)): ?>
                            <?php foreach ($classificacao as $pos => $player): ?>
                                <tr>
                                    <td><?= $pos + 1 ?>º</td>
                                    <td><?= htmlspecialchars($player['nome']) ?></td>
                                    <td class="text-success"><?= $player['vitorias'] ?></td>
                                    <td class="text-danger"><?= $player['derrotas'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

<?php endif; ?>
