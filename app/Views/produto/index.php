<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="text-light">Produtos da Loja</h2>
  <div>
      <span class="badge bg-secondary me-2">Dica: Arraste as linhas para reordenar</span>
      <a href="/produto/criar" class="btn btn-primary btn-sm">➕ Novo Produto</a>
  </div>
</div>

<form id="formOrdem" method="POST" action="/produto/salvarOrdem">
    <div class="table-responsive">
        <table class="table table-dark table-striped table-hover align-middle">
            <thead>
                <tr>
                    <th width="50"></th>
                    <th>Nome</th>
                    <th>Emoji</th>
                    <th>Valor Venda</th>
                    <th>Valor Compra</th>
                    <th>Controla Estoque</th>
                    <th>Estoque Atual</th>
                    <th class="text-center">Ordem Atual</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="sortable-produtos">
                <?php foreach ($produtos as $produto): ?>
                    <?php
                        // Lógica de alerta de estoque
                        $alertaEstoque = false;
                        if ($produto['controlar_estoque'] == 1 && $produto['estoque_atual'] <= $produto['estoque_alerta']) {
                            $alertaEstoque = true;
                        }
                    ?>
                    <tr data-id="<?= $produto['id_produto'] ?>">
                        <td class="text-center text-muted" style="width: 30px;">
                            <i class="bi bi-grip-vertical">☰</i>
                        </td>
                        <td>
                            <input type="hidden" name="id_produto[]" value="<?= $produto['id_produto'] ?>">
                            <?= htmlspecialchars($produto['nome']) ?>
                        </td>
                        <td><?= htmlspecialchars($produto['emoji']) ?></td>
                        <td><span class="badge bg-success">R$ <?= number_format($produto['valor_venda'], 2, ',', '.') ?></span></td>

                        <td><span class="badge bg-secondary">R$ <?= number_format($produto['valor_compra'], 2, ',', '.') ?></span></td>

                        <td class="text-center">
                            <?php if ($produto['controlar_estoque'] == 1): ?>
                                <span class="badge bg-info text-dark">Sim</span>
                            <?php else: ?>
                                <span class="badge bg-light text-dark">Não</span>
                            <?php endif; ?>
                        </td>

                        <td class="<?= $alertaEstoque ? 'estoque-critico' : '' ?>">
                            <?= $produto['estoque_atual'] ?>
                        </td>

                        <td class="text-center">
                            <span class="badge bg-dark border show-ordem"><?= $produto['ordem_exibicao'] ?></span>
                        </td>
                        <td>
                            <a class="btn btn-sm btn-warning" href="/produto/editar/<?= $produto['id_produto'] ?>" title="Editar">✏️ Editar</a>

                            <?php if ($produto['ativo'] == 1): ?>
                                <a class="btn btn-sm btn-danger" href="/produto/desativar/<?= $produto['id_produto'] ?>" title="Desativar">🚫 Desativar</a>
                            <?php else: ?>
                                <a class="btn btn-sm btn-success" href="/produto/ativar/<?= $produto['id_produto'] ?>" title="Ativar">✅ Ativar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <button class="btn btn-success mt-3" type="submit">💾 Salvar Nova Ordem</button>
</form>


