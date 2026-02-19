<!DOCTYPE html>
<html>
<head>
    <title>Pontuação Rodada <?= $numero_rodada ?></title>
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
        <strong><?= htmlspecialchars($loja['nome_loja']) ?></strong>
    </div>

    <h3>Pontuação - Rodada <?= $numero_rodada ?></h3>
    <?php if (!empty($classificacao)): ?>
        <table>
            <thead>
                <tr>
                    <th>Posição</th>
                    <th>Jogador</th>
                    <?php if (str_starts_with($_GET['tipo_torneio'], 'suico')): ?>
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
                        <td><?= $posicao++ ?></td>
                        <td><?= htmlspecialchars($linha['nome']) ?></td>
                        <?php if (str_starts_with($_GET['tipo_torneio'], 'suico')): ?>
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
        <p>Não foi possível calcular a pontuação desta rodada.</p>
    <?php endif; ?>
</body>
</html>

