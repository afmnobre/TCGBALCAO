<link rel="stylesheet" href="/public/css/recibo.css">

<!-- Primeira via (completa) -->
<div class="recibo">
    <div class="logo" style="text-align:center;">
        <?php if (!empty($loja['logo'])): ?>
            <img src="/storage/uploads/<?= htmlspecialchars($loja['id_loja'] ?? '') ?>/<?= htmlspecialchars($loja['logo'] ?? '') ?>"
                 alt="<?= htmlspecialchars($loja['nome_loja'] ?? '') ?>">
        <?php endif; ?>
    </div>

    <h2><?= htmlspecialchars($loja['nome_loja'] ?? '') ?></h2>
    <p class="small">

        CNPJ: <?= htmlspecialchars($loja['cnpj'] ?? '') ?>
    </p>

    <hr>
    <h3>RECIBO</h3>
    <p>
        Pedido Nº <?= htmlspecialchars($pedido['id_pedido'] ?? '') ?><br>
        <?= !empty($pedido['data_pedido']) ? date('d/m/Y', strtotime($pedido['data_pedido'])) : '' ?>
    </p>

    <hr>
    <p class="status">
        <?= !empty($pedido['pedido_pago']) && $pedido['pedido_pago'] ? '✔️ PAGO' : '❌ NÃO PAGO' ?>
    </p>

    <hr>
    <p class="t-left">
        Cliente:<br>
        <strong><?= htmlspecialchars($pedido['nome'] ?? '') ?></strong>
    </p>

    <hr>
    <?php if (!empty($itens)): ?>
        <table class="recibo-itens" style="font-size:11px;">
            <tr>
                <th>Produto</th>
                <th>Qtd</th>
                <th>Valor Unitário</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach ($itens as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['nome'] ?? '') ?></td>
                    <td><?= (int)($item['quantidade'] ?? 0) ?></td>
                    <td>R$ <?= number_format($item['valor_unitario'] ?? 0, 2, ',', '.') ?></td>
                    <td>R$ <?= number_format(($item['quantidade'] ?? 0) * ($item['valor_unitario'] ?? 0), 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <?php if (!empty($pedido['valor_variado']) && $pedido['valor_variado'] > 0): ?>
        <hr>
        <p class="t-left">
            Variado:<br>
            <?= number_format($pedido['valor_variado'],2,',','.') ?>
        </p>
        <?php if (!empty($pedido['observacao_variado'])): ?>
            <p class="t-left small">
                Obs: <?= htmlspecialchars($pedido['observacao_variado'] ?? '') ?>
            </p>
        <?php endif; ?>
    <?php endif; ?>

    <hr>
    <table>
        <tr>
            <td class="t-left total">TOTAL</td>
            <td class="t-right total">
                R$ <?= number_format(array_sum(array_map(fn($i)=>(($i['quantidade'] ?? 0)*($i['valor_unitario'] ?? 0)), $itens)) + ($pedido['valor_variado'] ?? 0),2,',','.') ?>
            </td>
        </tr>
    </table>

    <hr>
    <p class="small">
        Obrigado pela preferência!<br>
        Boa sorte nos torneios 🎴
    </p>
</div>

<!-- Linha pontilhada para corte -->
<hr class="cut">

<!-- Segunda via (simplificada) -->
<div class="recibo">
    <div class="logo" style="text-align:center;">
        <?php if (!empty($loja['logo'])): ?>
            <img src="/storage/uploads/<?= htmlspecialchars($loja['id_loja'] ?? '') ?>/<?= htmlspecialchars($loja['logo'] ?? '') ?>"
                 alt="<?= htmlspecialchars($loja['nome_loja'] ?? '') ?>">
        <?php endif; ?>
    </div>

    <h2><?= htmlspecialchars($loja['nome_loja'] ?? '') ?></h2>
    <p class="small">
        Pedido Nº <?= htmlspecialchars($pedido['id_pedido'] ?? '') ?><br>
        <?= !empty($pedido['data_pedido']) ? date('d/m/Y', strtotime($pedido['data_pedido'])) : '' ?>
    </p>

    <p class="status">
        <?= !empty($pedido['pedido_pago']) && $pedido['pedido_pago'] ? '✔️ PAGO' : '❌ NÃO PAGO' ?>
    </p>

    <p class="t-left">
        Cliente:<br>
        <strong><?= htmlspecialchars($pedido['nome'] ?? '') ?></strong>
    </p>

    <p class="total">
        TOTAL: R$ <?= number_format(array_sum(array_map(fn($i)=>(($i['quantidade'] ?? 0)*($i['valor_unitario'] ?? 0)), $itens)) + ($pedido['valor_variado'] ?? 0),2,',','.') ?>
    </p>
</div>

