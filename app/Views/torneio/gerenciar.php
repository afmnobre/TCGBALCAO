<h2 class="mb-3">Gerenciar Torneio - <?= htmlspecialchars($torneio['nome_torneio']) ?></h2>
<?php
    $numJogadores = count($torneioModel->listarParticipantes($torneio['id_torneio']));
    $maxRodadas = ceil(log($numJogadores, 2));
?>
<p>
    <strong>Cardgame:</strong> <?= htmlspecialchars($torneio['cardgame']) ?>|
    <strong>Tipo:</strong> <?= htmlspecialchars($torneio['tipo_legivel'] ?? '') ?><br>
    <strong>Participantes:</strong> <?= $numJogadores ?><br>
    <strong>Total de Rodadas:</strong> <?= $maxRodadas ?>
</p>

<?php if (str_starts_with($torneio['tipo_torneio'], 'suico')): ?>
    <button class="btn btn-sm btn-info" onclick="document.getElementById('popupRegrasSuico').style.display='block'">
        📖 Regras do Torneio Suíço
    </button>
<?php endif; ?><hr>



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
        <td><?= htmlspecialchars($partida['jogador1'] ?? '') ?></td>
        <td><?= $partida['jogador2'] ? htmlspecialchars($partida['jogador2']) : 'BY' ?></td>
        <td>
            <?php if (empty($partida['jogador2'])): ?>
                Vitória <?= htmlspecialchars($partida['jogador1']) ?> (2x1 - BYE)
            <?php else: ?>
                <?php
                switch ($partida['resultado']) {
                    case 'jogador1_2x0':
                        echo "Vitória " . htmlspecialchars($partida['jogador1']) . " (2x0)";
                        break;
                    case 'jogador2_2x0':
                        echo "Vitória " . htmlspecialchars($partida['jogador2']) . " (2x0)";
                        break;
                    case 'jogador1_2x1':
                        echo "Vitória " . htmlspecialchars($partida['jogador1']) . " (2x1)";
                        break;
                    case 'jogador2_2x1':
                        echo "Vitória " . htmlspecialchars($partida['jogador2']) . " (2x1)";
                        break;
                    case 'jogador1_vitoria':
                        echo "Vitória " . htmlspecialchars($partida['jogador1']);
                        break;
                    case 'jogador2_vitoria':
                        echo "Vitória " . htmlspecialchars($partida['jogador2']);
                        break;
                    case 'empate':
                        echo "Empate";
                        break;
                    default:
                        echo "Resultado não disponível";
                }
                ?>
            <?php endif; ?>
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
        <td><?= htmlspecialchars($partida['jogador1'] ?? '') ?></td>
        <td><?= $partida['jogador2'] ? htmlspecialchars($partida['jogador2']) : 'BY' ?></td>
        <td>
            <?php if (empty($partida['jogador2'])): ?>
                <!-- BYE: vitória automática do jogador1 (2x1), travado -->
                <select class="form-select" disabled>
                    <option selected>Vitória <?= htmlspecialchars($partida['jogador1']) ?> (2x1)</option>
                </select>
            <?php else: ?>
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
                                <td><?= htmlspecialchars($partida['jogador2'] ?? 'BY') ?></td>
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

<!-- Botão para ver o resultado do Torneio Suiço, só aparece se for torneio suiço. -->
<?php if (str_starts_with($torneio['tipo_torneio'], 'suico')): ?>
    <p><hr>
    <button class="btn btn-sm btn-primary"
            onclick="window.open('/torneio/verResultadoSuico/<?= $torneio['id_torneio'] ?>','_blank')">
        🏆 Transmitir Resultado Final
    </button>
<?php endif; ?>





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
    <p><hr>
    <div class="card-header bg-dark text-white">
        <strong>Classificação Final</strong>
    </div>
    <div class="card-body">
        <?php if (!empty($classificacao)): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Posição</th>
                        <th>Jogador</th>
                        <th>Vitórias</th>
                        <th>Derrotas</th>
                        <th>Empates</th>
                        <th>BYE</th>
                        <th>Pontos</th>
                        <th>Força dos Oponentes</th>
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
                            <td><?= $linha['vitorias'] ?? 0 ?></td>
                            <td><?= $linha['derrotas'] ?? 0 ?></td>
                            <td><?= $linha['empates'] ?? 0 ?></td>
                            <td><?= $linha['bye'] ?? 0 ?></td>
                            <td><?= $linha['pontos'] ?></td>
                            <td><?= $linha['forca_oponentes'] ?></td>
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

<!-- Popup oculto -->
<div id="popupRegrasSuico" class="popup" style="display:none;">
    <div class="popup-content">
        <span class="fechar" onclick="document.getElementById('popupRegrasSuico').style.display='none'">&times;</span>
        <h3>Regras do Torneio Suíço</h3>

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
    <br>

</div>

<br><br><br>


