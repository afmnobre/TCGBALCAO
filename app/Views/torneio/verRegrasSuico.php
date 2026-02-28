<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Regras de Pontuação - Sistema Suíço</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; text-align: center; padding: 40px; background-color: #fff; color: #000; }

        .header { margin-bottom: 20px; }
        .header img { max-height: 80px; margin-bottom: 5px; }
        .nome-loja { font-size: 1.2em; font-weight: bold; text-transform: uppercase; }

        .titulo-principal {
            margin: 30px 0;
            font-size: 1.8em;
            font-weight: bold;
            border-top: 3px solid #000;
            border-bottom: 3px solid #000;
            padding: 15px 0;
            display: flex;
            /* Garante que a bandeira e o texto fiquem alinhados */
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        /* Define o tamanho fixo da bandeira para não quebrar o layout */
        .img-bandeira {
            height: 30px;
            width: auto;
            border: 1px solid #eee; /* Uma borda fina para destacar caso o fundo seja branco */
        }

        .secao { margin-bottom: 40px; text-align: left; max-width: 600px; margin-left: auto; margin-right: auto; }
        h2 { border-left: 8px solid #d52b1e; padding-left: 15px; background: #f8f8f8; padding-top: 8px; padding-bottom: 8px; font-size: 1.4em; }

        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #000; padding: 10px; text-align: left; }

        .criterios-lista { padding-left: 20px; line-height: 1.8; }

        .no-print { margin-top: 30px; border-top: 1px solid #ddd; padding-top: 20px; }
        .btn { padding: 12px 25px; cursor: pointer; border: 2px solid #000; background: #fff; font-weight: bold; font-size: 14px; text-transform: uppercase; }
        .btn-print { background: #000; color: #fff; margin-right: 10px; }

        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

<div class="header">
    <?php if (!empty($loja['logo'])): ?>
        <img src="/storage/uploads/lojas/<?= $loja['id_loja'] ?>/<?= htmlspecialchars($loja['logo']) ?>" alt="Logo"><br>
    <?php endif; ?>
    <span class="nome-loja"><?= htmlspecialchars($loja['nome_loja']) ?></span>
</div>

<div class="titulo-principal">
    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Flag_of_Switzerland.svg/512px-Flag_of_Switzerland.svg.png" class="img-bandeira" alt="Bandeira Suíça">
    <span>TORNEIO SUÍÇO</span>
    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Flag_of_Switzerland.svg/512px-Flag_of_Switzerland.svg.png" class="img-bandeira" alt="Bandeira Suíça">
</div>

<div class="secao">
    <h2>Regras Melhor de 3 (MD3)</h2>
    <table>
        <tr><td>Vitória / BYE</td><td><strong>3 Pontos</strong></td></tr>
        <tr><td>Empate</td><td><strong>1 Ponto</strong></td></tr>
        <tr><td>Derrota</td><td><strong>0 Pontos</strong></td></tr>
    </table>
    <strong>Critérios de Desempate:</strong>
    <ul class="criterios-lista">
        <li><strong>1º Força do oponente (Buchholz):</strong> Soma da pontuação de todos os adversários enfrentados.</li>
        <li><strong>2º Performance de Sets:</strong> Jogadores que venceram por 2x0 têm vantagem sobre vitórias por 2x1.</li>
    </ul>
</div>

<div class="secao">
    <h2>Regras Melhor de 1 (MD1)</h2>
    <table>
        <tr><td>Vitória / BYE</td><td><strong>3 Pontos</strong></td></tr>
        <tr><td>Empate</td><td><strong>1 Ponto</strong></td></tr>
        <tr><td>Derrota</td><td><strong>0 Pontos</strong></td></tr>
    </table>
    <strong>Critérios de Desempate:</strong>
    <ul class="criterios-lista">
        <li><strong>1º Força do oponente (Buchholz):</strong> Soma da pontuação de todos os adversários enfrentados.</li>
    </ul>
</div>

<div class="no-print">
    <button class="btn btn-print" onclick="window.print()">Imprimir Regras</button>
    <button class="btn" onclick="window.close()">Fechar Janela</button>
</div>

</body>
</html>
