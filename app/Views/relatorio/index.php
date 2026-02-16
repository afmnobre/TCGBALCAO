<div class="container">
    <h1>📊 Dashboard de Relatórios</h1>

    <!-- KPIs rápidos -->
    <div class="kpis">
        <div class="kpi"><h3>Média por Dia</h3><p><?= number_format($mediaDia[0]['media_dia'] ?? 0, 2, ',', '.') ?></p></div>
        <div class="kpi"><h3>Média por Semana</h3><p><?= number_format($mediaSemana[0]['media_semana'] ?? 0, 2, ',', '.') ?></p></div>
        <div class="kpi"><h3>Média por Mês</h3><p><?= number_format($mediaMes[0]['media_mes'] ?? 0, 2, ',', '.') ?></p></div>
        <div class="kpi"><h3>Média por Ano</h3><p><?= number_format($mediaAno[0]['media_ano'] ?? 0, 2, ',', '.') ?></p></div>
    </div>

    <!-- Top 5 Clientes -->
    <h2>👥 Top 5 Clientes (Geral)</h2>
    <table>
        <thead><tr><th>Cliente</th><th>Total</th></tr></thead>
        <tbody>
            <?php if (!empty($topClientesGeral)): ?>
                <?php foreach ($topClientesGeral as $cliente): ?>
                    <tr><td><?= htmlspecialchars($cliente['nome']) ?></td><td><?= number_format($cliente['total'], 2, ',', '.') ?></td></tr>
                <?php endforeach; ?>
            <?php else: ?><tr><td colspan="2">Nenhum dado disponível</td></tr><?php endif; ?>
        </tbody>
    </table>

    <!-- Top 5 Produtos -->
    <h2>📦 Top 5 Produtos (Geral)</h2>
    <table>
        <thead><tr><th>Produto</th><th>Quantidade Vendida</th></tr></thead>
        <tbody>
            <?php if (!empty($topProdutosGeral)): ?>
                <?php foreach ($topProdutosGeral as $produto): ?>
                    <tr><td><?= htmlspecialchars($produto['nome']) ?></td><td><?= $produto['total_vendido'] ?></td></tr>
                <?php endforeach; ?>
            <?php else: ?><tr><td colspan="2">Nenhum dado disponível</td></tr><?php endif; ?>
        </tbody>
    </table>

    <!-- Gráfico de Pizza -->
    <canvas id="topProdutosChart"></canvas>
</div>

<!-- Chart.js + Plugin -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>

<script>
    const labels = <?php echo json_encode(array_map(fn($p) => $p['nome'], $topProdutosGeral), JSON_UNESCAPED_UNICODE); ?>;
    const data = <?php echo json_encode(array_map(fn($p) => (int)$p['total_vendido'], $topProdutosGeral)); ?>;

    new Chart(document.getElementById('topProdutosChart'), {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'right' },
                title: {
                    display: true,
                    text: 'Top 5 Produtos Mais Vendidos'
                },
                datalabels: {
                    color: '#fff',
                    formatter: (value, ctx) => {
                        let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        let percentage = (value / sum * 100).toFixed(1) + "%";
                        return percentage;
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
</script>
    <!-- Estoque Atual -->
    <h2>📦 Estoque Atual</h2>
    <table>
        <thead><tr><th>Produto</th><th>Estoque</th></tr></thead>
        <tbody>
            <?php if (!empty($estoqueAtual)): ?>
                <?php foreach ($estoqueAtual as $prod): ?>
                    <tr><td><?= htmlspecialchars($prod['nome']) ?></td><td><?= $prod['estoque_atual'] ?></td></tr>
                <?php endforeach; ?>
            <?php else: ?><tr><td colspan="2">Nenhum produto com estoque controlado</td></tr><?php endif; ?>
        </tbody>
    </table>



    <!-- Métricas Mensais -->
<h2>📅 Métricas Mensais (Ano <?= $anoAtual ?>)</h2>
<table>
    <thead>
        <tr>
            <th>Mês</th>
            <th>Total Vendas</th>
            <th>Média por Dia</th>
            <th>Média por Semana</th>
            <th>Média por Pedido</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($metricasMensais as $m): ?>
            <?php
                $mediaDia    = $m['dias_com_venda'] > 0 ? $m['total_mes'] / $m['dias_com_venda'] : 0;
                $mediaSemana = $m['semanas_com_venda'] > 0 ? $m['total_mes'] / $m['semanas_com_venda'] : 0;
                $mediaPedido = $m['pedidos_mes'] > 0 ? $m['total_mes'] / $m['pedidos_mes'] : 0;
            ?>
            <tr>
                <td><?= str_pad($m['mes'], 2, '0', STR_PAD_LEFT) ?>/<?= $m['ano'] ?></td>
                <td><?= number_format($m['total_mes'], 2, ',', '.') ?></td>
                <td><?= number_format($mediaDia, 2, ',', '.') ?></td>
                <td><?= number_format($mediaSemana, 2, ',', '.') ?></td>
                <td><?= number_format($mediaPedido, 2, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<canvas id="graficoMensal" width="800" height="400"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('graficoMensal').getContext('2d');

    const meses = [
        'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
    ];

    const valores = <?php echo json_encode(array_column($metricasMensais, 'total_mes')); ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: meses,
            datasets: [{
                label: 'Vendas por mês - <?php echo $anoAtual; ?>',
                data: valores,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total de vendas'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Meses'
                    }
                }
            }
        }
    });
</script>






