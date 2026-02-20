<h2 class="text-light mb-3">Produtos da Loja</h2>

<!-- Botão para criar novo produto -->
<div class="mb-3">
    <a href="/produto/criar" class="btn btn-primary">➕ Novo Produto</a>
</div>

<form method="POST" action="/produto/salvarOrdem">
    <div class="table-responsive">
        <table class="table table-dark table-striped table-hover align-middle">
            <thead>
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
            </thead>
            <tbody>
                <?php $total = count($produtos); ?>
                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td><?= htmlspecialchars($produto['nome']) ?></td>
                        <td><?= htmlspecialchars($produto['emoji']) ?></td>
                        <td><span class="badge bg-success">R$ <?= number_format($produto['valor_venda'], 2, ',', '.') ?></span></td>
                        <td><span class="badge bg-secondary">R$ <?= number_format($produto['valor_compra'], 2, ',', '.') ?></span></td>
                        <td>
                            <?php if (!empty($produto['controlar_estoque'])): ?>
                                <span class="badge bg-info">Sim</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Não</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $produto['estoque_atual'] ?></td>
                        <td><?= $produto['estoque_alerta'] ?></td>
                        <td>
                            <select class="form-select form-select-sm" name="ordem[<?= $produto['id_produto'] ?>]">
                                <?php for ($i = 1; $i <= $total; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($produto['ordem_exibicao'] == $i) ? 'selected' : '' ?>>
                                        <?= $i ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </td>
                        <td>
                            <a class="btn btn-sm btn-warning" href="/produto/editar/<?= $produto['id_produto'] ?>">✏️ Editar</a>
                            <?php if ($produto['ativo'] == 1): ?>
                                <a class="btn btn-sm btn-danger" href="/produto/desativar/<?= $produto['id_produto'] ?>">🚫 Desativar</a>
                            <?php else: ?>
                                <a class="btn btn-sm btn-success" href="/produto/ativar/<?= $produto['id_produto'] ?>">✅ Ativar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <button class="btn btn-primary mt-3" type="submit">💾 Salvar Ordem</button>
</form>


