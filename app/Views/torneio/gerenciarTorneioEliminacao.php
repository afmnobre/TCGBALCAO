<?php
// No topo do arquivo gerenciarTorneioEliminacao.php
$numRodadaAtiva = $rodadaAtual['numero_rodada'] ?? 1;
$tipoChaveAtiva = $rodadaAtual['tipo_chave'] ?? 'WB';

$pendentes = 0;
$estrutura = [];

if (!empty($partidas)) {
    foreach ($partidas as $p) {
        $r = $p['numero_rodada'];
        $tipo = $p['tipo_chave'] ?? 'WB';
        $estrutura[$r][$tipo][] = $p;

        // Lógica de pendentes: Só conta se for da rodada e chave que estão ATIVAS
        if ($r == $numRodadaAtiva && $tipo == $tipoChaveAtiva) {
            $j1 = (int)($p['id_jogador1'] ?? 0);
            $j2 = (int)($p['id_jogador2'] ?? 0);
            $resultado = trim($p['resultado'] ?? '');

            // Partida pendente se tiver 2 jogadores reais e nenhum resultado
            if ($j1 > 0 && $j2 > 0 && empty($resultado)) {
                $pendentes++;
            }
        }
    }
}
?>

<div class="bg-dark text-warning p-2 small border-bottom border-warning">
    <strong>DEBUG:</strong> Rodada Ativa no Banco: <?= $numRodadaAtiva ?> (<?= $tipoChaveAtiva ?>) | Total de Partidas Aguardando Resultado: <?= $pendentes ?>
</div>

<div class="container-fluid bg-black text-white py-4" style="min-height: 100vh;">

    <div class="d-flex justify-content-between align-items-center px-4 mb-5">
        <div>
            <h2 class="mb-0 text-uppercase fw-bold" style="letter-spacing: 2px;">
                <?= htmlspecialchars($torneio['nome_torneio'] ?? 'Torneio') ?>
            </h2>
            <span class="badge bg-warning text-dark">
                <?= ($tipoChaveAtiva == 'LB' ? 'REPESCAGEM (LOSERS)' : 'CHAVE PRINCIPAL (WINNERS)') ?> - RODADA <?= $numRodadaAtiva ?>
            </span>
        </div>

        <?php if ((int)$pendentes === 0): ?>
            <form action="/TorneioEliminacao/avancarRodada" method="POST">
                <input type="hidden" name="id_torneio" value="<?= $id_torneio ?>">
                <input type="hidden" name="rodada_atual" value="<?= $numRodadaAtiva ?>">
                <input type="hidden" name="tipo_chave" value="<?= $tipoChaveAtiva ?>">
                <button type="submit" class="btn btn-success btn-lg fw-bold shadow-lg" style="border: 2px solid #fff;">
                    <i class="fas fa-arrow-right me-2"></i> FINALIZAR RODADA <?= $numRodadaAtiva ?>
                </button>
            </form>
        <?php endif; ?>
    </div>

    <div class="px-4 mb-2">
        <h5 class="text-success fw-bold"><i class="fas fa-trophy me-2"></i>WINNERS BRACKET</h5>
    </div>
    <div class="bracket-container mb-5 d-flex align-items-start" style="overflow-x: auto; padding-bottom: 20px;">
        <?php
        $rodadasWB = [];
        foreach($estrutura as $r => $tipos) if(isset($tipos['WB'])) $rodadasWB[] = $r;
        sort($rodadasWB);

        foreach ($rodadasWB as $i): ?>
            <div class="bracket-column">
                <div class="bracket-title">Rodada <?= $i ?></div>
                <?php if(isset($estrutura[$i]['WB'])) foreach ($estrutura[$i]['WB'] as $p) {
                    echo renderChallongeGame($p, $id_torneio);
                } ?>
            </div>
        <?php endforeach; ?>

        <?php
        $rodadaFinalKey = null;
        foreach($estrutura as $r => $tipos) if(isset($tipos['GF'])) $rodadaFinalKey = $r;
        if ($rodadaFinalKey): ?>
            <div class="bracket-column">
                <div class="bracket-title text-warning fw-bold">GRANDE FINAL</div>
                <?php foreach ($estrutura[$rodadaFinalKey]['GF'] as $p) {
                    echo renderChallongeGame($p, $id_torneio);
                } ?>
            </div>
        <?php endif; ?>
    </div>

    <hr class="border-secondary my-5">

    <div class="px-4 mb-2">
        <h5 class="text-danger fw-bold"><i class="fas fa-skull-crossbones me-2"></i>LOSERS BRACKET</h5>
    </div>
    <div class="bracket-container d-flex align-items-start" style="overflow-x: auto; padding-bottom: 20px;">
        <?php
        $rodadasLB = [];
        foreach($estrutura as $r => $tipos) if(isset($tipos['LB'])) $rodadasLB[] = $r;
        sort($rodadasLB);

        foreach ($rodadasLB as $i): ?>
            <div class="bracket-column">
                <div class="bracket-title">Rodada L<?= $i ?></div>
                <?php if(isset($estrutura[$i]['LB'])) foreach ($estrutura[$i]['LB'] as $p) {
                    echo renderChallongeGame($p, $id_torneio);
                } ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
