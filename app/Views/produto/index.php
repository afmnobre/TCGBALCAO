<h2>Produtos da Loja</h2>

<a href="/produto/criar" class="btn-link">➕ Novo Produto</a>

<form method="POST" action="/produto/salvarOrdem">
<table>
<tr>
    <th>Nome</th>
    <th>Emoji</th>
    <th>Valor Venda</th>
    <th>Valor Compra</th>
    <th>Controla Estoque</th>
    <th>Estoque Atual</th>
    <th>Estoque Alerta</th>
    <th>Ordem de Exibição</th>
    <th>Ações</th>
</tr>

    <?php $total = count($produtos); ?>
    <?php foreach ($produtos as $produto): ?>
        <tr>
            <td><?= htmlspecialchars($produto['nome']) ?></td>
            <td><?= htmlspecialchars($produto['emoji']) ?></td>
            <td>R$ <?= number_format($produto['valor_venda'], 2, ',', '.') ?></td>
            <td>R$ <?= number_format($produto['valor_compra'], 2, ',', '.') ?></td>
            <td><?= !empty($produto['controlar_estoque']) ? 'Sim' : 'Não' ?></td>
            <td><?= $produto['estoque_atual'] ?></td>
            <td><?= $produto['estoque_alerta'] ?></td>
            <td>
                <select name="ordem[<?= $produto['id_produto'] ?>]">
                    <?php for ($i = 1; $i <= $total; $i++): ?>
                        <option value="<?= $i ?>" <?= ($produto['ordem_exibicao'] == $i) ? 'selected' : '' ?>>
                            <?= $i ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </td>
            <td>
                <a class="btn-link" href="/produto/editar/<?= $produto['id_produto'] ?>">✏️ Editar</a>
                <?php if ($produto['ativo'] == 1): ?>
                    <a class="btn-link" href="/produto/desativar/<?= $produto['id_produto'] ?>">🚫 Desativar</a>
                <?php else: ?>
                    <a class="btn-link" href="/produto/ativar/<?= $produto['id_produto'] ?>">✅ Ativar</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<button class="btn-link" type="submit">Salvar Ordem</button>
</form>

