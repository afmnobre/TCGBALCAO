
    <h2 class="mb-3">Torneios</h2>

    <!-- Botão para criar novo torneio -->
    <div class="mb-3">
        <a href="/torneio/criar" class="btn btn-primary">+ Novo Torneio</a>
    </div>

    <!-- Lista de torneios -->
    <?php if (!empty($torneios)): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Cardgame</th>
                    <th>Tipo</th>
                    <th>Data Criação</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($torneios as $torneio): ?>
                    <tr>
                        <td><?= htmlspecialchars($torneio['nome_torneio']) ?></td>
                        <td><?= htmlspecialchars($torneio['cardgame']) ?></td>
                        <td><?= strtoupper($torneio['tipo_torneio']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($torneio['data_criacao'])) ?></td>
                        <td><?= ucfirst($torneio['status']) ?></td>
                        <td>
                            <a href="/torneio/participantes/<?= $torneio['id_torneio'] ?>" class="btn btn-sm btn-success">Participantes</a>
                            <a href="/torneio/gerenciar/<?= $torneio['id_torneio'] ?>" class="btn btn-sm btn-info">Gerenciar</a>
                            <a href="/torneio/resultado/<?= $torneio['id_torneio'] ?>" class="btn btn-sm btn-secondary">Resultado</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">Nenhum torneio cadastrado até o momento.</div>
    <?php endif; ?>




