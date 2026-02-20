<style>
  .linha-relatorio {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 2rem;
    gap: 1rem;
  }
  .coluna {
    flex: 1;
    min-width: 300px;
    background: #1e1e1e;
    padding: 1rem;
    border-radius: 8px;
    border: 1px solid #444;
    color: #fff;
  }
  .coluna h2 {
    margin-bottom: 1rem;
    font-size: 1.2rem;
    border-bottom: 1px solid #555;
    padding-bottom: 0.5rem;
  }
  .kpis {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
  }
  .kpi {
    background: #2a2a2a;
    padding: 0.8rem;
    border-radius: 6px;
    text-align: center;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 0.5rem;
  }
  table th, table td {
    border: 1px solid #555;
    padding: 0.5rem;
    text-align: left;
  }
  table th {
    background: #333;
  }
</style>

<!-- Primeira linha: Dashboard + Estoque -->
<div class="linha-relatorio">
  <div class="coluna">
    <h2>📊 Dashboard de Relatórios</h2>
    <div class="kpis">
      <div class="kpi"><h3>Média por Dia</h3><p>R$ <?= number_format($mediaDia[0]['media_dia'] ?? 0, 2, ',', '.') ?></p></div>
      <div class="kpi"><h3>Média por Semana</h3><p>R$ <?= number_format($mediaSemana[0]['media_semana'] ?? 0, 2, ',', '.') ?></p></div>
      <div class="kpi"><h3>Média por Mês</h3><p>R$ <?= number_format($mediaMes[0]['media_mes'] ?? 0, 2, ',', '.') ?></p></div>
      <div class="kpi"><h3>Média por Ano</h3><p>R$ <?= number_format($mediaAno[0]['media_ano'] ?? 0, 2, ',', '.') ?></p></div>
    </div>
  </div>

  <div class="coluna">
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
  </div>
</div>

<!-- Segunda linha: Top Clientes + Gráfico -->
<div class="linha-relatorio">
  <div class="coluna">
    <h2>👥 Top 5 Clientes (Geral)</h2>
    <table id="tabelaClientes">
      <thead><tr><th>Cliente</th><th>Total</th></tr></thead>
      <tbody>
        <?php if (!empty($topClientesGeral)): ?>
          <?php foreach ($topClientesGeral as $cliente): ?>
            <tr><td><?= htmlspecialchars($cliente['nome']) ?></td><td>R$ <?= number_format($cliente['total'], 2, ',', '.') ?></td></tr>
          <?php endforeach; ?>
        <?php else: ?><tr><td colspan="2">Nenhum dado disponível</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
  <div class="coluna">
    <h2>📊 Gráfico Pizza Top 5 Clientes</h2>
    <canvas id="topClientesChart"></canvas>
  </div>
</div>

<!-- Terceira linha: Top Produtos + Gráfico -->
<div class="linha-relatorio">
  <div class="coluna">
    <h2>📦 Top 5 Produtos (Geral)</h2>
    <table id="tabelaProdutos">
      <thead><tr><th>Produto</th><th>Quantidade Vendida</th></tr></thead>
      <tbody>
        <?php if (!empty($topProdutosGeral)): ?>
          <?php foreach ($topProdutosGeral as $produto): ?>
            <tr><td><?= htmlspecialchars($produto['nome']) ?></td><td><?= $produto['total_vendido'] ?></td></tr>
          <?php endforeach; ?>
        <?php else: ?><tr><td colspan="2">Nenhum dado disponível</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
  <div class="coluna">
    <h2>📊 Gráfico Pizza Top 5 Produtos</h2>
    <canvas id="topProdutosChart"></canvas>
  </div>
</div>

<!-- Quarta linha: Métricas Mensais + Gráfico Barras -->
<div class="linha-relatorio">
  <div class="coluna">
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
            <td>R$ <?= number_format($m['total_mes'], 2, ',', '.') ?></td>
            <td>R$ <?= number_format($mediaDia, 2, ',', '.') ?></td>
            <td>R$ <?= number_format($mediaSemana, 2, ',', '.') ?></td>
            <td>R$ <?= number_format($mediaPedido, 2, ',', '.') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="coluna">
    <h2>📊 Gráfico de Barras Mensais</h2>
    <canvas id="graficoMensal"></canvas>
  </div>
</div>


<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
    // Gráfico Pizza Top Clientes
    const clientesLabels = <?php echo json_encode(array_map(fn($c) => $c['nome'], $topClientesGeral), JSON_UNESCAPED_UNICODE); ?>;
    const clientesData = <?php echo json_encode(array_map(fn($c) => (float)$c['total'], $topClientesGeral)); ?>;

    new Chart(document.getElementById('topClientesChart'), {
        type: 'pie',
        data: {
            labels: clientesLabels,
            datasets: [{
                data: clientesData,
                backgroundColor: ['#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF']
            }]
        },
        options: {
            plugins: {
                legend: { position: 'right' },
                title: { display: true, text: 'Top 5 Clientes' }
            }
        }
    });

    // Gráfico Pizza Top Produtos
    const produtosLabels = <?php echo json_encode(array_map(fn($p) => $p['nome'], $topProdutosGeral), JSON_UNESCAPED_UNICODE); ?>;
    const produtosData = <?php echo json_encode(array_map(fn($p) => (int)$p['total_vendido'], $topProdutosGeral)); ?>;

    new Chart(document.getElementById('topProdutosChart'), {
        type: 'pie',
        data: {
            labels: produtosLabels,
            datasets: [{
                data: produtosData,
                backgroundColor: ['#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF']
            }]
        },
        options: {
            plugins: {
                legend: { position: 'right' },
                title: { display: true, text: 'Top 5 Produtos' }
            }
        }
    });

    // Gráfico Barras Mensais
    const ctxMensal = document.getElementById('graficoMensal').getContext('2d');
    const meses = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
    const valores = <?php echo json_encode(array_column($metricasMensais, 'total_mes')); ?>;

    new Chart(ctxMensal, {
        type: 'bar',
        data: {
            labels: meses,
            datasets: [{
                label: 'Vendas por mês - <?= $anoAtual ?>',
                data: valores,
                backgroundColor: 'rgba(54,162,235,0.6)',
                borderColor: 'rgba(54,162,235,1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

