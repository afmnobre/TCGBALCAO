<h2 class="mb-3">Gerenciar Torneio - <?= htmlspecialchars($torneio['nome_torneio']) ?></h2>
<?php
    $numJogadores = count($torneioModel->listarParticipantes($torneio['id_torneio']));
    $maxRodadas = ceil(log($numJogadores, 2));
?>
<p>
    <strong>Cardgame:</strong> <?= htmlspecialchars($torneio['cardgame_nome']) ?> |
    <strong>Tipo:</strong> <?= htmlspecialchars($torneio['tipo_legivel'] ?? '') ?><br>
    <strong>Participantes:</strong> <?= $numJogadores ?><br>
    <strong>Total de Rodadas:</strong> <?= $maxRodadas ?>
</p>

<!-- Timer da rodada -->
<?php if (!empty($torneio['tempo_rodada'])): ?>
    <div style="margin-bottom:15px;">
        <div id="timer" style="font-size:1.2em; font-weight:bold; color:#d9534f;">
            Tempo restante: <span id="time"></span>
        </div>
        <button id="startBtn" class="btn btn-success btn-sm">Iniciar</button>
        <button id="pauseBtn" class="btn btn-warning btn-sm">Pausar</button>
    </div>

    <script>
        var minutes = <?= (int)$torneio['tempo_rodada'] ?>;
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
                seconds--;
                updateTimerDisplay();
            }, 1000);
        }

        function pauseTimer() {
            clearInterval(interval);
            interval = null;
        }

        document.getElementById('startBtn').addEventListener('click', startTimer);
        document.getElementById('pauseBtn').addEventListener('click', pauseTimer);

        updateTimerDisplay();
    </script>
<?php endif; ?>

<!-- Lista de rodadas -->
<?php
$temRodadaComCombo = false;
foreach ($rodadas as $rodada): ?>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div><p><hr>
                <strong>Rodada <?= $rodada['numero_rodada'] ?></strong>
                <span class="badge bg-<?= $rodada['status'] == 'em_andamento' ? 'success' : ($rodada['status'] == 'finalizada' ? 'secondary' : 'warning') ?>">
                    <?= ucfirst($rodada['status']) ?>
                </span>
            </div>
