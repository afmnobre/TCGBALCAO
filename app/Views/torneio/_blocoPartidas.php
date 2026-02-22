<?php
// Busca os pareamentos baseado no tipo de chave (WB ou LB)
if ($rodada['tipo_chave'] === 'WB') {
    $pareamentos = $torneioModel->listarPareamentoTorneioEliminacaoDuplaWB($torneio['id_torneio'], $rodada['numero_rodada']);
} else {
    $pareamentos = $torneioModel->listarPareamentoTorneioEliminacaoDuplaLB($torneio['id_torneio'], $rodada['numero_rodada']);
}
?>

<?php if (!empty($pareamentos)): ?>
<div class="table-responsive">
    <table class="table table-dark table-sm table-hover border-secondary align-middle">
        <thead class="table-secondary text-dark">
            <tr>
                <th style="width: 35%;">Jogador 1</th>
                <th style="width: 30%; text-align: center;">Resultado</th>
                <th style="width: 35%; text-align: right;">Jogador 2</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pareamentos as $partida): ?>
            <tr>
                <td><span class="fw-bold"><?= htmlspecialchars($partida["jogador1"] ?? "A definir") ?></span></td>

                <td class="text-center">
                    <?php if ($rodada["status"] === "finalizada"): ?>
                        <span class="text-info"><?= $partida["resultado"] ?? 'Finalizado' ?></span>
                    <?php else: ?>
                        <?php
                        // AQUI ESTAVA O ERRO: Vamos checar se o NOME do jogador 2 é BYE ou vazio
                        $isBye = (empty($partida["jogador2"]) || strtoupper($partida["jogador2"]) === 'BYE');

                        if ($isBye): ?>
                            <input type="hidden" name="resultados[<?= $partida["id_partida"] ?>]" value="jogador1_vitoria">
                            <span class="badge bg-success w-100">VITÓRIA AUTOMÁTICA (BYE)</span>
                        <?php else: ?>
                            <select name="resultados[<?= $partida["id_partida"] ?>]" class="form-select form-select-sm bg-dark text-light border-secondary" required>
                                <option value="">Selecione...</option>
                                <?php if (str_contains($torneio["tipo_torneio"], "bo3")): ?>
                                    <option value="jogador1_2x0">2x0 (<?= htmlspecialchars($partida["jogador1"]) ?>)</option>
                                    <option value="jogador1_2x1">2x1 (<?= htmlspecialchars($partida["jogador1"]) ?>)</option>
                                    <option value="jogador2_2x0">2x0 (<?= htmlspecialchars($partida["jogador2"]) ?>)</option>
                                    <option value="jogador2_2x1">2x1 (<?= htmlspecialchars($partida["jogador2"]) ?>)</option>
                                <?php else: ?>
                                    <option value="jogador1_vitoria">Vence: <?= htmlspecialchars($partida["jogador1"]) ?></option>
                                    <option value="jogador2_vitoria">Vence: <?= htmlspecialchars($partida["jogador2"]) ?></option>
                                <?php endif; ?>
                            </select>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>

                <td class="text-end"><span class="fw-bold"><?= !empty($partida["jogador2"]) ? htmlspecialchars($partida["jogador2"]) : "BYE" ?></span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>


