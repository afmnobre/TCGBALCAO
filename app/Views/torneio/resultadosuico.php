<?php
function formatarDataHora($valor) {
    if (empty($valor)) return 'N/D';
    $dt = new DateTime($valor);
    return $dt->format('d/m/Y H:i');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Resultado Final - <?= htmlspecialchars($dadosTorneio['nome_torneio'] ?? 'Torneio') ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .header { margin-bottom: 20px; }
        .header img { max-height: 80px; }
        table { margin: 0 auto; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px 12px; }
    </style>
</head>
<body>
<div class="header">
    <?php if (!empty($loja['logo'])): ?>
        <img src="/storage/uploads/<?= $loja['id_loja'] ?>/<?= htmlspecialchars($loja['logo']) ?>" alt="Logo da Loja"><br>
    <?php endif; ?>
    <strong><?= htmlspecialchars($loja['nome_loja']) ?></strong><br>
</div>

<h2>Resultado Final - <?= htmlspecialchars($dadosTorneio['nome_torneio'] ?? 'Torneio') ?></h2>

<p>
    <strong>Rodadas: </strong> <?= htmlspecialchars($maxRodadas) ?><br>
    <strong>Criado em:</strong> <?= formatarDataHora($dadosTorneio['data_criacao'] ?? null) ?>
</p>


<?php if (!empty($classificacaoFinal)): ?>
    <table>
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
                <th>Vitórias 2x0</th>
            </tr>
        </thead>
        <tbody>
            <?php $posicao = 1; ?>
            <?php foreach ($classificacaoFinal as $linha): ?>
                <tr>
                    <td><?= $posicao++ ?></td>
                    <td><?= $linha['nome'] !== null ? htmlspecialchars($linha['nome']) : 'BYE' ?></td>
                    <td><?= $linha['vitorias'] ?></td>
                    <td><?= $linha['derrotas'] ?></td>
                    <td><?= $linha['empates'] ?></td>
                    <td><?= $linha['bye'] ?></td>
                    <td><?= $linha['pontos'] ?></td>
                    <td><?= $linha['forca_oponentes'] ?></td>
                    <td><?= $linha['vitorias_2x0'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Nenhum resultado disponível.</p>
<?php endif; ?>

</body>
</html>