<div>
    <table style="width:auto; border-collapse:collapse;">
        <tr>
            <!-- Botões de popup (na tela do sistema) -->
            <td style="padding:5px;">
                <button class="btn btn-sm btn-info"
                        onclick="document.getElementById('pareamentoRodada<?= $rodada['numero_rodada'] ?>').style.display='block'">
                    👁️ Ver Pareamento
                </button>
            </td>
            <td style="padding:5px;">
                <?php if ($rodada['status'] === 'finalizada'): ?>
                    <button class="btn btn-sm btn-success"
                            onclick="document.getElementById('pontuacaoRodada<?= $rodada['numero_rodada'] ?>').style.display='block'">
                        👁️ Ver Pontuação
                    </button>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <!-- Botões que abrem nova aba (transmissão) -->
            <td style="padding:5px;">
                <button class="btn btn-sm btn-info"
                        onclick="window.open('/torneio/verPareamento/<?= $torneio['id_torneio'] ?>/<?= $rodada['numero_rodada'] ?>','_blank')">
                    📡 Transmitir Pareamento
                </button>
            </td>
            <td style="padding:5px;">
                <?php if ($rodada['status'] === 'finalizada'): ?>
                    <button class="btn btn-sm btn-success"
                            onclick="window.open('/torneio/verPontuacao/<?= $torneio['id_torneio'] ?>/<?= $rodada['numero_rodada'] ?>?tipo_torneio=<?= $torneio['tipo_torneio'] ?>','_blank')">
                        📡 Transmitir Pontuação
                    </button>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</div>

        </div>
        <div class="card-body">
            <?php $pareamentos = $torneioModel->listarPareamentos($torneio['id_torneio'], $rodada['numero_rodada']); ?>
            <?php if (!empty($pareamentos)): ?>
                <?php if ($rodada['status'] == 'finalizada'): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Jogador 1</th>
                                <th>Jogador 2</th>
                                <th>Resultado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pareamentos as $partida): ?>
                                <tr>
                                    <td><?= htmlspecialchars($partida['jogador1']) ?></td>
                                    <td><?= htmlspecialchars($partida['jogador2']) ?></td>
                                    <td>
                                        <?php
                                        switch ($partida['resultado']) {
                                            case 'jogador1_2x0': echo "Vitória " . htmlspecialchars($partida['jogador1']) . " (2x0)"; break;
                                            case 'jogador2_2x0': echo "Vitória " . htmlspecialchars($partida['jogador2']) . " (2x0)"; break;
                                            case 'jogador1_2x1': echo "Vitória " . htmlspecialchars($partida['jogador1']) . " (2x1)"; break;
                                            case 'jogador2_2x1': echo "Vitória " . htmlspecialchars($partida['jogador2']) . " (2x1)"; break;
                                            case 'jogador1_vitoria': echo "Vitória " . htmlspecialchars($partida['jogador1']); break;
                                            case 'jogador2_vitoria': echo "Vitória " . htmlspecialchars($partida['jogador2']); break;
                                            case 'empate': echo "Empate"; break;
                                            default: echo htmlspecialchars($partida['resultado']);
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php elseif (!$temRodadaComCombo): ?>
                    <form action="/torneio/salvarResultado/<?= $torneio['id_torneio'] ?>/<?= $rodada['id_rodada'] ?>" method="POST">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Jogador 1</th>
                                    <th>Jogador 2</th>
                                    <th>Resultado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pareamentos as $partida): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($partida['jogador1']) ?></td>
                                        <td><?= htmlspecialchars($partida['jogador2']) ?></td>
                                        <td>
                                            <select name="resultados[<?= $partida['id_partida'] ?>]" class="form-select" required>
                                                <option value="">Selecione...</option>
                                                <?php if (strpos($torneio['tipo_torneio'], 'bo3') !== false): ?>
                                                    <option value="jogador1_2x0">Vitória <?= htmlspecialchars($partida['jogador1']) ?> (2x0)</option>
                                                    <option value="jogador2_2x0">Vitória <?= htmlspecialchars($partida['jogador2']) ?> (2x0)</option>
                                                    <option value="jogador1_2x1">Vitória <?= htmlspecialchars($partida['jogador1']) ?> (2x1)</option>
                                                    <option value="jogador2_2x1">Vitória <?= htmlspecialchars($partida['jogador2']) ?> (2x1)</option>
                                                    <option value="empate">Empate</option>
                                                <?php else: ?>
                                                    <option value="jogador1_vitoria">Vitória <?= htmlspecialchars($partida['jogador1']) ?></option>
                                                    <option value="jogador2_vitoria">Vitória <?= htmlspecialchars($partida['jogador2']) ?></option>
                                                    <option value="empate">Empate</option>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">Salvar Resultados</button>
                    </form>
                    <?php $temRodadaComCombo = true; ?>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-info">Nenhum pareamento gerado para esta rodada.</div>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
