<?php require_once __DIR__ . '/layout/header.php'; ?>

<div class="container">
    <h2>Clientes Inativos (mais de 2 meses)</h2>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID Cliente</th>
                <th>Nome</th>
                <th>Última Compra</th>
                <th>Total de Pedidos</th>
                <th>Total Gasto (R$)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($clientesInativos)): ?>
                <?php foreach ($clientesInativos as $cliente): ?>
                    <tr>
                        <td><?= htmlspecialchars($cliente['id_cliente']) ?></td>
                        <td><?= htmlspecialchars($cliente['nome']) ?></td>
                        <td>
                            <?= $cliente['ultima_compra']
                                ? date('d/m/Y', strtotime($cliente['ultima_compra']))
                                : 'Nunca comprou' ?>
                        </td>
                        <td><?= htmlspecialchars($cliente['total_pedidos']) ?></td>
                        <td><?= number_format($cliente['total_gasto'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Nenhum cliente inativo encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>



