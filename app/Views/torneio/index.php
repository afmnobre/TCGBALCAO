<h2 class="text-light mb-3">Torneios</h2>

<!-- Botão para criar novo torneio -->
<div class="mb-3">
    <a href="/torneio/criar" class="btn btn-primary">+ Novo Torneio</a>
</div>

<!-- Lista de torneios -->
<?php if (!empty($torneios)): ?>
    <div class="table-responsive">
        <table class="table table-dark table-striped table-hover align-middle">
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
                        <td><?= htmlspecialchars($torneio['cardgame'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($torneio['tipo_legivel'] ?? $torneio['tipo_torneio'] ?? 'Não definido') ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($torneio['data_criacao'])) ?></td>
                        <td><?= ucfirst($torneio['status']) ?></td>
                        <td>
                            <a href="/torneio/participantes/<?= $torneio['id_torneio'] ?>" class="btn btn-sm btn-success">Participantes</a>
                            <a href="/torneio/gerenciar/<?= $torneio['id_torneio'] ?>" class="btn btn-sm btn-info">Gerenciar</a>
                            <button class="btn btn-sm btn-primary"  onclick="window.open('/torneiosuico/verPontuacao/<?= $torneio['id_torneio'] ?>','_blank')">🏆 Resultado</button>
                            <a href="/torneio/excluir/<?= $torneio['id_torneio'] ?>"
                                class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('ATENÇÃO: Isso apagará permanentemente o torneio, todos os jogadores, rodadas e resultados. Confirma?')"
                                title="Excluir Torneio">
                                <i class="fas fa-trash-alt">Excluir</i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-warning">Nenhum torneio cadastrado até o momento.</div>
<?php endif; ?>

