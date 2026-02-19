
    <h2 class="mb-3">Novo Torneio</h2>

    <form action="/torneio/salvar" method="POST">
        <!-- Nome do torneio -->
        <div class="mb-3">
            <label for="nome_torneio" class="form-label">Nome do Torneio</label>
            <input type="text" class="form-control" id="nome_torneio" name="nome_torneio" required>
        </div>

        <!-- Cardgame -->
        <div class="mb-3">
            <label for="id_cardgame" class="form-label">Cardgame</label>
            <select class="form-select" id="id_cardgame" name="id_cardgame" required>
                <option value="">Selecione...</option>
                <?php foreach ($cardgames as $cg): ?>
                    <option value="<?= $cg['id_cardgame'] ?>"><?= htmlspecialchars($cg['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Tipo de torneio -->
        <div class="mb-3">
            <label for="tipo_torneio" class="form-label">Tipo de Torneio</label>
            <select class="form-select" id="tipo_torneio" name="tipo_torneio" required>
                <option value="suico_bo1">Suíço - Melhor de 1</option>
                <option value="suico_bo3">Suíço - Melhor de 3</option>
                <option value="elim_dupla_bo1">Eliminação Dupla - Melhor de 1</option>
                <option value="elim_dupla_bo3">Eliminação Dupla - Melhor de 3</option>
            </select>
        </div>

        <!-- Tempo de rodada -->
        <div class="mb-3">
            <label for="tempo_rodada" class="form-label">Tempo de Rodada (minutos)</label>
            <input type="number" class="form-control" id="tempo_rodada" name="tempo_rodada" value="50" min="10" required>
        </div>

        <!-- Botão -->
        <button type="submit" class="btn btn-success">Criar Torneio</button>
        <a href="/torneio" class="btn btn-secondary">Cancelar</a>
    </form>