/**
 * Função renderizadora de cada partida com CORREÇÃO DE BOTÕES
 */
function renderChallongeGame($p, $idT) {
    $resultado = $p['resultado'] ?? '';
    $vencida = !empty($resultado);
    $statusRodada = $p['status'] ?? '';
    $abertaParaEdicao = ($statusRodada === 'A' || $statusRodada === 'em_andamento');

    $j1_venceu = (strpos($resultado, 'jogador1') !== false || $resultado === 'BYE');
    $j2_venceu = (strpos($resultado, 'jogador2') !== false);

    $class_locked = ($abertaParaEdicao || $vencida) ? '' : 'locked';
    $erroDuplicidade = (!empty($p['id_jogador1']) && !empty($p['id_jogador2']) && $p['id_jogador1'] === $p['id_jogador2']);

    ob_start(); ?>
    <div class="bracket-game <?= $class_locked ?> <?= $erroDuplicidade ? 'border border-danger' : '' ?>" style="position:relative;">
        <small style="position:absolute; top:-12px; right:5px; font-size:9px; color:#666;">ID: #<?= $p['id_partida'] ?></small>

        <div class="player-slot <?= $j1_venceu ? 'winner' : '' ?>">
            <span class="player-name"><?= htmlspecialchars($p['nome_j1'] ?? 'TBD') ?></span>
            <span class="player-score"><?= $j1_venceu ? '✓' : '' ?></span>
        </div>

        <div class="player-slot <?= $j2_venceu ? 'winner' : '' ?>">
            <span class="player-name">
                <?php if (!empty($p['id_jogador2'])): ?>
                    <?= htmlspecialchars($p['nome_j2']) ?>
                <?php else: ?>
                    <span class="text-muted italic"><?= ($p['numero_rodada'] == 1 && $p['tipo_chave'] == 'WB') ? 'BYE' : 'TBD' ?></span>
                <?php endif; ?>
            </span>
            <span class="player-score"><?= $j2_venceu ? '✓' : '' ?></span>
        </div>

        <?php
        // CORREÇÃO AQUI: Exibe botões individuais para permitir avançar jogadores sozinhos (ex: Felipe Santos na L4)
        if ($abertaParaEdicao && !$vencida): ?>
            <div class="p-1 bg-dark border-top border-secondary text-center">
                <form action="/TorneioEliminacao/salvarResultado" method="POST" class="d-flex gap-1">
                    <input type="hidden" name="id_partida" value="<?= $p['id_partida'] ?>">
                    <input type="hidden" name="id_torneio" value="<?= $idT ?>">

                    <?php if (!empty($p['id_jogador1'])): ?>
                        <button name="resultado" value="jogador1_2x0" class="btn btn-xs btn-success flex-fill" style="font-size: 0.6rem;">
                            V J1
                        </button>
                    <?php endif; ?>

                    <?php if (!empty($p['id_jogador2'])): ?>
                        <button name="resultado" value="jogador2_2x0" class="btn btn-xs btn-info flex-fill" style="font-size: 0.6rem;">
                            V J2
                        </button>
                    <?php endif; ?>
                </form>
            </div>
        <?php endif; ?>
    </div>
    <?php return ob_get_clean();
} ?>
