<div class="container mt-4">

    <h3 class="mb-4">Dashboard</h3>

    <!-- CARDS RESUMO -->
    <div class="row mb-4">

        <div class="col-md-6">
            <div class="card shadow p-4 text-center">
                <h5>Total de Lojas</h5>
                <h2><?= $totalLojas ?></h2>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow p-4 text-center">
                <h5>Contratos Ativos</h5>
                <h2><?= $totalContratos ?></h2>
            </div>
        </div>

    </div>

    <div class="row">

        <!-- LISTA LOJAS -->
        <div class="col-md-6">
            <div class="card shadow p-3">
                <h5 class="mb-3">Últimas Lojas</h5>

                <?php if (!empty($lojas)): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach (array_slice($lojas, 0, 5) as $loja): ?>
                            <li class="list-group-item">
                                <?= htmlspecialchars($loja['nome_loja'] ?? 'Sem nome') ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">Nenhuma loja cadastrada.</p>
                <?php endif; ?>

            </div>
        </div>

        <!-- LISTA CONTRATOS -->
        <div class="col-md-6">
            <div class="card shadow p-3">
                <h5 class="mb-3">Contratos Ativos</h5>

                <?php if (!empty($contratos)): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach (array_slice($contratos, 0, 5) as $contrato): ?>
                            <li class="list-group-item">
                                Contrato #<?= $contrato['id_contrato'] ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">Nenhum contrato ativo.</p>
                <?php endif; ?>

            </div>
        </div>

    </div>

</div>

