<div class="container mt-4">

    <h3><?= isset($loja) ? 'Editar Loja' : 'Cadastrar Loja' ?></h3>

    <form method="POST" action="<?= isset($loja) ? '/admin/loja/update/' . $loja['id_loja'] : '/admin/loja/store' ?>" enctype="multipart/form-data">

        <div class="mb-3">
            <label>Nome da Loja</label>
            <input type="text" name="nome_loja" class="form-control" required
                   value="<?= isset($loja) ? htmlspecialchars($loja['nome_loja']) : '' ?>">
        </div>

        <div class="mb-3">
            <label>CNPJ</label>
            <input type="text" name="cnpj" class="form-control" required
                   value="<?= isset($loja) ? htmlspecialchars($loja['cnpj']) : '' ?>">
        </div>

        <div class="mb-3">
            <label>Endereço</label>
            <input type="text" name="endereco" class="form-control"
                   value="<?= isset($loja) ? htmlspecialchars($loja['endereco']) : '' ?>">
        </div>

        <div class="mb-3">
            <label>Cor Tema</label>
            <input type="color" name="cor_tema" class="form-control form-control-color"
                   value="<?= isset($loja) ? $loja['cor_tema'] : '#ffffff' ?>">
        </div>

        <div class="mb-3">
            <label>Logo</label>
            <input type="file" name="logo" class="form-control">
            <?php if(isset($loja) && !empty($loja['logo'])): ?>
                <img src="/public/storage/uploads/lojas/<?= $loja['id_loja'] ?>/<?= $loja['logo'] ?>" height="40" class="mt-2">
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label>Favicon</label>
            <input type="file" name="favicon" class="form-control">
            <?php if(isset($loja) && !empty($loja['favicon'])): ?>
                <img src="/public/storage/uploads/lojas/<?= $loja['id_loja'] ?>/<?= $loja['favicon'] ?>" height="20" class="mt-2">
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-success">
            <?= isset($loja) ? 'Atualizar' : 'Salvar' ?>
        </button>

    </form>

</div>


