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
                        <td><?= htmlspecialchars($torneio['cardgame']) ?></td>
                        <td><?= htmlspecialchars($torneio['tipo_legivel']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($torneio['data_criacao'])) ?></td>
                        <td><?= ucfirst($torneio['status']) ?></td>
                        <td>
                            <a href="/torneio/participantes/<?= $torneio['id_torneio'] ?>" class="btn btn-sm btn-success">Participantes</a>
                            <a href="/torneio/gerenciar/<?= $torneio['id_torneio'] ?>" class="btn btn-sm btn-info">Gerenciar</a>
                            <button class="btn btn-sm btn-primary" onclick="window.open('/torneio/verResultadoSuico/<?= $torneio['id_torneio'] ?>','_blank')">🏆 Resultado</button>
                            <a href="/torneio/excluir/<?= $torneio['id_torneio'] ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Tem certeza que deseja excluir este torneio?')">
                                Excluir
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

