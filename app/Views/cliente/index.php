<?php if (!empty($_SESSION['flash'])): ?>
    <p style="color: green;"><?= $_SESSION['flash'] ?></p>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>


<h2>Clientes da Loja</h2>

<a href="/cliente/criar" class="btn-link">➕ Novo Cliente</a>

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
                <td class="telefone-coluna"><?= htmlspecialchars($cliente['telefone']) ?></td>
                <td>
<?php if (!empty($cliente['cardgames'])): ?>
    <div class="cliente-cardgames">
        <?php foreach ($cliente['cardgames'] as $game): ?>
            <div class="cliente-cardgame-thumb"
                 title="<?= htmlspecialchars($game['nome']) ?>"
                 style="background-image: url('/public/images/cartas_fundo/<?= htmlspecialchars($game['imagem_fundo_card']) ?>');">
                <span class="cliente-cardgame-name"><?= htmlspecialchars($game['nome']) ?></span>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <span>Nenhum</span>
<?php endif; ?>
                </td>
                <td>
                    <a href="/cliente/editar/<?= $cliente['id_cliente'] ?>" class="btn-link">✏️ Editar</a>
                    <a href="/cliente/excluir/<?= $cliente['id_cliente'] ?>"
                       onclick="return confirm('Tem certeza que deseja excluir este cliente?')" class="btn-link">🗑️ Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">Nenhum cliente cadastrado ainda.</td>
        </tr>
    <?php endif; ?>
</table>
<br><br><br>

