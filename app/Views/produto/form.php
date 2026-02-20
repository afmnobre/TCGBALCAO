<h2 class="text-light mb-3">
    <?= isset($produto) ? '✏️ Editar Produto' : '➕ Novo Produto' ?>
</h2>

<form method="POST" action="<?= isset($produto) ? '/produto/atualizar/'.$produto['id_produto'] : '/produto/salvar' ?>" class="bg-dark text-light p-4 rounded border border-secondary">
    <div class="row">
        <!-- Coluna 1 -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" id="nome" name="nome" class="form-control" value="<?= $produto['nome'] ?? '' ?>" required>
            </div>

            <!-- Campo Emoji com seletor -->
            <div class="mb-3">
              <label for="emoji" class="form-label">Emoji</label>
              <div class="input-group">
                <input type="text" id="emoji" name="emoji" class="form-control" value="<?= $produto['emoji'] ?? '' ?>">
                <button type="button" id="emojiPickerBtn" class="btn btn-outline-secondary">😀</button>
              </div>
            </div>

            <!-- Container para o picker -->
            <div id="emojiContainer" style="display:none; position:absolute; z-index:1000;"></div>


            <div class="mb-3">
                <label for="valor_venda" class="form-label">Valor de Venda</label>
                <input type="text" id="valor_venda" name="valor_venda" class="form-control"
                       value="<?= isset($produto['valor_venda']) ? number_format($produto['valor_venda'], 2, ',', '.') : '' ?>">
            </div>

            <div class="mb-3">
                <label for="valor_compra" class="form-label">Valor de Compra</label>
                <input type="text" id="valor_compra" name="valor_compra" class="form-control"
                       value="<?= isset($produto['valor_compra']) ? number_format($produto['valor_compra'], 2, ',', '.') : '' ?>">
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" id="controlar_estoque" name="controlar_estoque" class="form-check-input"
                       <?= !empty($produto['controlar_estoque']) ? 'checked' : '' ?>>
                <label for="controlar_estoque" class="form-check-label">Controlar Estoque</label>
            </div>
        </div>

        <!-- Coluna 2 -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="estoque_atual" class="form-label">Estoque Atual</label>
                <input type="number" id="estoque_atual" name="estoque_atual" class="form-control" value="<?= $produto['estoque_atual'] ?? 0 ?>">
            </div>

            <div class="mb-3">
                <label for="estoque_alerta" class="form-label">Estoque Alerta (mínimo)</label>
                <input type="number" id="estoque_alerta" name="estoque_alerta" class="form-control" value="<?= $produto['estoque_alerta'] ?? 0 ?>">
            </div>

            <div class="mb-3">
                <label for="ordem_exibicao" class="form-label">Ordem de Exibição</label>
                <input type="number" id="ordem_exibicao" name="ordem_exibicao" class="form-control" value="1" readonly>
            </div>

            <div class="mb-3">
                <label for="id_fornecedor" class="form-label">Fornecedor ID</label>
                <input type="number" id="id_fornecedor" name="id_fornecedor" class="form-control" value="1" readonly>
            </div>

        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">💾 Salvar</button>
        <a href="/produto" class="btn btn-secondary">↩️ Voltar</a>
    </div>
</form>



