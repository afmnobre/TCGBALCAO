<h2><?= isset($cliente) ? 'Editar Cliente' : 'Cadastrar Cliente' ?></h2>

<form method="POST" action="<?= isset($cliente) ? '/cliente/atualizar/'.$cliente['id_cliente'] : '/cliente/salvar' ?>">
    <label>Telefone (Celular):</label>
    <input type="text" name="telefone" value="<?= $cliente['telefone'] ?? '' ?>" required><br>

    <label>Nome:</label>
    <input type="text" name="nome" value="<?= $cliente['nome'] ?? '' ?>"><br>

    <label>Email:</label>
    <input type="email" name="email" value="<?= $cliente['email'] ?? '' ?>"><br>

    <h3>CardGames que o cliente joga:</h3>
    <?php if (!empty($cardgames)): ?>
        <?php foreach ($cardgames as $cg): ?>
            <?php $checked = (!empty($cardgamesCliente) && in_array($cg['id_cardgame'], array_column($cardgamesCliente, 'id_cardgame'))) ? 'checked' : ''; ?>
            <label>
                <input type="checkbox" name="cardgames[]" value="<?= $cg['id_cardgame'] ?>" <?= $checked ?>>
                <?= htmlspecialchars($cg['nome']) ?>
            </label><br>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nenhum cardgame cadastrado.</p>
    <?php endif; ?>

    <button type="submit">Salvar</button>
</form>

