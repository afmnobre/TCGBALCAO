
    <h2 class="mb-3">Selecionar Participantes - <?= htmlspecialchars($torneio['nome_torneio']) ?></h2>
    <p>
        <strong>Cardgame:</strong> <?= htmlspecialchars($torneio['cardgame_nome']) ?> |
        <strong>Tipo:</strong> <?= htmlspecialchars($torneio['tipo_legivel']) ?>
    </p>

    <form action="/torneio/salvarParticipantes/<?= $torneio['id_torneio'] ?>" method="POST">
        <div class="mb-3">
            <?php if (!empty($clientes)): ?>
                <div class="list-group">
                    <?php foreach ($clientes as $cliente): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <input type="checkbox" name="participantes[]" value="<?= $cliente['id_cliente'] ?>" class="form-check-input me-2">
                                <?= htmlspecialchars($cliente['nome']) ?>
                                <?php if (!empty($cliente['email'])): ?>
                                    <small class="text-muted"> - <?= htmlspecialchars($cliente['email']) ?></small>
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

        <button type="submit" class="btn btn-success">Confirmar Participantes</button>
        <a href="/torneio" class="btn btn-secondary">Cancelar</a>
    </form>

