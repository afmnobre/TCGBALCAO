<h2 class="text-light mb-3">Selecionar Participantes - <?= htmlspecialchars($torneio['nome_torneio']) ?></h2>
<p class="text-light">
    <strong>Cardgame:</strong> <?= htmlspecialchars($torneio['cardgame']) ?> |
    <strong>Tipo:</strong> <?= htmlspecialchars($torneio['tipo_legivel']) ?>
</p>

<form action="/torneio/salvarParticipantes/<?= $torneio['id_torneio'] ?>" method="POST" class="bg-dark text-light p-4 rounded">
    <div class="mb-3">
        <?php if (!empty($clientes)): ?>
            <div class="list-group">
                <?php foreach ($clientes as $cliente): ?>
                    <div class="list-group-item bg-dark text-light d-flex justify-content-between align-items-center">
                        <div>
                            <input type="checkbox" name="participantes[]" value="<?= $cliente['id_cliente'] ?>" class="form-check-input me-2">
                            <?= htmlspecialchars($cliente['nome']) ?>
                            <?php if (!empty($cliente['email'])): ?>
                                <small class=".text-danger"> - <?= htmlspecialchars($cliente['email']) ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                Nenhum cliente vinculado a este cardgame na loja.
            </div>
        <?php endif; ?>
    </div>

    <!-- Botões -->
    <button type="submit" class="btn btn-success">Confirmar Participantes</button>
    <a href="/torneio" class="btn btn-secondary">Cancelar</a>
</form>

