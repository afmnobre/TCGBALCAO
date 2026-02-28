<div class="container mt-4">

    <h3><?= isset($usuario) ? "Editar Usuário" : "Cadastrar Usuário" ?></h3>

    <form method="POST" action="<?= isset($usuario) ? "/admin/usuarioLoja/update/{$usuario['id_usuario']}" : "/admin/usuarioLoja/store" ?>">

        <!-- LOJA -->
        <div class="mb-3">
            <label>Loja</label>
            <select name="id_loja" class="form-control" required>
                <option value="" disabled <?= !isset($usuario) ? 'selected' : '' ?>>Selecione a loja</option>
                <?php foreach ($lojas as $loja): ?>
                    <option value="<?= $loja['id_loja'] ?>"
                        <?= (isset($usuario) && $usuario['id_loja'] == $loja['id_loja']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($loja['nome_loja']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- NOME -->
        <div class="mb-3">
            <label>Nome</label>
            <input type="text" name="nome" class="form-control" required
                   value="<?= isset($usuario) ? htmlspecialchars($usuario['nome']) : '' ?>">
        </div>

        <!-- EMAIL -->
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required
                   value="<?= isset($usuario) ? htmlspecialchars($usuario['email']) : '' ?>">
        </div>

        <!-- SENHA -->
        <div class="mb-3">
            <label>Senha <?= isset($usuario) ? "(preencha apenas para alterar)" : "" ?></label>
            <input type="password" name="senha" class="form-control" <?= isset($usuario) ? "" : "required" ?>>
        </div>

        <!-- PERFIL -->
        <div class="mb-3">
            <label>Perfil</label>
            <select name="perfil" class="form-control" required>
                <option value="atendente" <?= (isset($usuario) && $usuario['perfil'] == 'atendente') ? 'selected' : '' ?>>Atendente</option>
                <option value="gerente" <?= (isset($usuario) && $usuario['perfil'] == 'gerente') ? 'selected' : '' ?>>Gerente</option>
            </select>
        </div>

        <!-- ATIVO -->
        <div class="mb-3">
            <label>Ativo</label>
            <select name="ativo" class="form-control">
                <option value="1" <?= (isset($usuario) && $usuario['ativo'] == 1) ? 'selected' : '' ?>>Sim</option>
                <option value="0" <?= (isset($usuario) && $usuario['ativo'] == 0) ? 'selected' : '' ?>>Não</option>
            </select>
        </div>

        <!-- BOTÕES -->
        <button type="submit" class="btn btn-success">
            <?= isset($usuario) ? "Atualizar" : "Salvar" ?>
        </button>

        <a href="/admin/usuarioLoja" class="btn btn-secondary">Cancelar</a>

    </form>

</div>

