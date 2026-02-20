<h2 class="text-light mb-3">Novo Torneio</h2>

<form action="/torneio/salvar" method="POST" class="bg-dark text-light p-4 rounded">
    <div class="row mb-3">
        <!-- Nome do torneio -->
        <div class="col-md-6">
            <label for="nome_torneio" class="form-label text-light">Nome do Torneio</label>
            <input type="text" class="form-control bg-dark text-light border-secondary" id="nome_torneio" name="nome_torneio" required>
        </div>

        <!-- Cardgame -->
        <div class="col-md-6">
            <label for="id_cardgame" class="form-label text-light">Cardgame</label>
            <select class="form-select bg-dark text-light border-secondary" id="id_cardgame" name="id_cardgame" required>
                <option value="">Selecione...</option>
                <?php foreach ($cardgames as $cg): ?>
                    <option value="<?= $cg['id_cardgame'] ?>"><?= htmlspecialchars($cg['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <!-- Tipo de torneio -->
        <div class="col-md-6">
            <label for="tipo_torneio" class="form-label text-light">Tipo de Torneio</label>
            <select class="form-select bg-dark text-light border-secondary" id="tipo_torneio" name="tipo_torneio" required>
                <option value="suico_bo1">Suíço - Melhor de 1</option>
                <option value="suico_bo3">Suíço - Melhor de 3</option>
                <option value="elim_dupla_bo1">Eliminação Dupla - Melhor de 1</option>
                <option value="elim_dupla_bo3">Eliminação Dupla - Melhor de 3</option>
            </select>
        </div>

        <!-- Tempo de rodada -->
        <div class="col-md-6">
            <label for="tempo_rodada" class="form-label text-light">Tempo de Rodada (minutos)</label>
            <input type="number" class="form-control bg-dark text-light border-secondary" id="tempo_rodada" name="tempo_rodada" value="50" min="10" required>
        </div>
    </div>

    <!-- Botões -->
    <button type="submit" class="btn btn-success">Criar Torneio</button>
    <a href="/torneio" class="btn btn-secondary">Cancelar</a>
</form>


