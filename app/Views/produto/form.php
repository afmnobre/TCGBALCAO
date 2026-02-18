<h2><?= isset($produto) ? 'Editar Produto' : 'Novo Produto' ?></h2>

<form method="POST" action="<?= isset($produto) ? '/produto/atualizar/'.$produto['id_produto'] : '/produto/salvar' ?>">
    <label>Nome:</label><br>
    <input type="text" name="nome" value="<?= $produto['nome'] ?? '' ?>" required><br><br>

    <label>Emoji:</label><br>
    <input type="text" name="emoji" value="<?= $produto['emoji'] ?? '' ?>"><br><br>

    <label>Valor de Venda:</label><br>
    <input type="text" name="valor_venda" value="<?= isset($produto['valor_venda']) ? number_format($produto['valor_venda'], 2, ',', '.') : '' ?>"><br><br>

    <label>Valor de Compra:</label><br>
    <input type="text" name="valor_compra" value="<?= isset($produto['valor_compra']) ? number_format($produto['valor_compra'], 2, ',', '.') : '' ?>"><br><br>

    <label>Controlar Estoque:</label>
    <input type="checkbox" name="controlar_estoque" <?= !empty($produto['controlar_estoque']) ? 'checked' : '' ?>><br><br>

    <label>Estoque Atual:</label><br>
    <input type="number" name="estoque_atual" value="<?= $produto['estoque_atual'] ?? 0 ?>"><br><br>

    <label>Estoque Alerta (mínimo):</label><br>
    <input type="number" name="estoque_alerta" value="<?= $produto['estoque_alerta'] ?? 0 ?>"><br><br>

    <label>Ordem de Exibição:</label><br>
    <input type="number" name="ordem_exibicao" value="<?= $produto['ordem_exibicao'] ?? 0 ?>"><br><br>


    <label>Fornecedor ID:</label><br>
    <input type="number" name="id_fornecedor" value="<?= $produto['id_fornecedor'] ?? '' ?>"><br><br>

    <button type="submit" class="btn-link">Salvar</button>
</form>

