<!DOCTYPE html>
<html>
<head>
    <title>Pareamento Rodada <?= $numero_rodada ?></title>
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
        <img src="/storage/uploads/lojas/<?= $loja['id_loja'] ?>/<?= htmlspecialchars($loja['logo']) ?>" alt="Logo da Loja"><br>
    <?php endif; ?>
    <strong><?= htmlspecialchars($loja['nome_loja']) ?></strong><br>
</div>


    <h3>Pareamentos - Rodada <?= $numero_rodada ?></h3>
    <?php if (!empty($pareamentos)): ?>
        <table>
            <thead><tr><th>Jogador 1</th><th>Jogador 2</th></tr></thead>
    <tbody>
        <?php foreach ($pareamentos as $partida): ?>
            <tr>
                <td><?= $partida['jogador1'] !== null ? htmlspecialchars($partida['jogador1']) : 'BYE' ?></td>
                <td><?= $partida['jogador2'] !== null ? htmlspecialchars($partida['jogador2']) : 'BYE' ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum pareamento disponível.</p>
    <?php endif; ?>
</body>
</html>


