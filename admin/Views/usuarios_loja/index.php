<div class="container mt-4">
    <h3>Usuários das Lojas</h3>

    <a href="/admin/usuarioLoja/form" class="btn btn-success mb-3">Cadastrar Usuário</a>

    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Loja</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Perfil</th>
                <th>Ativo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= $u['id_usuario'] ?></td>
                    <td><?= $u['nome_loja'] ?></td>
                    <td><?= $u['nome'] ?></td>
                    <td><?= $u['email'] ?></td>
                    <td><?= ucfirst($u['perfil']) ?></td>
                    <td><?= $u['ativo'] ? 'Sim' : 'Não' ?></td>
                    <td>
                        <a href="/admin/usuarioLoja/form/<?= $u['id_usuario'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="/admin/usuarioLoja/delete/<?= $u['id_usuario'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir este usuário?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


