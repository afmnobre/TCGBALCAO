<?php if (!empty($_SESSION['flash'])): ?>
    <p style="color: green;"><?= $_SESSION['flash'] ?></p>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>


<h2>Clientes da Loja</h2>

<a href="/cliente/criar">➕ Novo Cliente</a>

<table>
    <tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Telefone</th>
        <th>CardGames</th>
        <th>Ações</th>
    </tr>

    <?php if (!empty($clientes)): ?>
        <?php foreach ($clientes as $cliente): ?>
            <tr>
                <td><?= htmlspecialchars($cliente['nome']) ?></td>
                <td><?= htmlspecialchars($cliente['email']) ?></td>
                <td><?= htmlspecialchars($cliente['telefone']) ?></td>
                <td>
                    <?php if (!empty($cliente['cardgames'])): ?>
                        <?= implode(', ', array_column($cliente['cardgames'], 'nome')) ?>
                    <?php else: ?>
                        Nenhum
                    <?php endif; ?>
                </td>
                <td>
                    <a href="/cliente/editar/<?= $cliente['id_cliente'] ?>">✏️ Editar</a>
                    <a href="/cliente/excluir/<?= $cliente['id_cliente'] ?>"
                       onclick="return confirm('Tem certeza que deseja excluir este cliente?')">🗑️ Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">Nenhum cliente cadastrado ainda.</td>
        </tr>
    <?php endif; ?>
</table>

