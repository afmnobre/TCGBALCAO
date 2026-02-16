<link rel="stylesheet" href="/public/css/recibo.css">

<div class="logo">
    <img src="<?= htmlspecialchars($loja['logo'] ?? '') ?>" alt="<?= htmlspecialchars($loja['nome_loja'] ?? '') ?>">
</div>

<h2><?= htmlspecialchars($loja['nome_loja'] ?? '') ?></h2>
<p class="small">
Endereço: <?= htmlspecialchars($loja['endereco'] ?? '') ?><br>
CNPJ: <?= htmlspecialchars($loja['cnpj'] ?? '') ?>
</p>

<hr>
<h3>RECIBO</h3>
<p>
Pedido Nº <?= $pedido['id_pedido'] ?><br>
<?= date('d/m/Y', strtotime($pedido['data_pedido'])) ?>
</p>


<div class="logo">
    <img src="<?= htmlspecialchars($loja['logo'] ?? '') ?>" alt="<?= htmlspecialchars($loja['nome_loja'] ?? '') ?>">
</div>

<h2><?= htmlspecialchars($loja['nome_loja'] ?? '') ?></h2>
<p class="small">
Endereço: <?= htmlspecialchars($loja['endereco'] ?? '') ?><br>
CNPJ: <?= htmlspecialchars($loja['cnpj'] ?? '') ?>
</p>


<hr>
<h3>RECIBO</h3>
<p>
Pedido Nº <?= $pedido['id_pedido'] ?><br>
<?= date('d/m/Y', strtotime($pedido['data_pedido'])) ?>
</p>

<hr>
<p class="status">
<?= $pedido['pedido_pago'] ? '✔️ PAGO' : '❌ NÃO PAGO' ?>
</p>

<hr>
<p class="t-left">
Cliente:<br>
<strong><?= htmlspecialchars($pedido['nome']) ?></strong>
</p>

<hr>
<?php if (!empty($itens)): ?>
    <table>
        <tr>
            <th>Produto</th>
            <th>Qtd</th>
            <th>Valor Unitário</th>
            <th>Subtotal</th>
        </tr>
        <?php foreach ($itens as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['nome']) ?></td>
                <td><?= (int)$item['quantidade'] ?></td>
                <td>R$ <?= number_format($item['valor_unitario'], 2, ',', '.') ?></td>
                <td>R$ <?= number_format($item['quantidade'] * $item['valor_unitario'], 2, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>


<?php if ($pedido['valor_variado'] > 0): ?>
<hr>
<p class="t-left">
Variado:<br>
<?= number_format($pedido['valor_variado'],2,',','.') ?>
</p>
<?php if (!empty($pedido['observacao_variado'])): ?>
<p class="t-left small">
Obs: <?= htmlspecialchars($pedido['observacao_variado']) ?>
</p>
<?php endif; ?>
<?php endif; ?>

<hr>
<table>
<tr>
<td class="t-left total">TOTAL</td>
<td class="t-right total">
R$ <?= number_format(array_sum(array_map(fn($i)=>$i['quantidade']*$i['valor_unitario'],$itens)) + $pedido['valor_variado'],2,',','.') ?>
</td>
</tr>
</table>

<hr>
<p class="small">
Obrigado pela preferência!<br>
Boa sorte nos torneios 🎴
</p>