<?php foreach ($rodadas as $rodada): ?>
    <!-- Popup Pareamento -->
    <div id="pareamentoRodada<?= $rodada['numero_rodada'] ?>" class="popup-variado">
        <div class="popup-content">
            <h3>Pareamentos - Rodada <?= $rodada['numero_rodada'] ?></h3>
            <?php $pareamentos = $torneioModel->listarPareamentos($torneio['id_torneio'], $rodada['numero_rodada']); ?>
            <?php if (!empty($pareamentos)): ?>
                <table class="table table-bordered">
                    <thead><tr><th>Jogador 1</th><th>Jogador 2</th></tr></thead>
                    <tbody>
                        <?php foreach ($pareamentos as $partida): ?>
                            <tr>
                                <td><?= htmlspecialchars($partida['jogador1']) ?></td>
                                <td><?= htmlspecialchars($partida['jogador2']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info">Nenhum pareamento disponível.</div>
            <?php endif; ?>
            <br>
            <button onclick="document.getElementById('pareamentoRodada<?= $rodada['numero_rodada'] ?>').style.display='none'">Fechar</button>
        </div>
    </div>

    <!-- Popup Pontuação -->
    <?php if ($rodada['status'] === 'finalizada'): ?>
        <div id="pontuacaoRodada<?= $rodada['numero_rodada'] ?>" class="popup-variado">
            <div class="popup-content">
                <h3>Pontuação - Rodada <?= $rodada['numero_rodada'] ?></h3>
                <?php
if (str_starts_with($torneio['tipo_torneio'], 'suico')) {
    $classificacaoRodada = $torneioModel->classificacaoSuicoParcial($torneio['id_torneio'], $rodada['numero_rodada']);
} elseif (str_starts_with($torneio['tipo_torneio'], 'elim_dupla')) {
    $classificacaoRodada = $torneioModel->classificacaoElimDuplaParcial($torneio['id_torneio'], $rodada['numero_rodada']);
} else {
    $classificacaoRodada = [];
}

                ?>
                <?php if (!empty($classificacaoRodada)): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Posição</th>
                                <th>Jogador</th>
                                <?php if (str_starts_with($torneio['tipo_torneio'], 'suico')): ?>
                                    <th>Pontos</th>
                                    <th>Força dos Oponentes</th>
                                <?php else: ?>
                                    <th>Vitórias</th>
                                    <th>Derrotas</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $posicao = 1; ?>
                            <?php foreach ($classificacaoRodada as $linha): ?>
                                <tr>
                                    <td><?= $posicao++ ?></td>
                                    <td><?= htmlspecialchars($linha['nome']) ?></td>
                                    <?php if (str_starts_with($torneio['tipo_torneio'], 'suico')): ?>
                                        <td><?= $linha['pontos'] ?></td>
                                        <td><?= $linha['forca_oponentes'] ?></td>
                                    <?php else: ?>
                                        <td><?= $linha['vitorias'] ?></td>
                                        <td><?= $linha['derrotas'] ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info">Não foi possível calcular a pontuação desta rodada.</div>
                <?php endif; ?>
                <br>
                <button onclick="document.getElementById('pontuacaoRodada<?= $rodada['numero_rodada'] ?>').style.display='none'">Fechar</button>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>

<?php
// Mostrar classificação final se todas as rodadas estiverem finalizadas
$todasFinalizadas = true;
foreach ($rodadas as $rodada) {
    if ($rodada['status'] !== 'finalizada') {
        $todasFinalizadas = false;
        break;
    }
}

if ($todasFinalizadas):
    if (str_starts_with($torneio['tipo_torneio'], 'suico')) {
        $classificacao = $torneioModel->classificacaoSuico($torneio['id_torneio']);
    } elseif (str_starts_with($torneio['tipo_torneio'], 'elim_dupla')) {
        $classificacao = $torneioModel->classificacaoElimDupla($torneio['id_torneio']);
    } else {
        $classificacao = [];
    }
?>
    <div class="card mt-4">
        <div class="card-header bg-dark text-white">
            <p><hr>
            <strong>Classificação Final</strong>
        </div>
        <div class="card-body">
            <?php if (!empty($classificacao)): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Posição</th>
                            <th>Jogador</th>
                            <?php if (str_starts_with($torneio['tipo_torneio'], 'suico')): ?>
                                <th>Pontos</th>
                                <th>Força dos Oponentes</th>
                            <?php else: ?>
                                <th>Vitórias</th>
                                <th>Derrotas</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $posicao = 1; ?>
                        <?php foreach ($classificacao as $linha): ?>
                            <tr>
                                <td><?= $posicao ?></td>
                                <td>
                                    <?= htmlspecialchars($linha['nome']) ?>
                                    <?php if ($posicao === 1): ?>
                                        <span class="badge bg-warning text-dark">🏆 Campeão</span>
                                    <?php endif; ?>
                                </td>
                                <?php if (str_starts_with($torneio['tipo_torneio'], 'suico')): ?>
                                    <td><?= $linha['pontos'] ?></td>
                                    <td><?= $linha['forca_oponentes'] ?></td>
                                <?php else: ?>
                                    <td><?= $linha['vitorias'] ?></td>
                                    <td><?= $linha['derrotas'] ?></td>
                                <?php endif; ?>
                            </tr>
                            <?php $posicao++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info">Não foi possível calcular a classificação final.</div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<br><br><br>


