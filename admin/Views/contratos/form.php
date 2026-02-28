<div class="container mt-4">

    <h3><?= isset($contrato) ? "Editar Contrato" : "Cadastrar Contrato" ?></h3>

    <form method="POST" action="<?= isset($contrato) ? "/admin/contrato/update/{$contrato['id_contrato']}" : "/admin/contrato/store" ?>">

        <div class="mb-3">
            <label>Loja</label>
            <select name="id_loja" class="form-control" required>
                <option value="">Selecione a loja</option>
                <?php foreach ($lojas as $loja): ?>
                    <option value="<?= $loja['id_loja'] ?>" <?= (isset($contrato) && $contrato['id_loja'] == $loja['id_loja']) ? 'selected' : '' ?>>
                        <?= $loja['nome_loja'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Tipo</label>
            <select name="tipo" class="form-control" required>
                <option value="teste" <?= (isset($contrato) && $contrato['tipo'] == 'teste') ? 'selected' : '' ?>>Teste</option>
                <option value="mensal" <?= (isset($contrato) && $contrato['tipo'] == 'mensal') ? 'selected' : '' ?>>Mensal</option>
                <option value="anual" <?= (isset($contrato) && $contrato['tipo'] == 'anual') ? 'selected' : '' ?>>Anual</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Data Início</label>
            <input type="date" name="data_inicio" class="form-control" value="<?= isset($contrato) ? $contrato['data_inicio'] : '' ?>" required>
        </div>

        <div class="mb-3">
            <label>Data Fim</label>
            <input type="date" name="data_fim" class="form-control" value="<?= isset($contrato) ? $contrato['data_fim'] : '' ?>">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="ativo" <?= (isset($contrato) && $contrato['status'] == 'ativo') ? 'selected' : '' ?>>Ativo</option>
                <option value="suspenso" <?= (isset($contrato) && $contrato['status'] == 'suspenso') ? 'selected' : '' ?>>Suspenso</option>
                <option value="cancelado" <?= (isset($contrato) && $contrato['status'] == 'cancelado') ? 'selected' : '' ?>>Cancelado</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">
            <?= isset($contrato) ? "Atualizar" : "Salvar" ?>
        </button>

        <a href="/admin/contrato" class="btn btn-secondary">Cancelar</a>

    </form>

</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoSelect = document.querySelector('select[name="tipo"]');
    const dataInicio = document.querySelector('input[name="data_inicio"]');
    const dataFim = document.querySelector('input[name="data_fim"]');

    function atualizarDataFim() {
        const tipo = tipoSelect.value;
        const inicio = dataInicio.value;
        if (!inicio) return;

        let diasAdicionar = 0;
        switch(tipo) {
            case 'teste':
                diasAdicionar = 7;
                break;
            case 'mensal':
                diasAdicionar = 30;
                break;
            case 'anual':
                diasAdicionar = 365;
                break;
        }

        const inicioDate = new Date(inicio);
        inicioDate.setDate(inicioDate.getDate() + diasAdicionar);

        // Formata para yyyy-mm-dd (para o input date)
        const ano = inicioDate.getFullYear();
        const mes = String(inicioDate.getMonth() + 1).padStart(2, '0');
        const dia = String(inicioDate.getDate()).padStart(2, '0');

        dataFim.value = `${ano}-${mes}-${dia}`;
    }

    // Atualiza sempre que muda a data de início ou o tipo
    dataInicio.addEventListener('change', atualizarDataFim);
    tipoSelect.addEventListener('change', atualizarDataFim);
});
</script>

