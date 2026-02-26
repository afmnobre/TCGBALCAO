<!DOCTYPE html>
<html>
<head>
    <title>Classificação Parcial - <?= htmlspecialchars($dadosTorneio['nome_torneio']) ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background-color: #fff; color: #000; }
        .header { margin-bottom: 20px; padding-top: 20px; }
        .header img { max-height: 80px; }
        table { margin: 0 auto; border-collapse: collapse; width: 90%; }
        th, td { border: 1px solid #000; padding: 10px; font-size: 14px; }
        th { background-color: #f2f2f2; }
        .rodada-titulo { font-size: 24px; font-weight: bold; text-transform: uppercase; margin-bottom: 5px; }
    </style>
</head>
<body>
<div class="header">
    <?php if (!empty($loja['logo'])): ?>
        <img src="/storage/uploads/lojas/<?= $loja['id_loja'] ?>/<?= htmlspecialchars($loja['logo']) ?>" alt="Logo"><br>
    <?php endif; ?>
    <strong><?= htmlspecialchars($loja['nome_loja']) ?></strong>
</div>

<div class="rodada-titulo">
    <?= $numero_rodada_texto ?> - <?= htmlspecialchars($dadosTorneio['nome_torneio']) ?>
</div>
<p>Classificação atualizada após os resultados da rodada.</p>

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
        <?php $pos = 1; foreach ($classificacao as $r): ?>
            <tr>
                <td><?= $pos++ ?>º</td>
                <td><?= htmlspecialchars($r['nome']) ?></td>
                <td><?= $r['vitorias'] ?></td>
                <td><?= $r['derrotas'] ?></td>
                <td><?= $r['empates'] ?></td>
                <td><?= $r['bye'] ?></td>
                <td><strong><?= $r['pontos'] ?></strong></td>
                <td><?= $r['forca_oponentes'] ?></td>
                <td><?= $r['vitorias_2x0'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
