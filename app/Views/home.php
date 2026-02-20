<div class="row">
  <!-- Coluna esquerda: Clientes Inativos -->
  <div class="col-md-6">
    <div class="card mb-4 bg-dark text-light">
      <div class="card-header bg-danger text-white">
        👥 Clientes Inativos (mais de 2 meses)
      </div>
      <div class="card-body">
        <table class="table table-dark table-striped table-bordered">
          <thead>
            <tr>
              <th>ID Cliente</th>
              <th>Nome</th>
              <th>Telefone</th>
              <th>Última Compra</th>
              <th>Total de Pedidos</th>
              <th>Total Gasto (R$)</th>
              <th>Ação</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($clientesInativos)): ?>
              <?php foreach ($clientesInativos as $cliente): ?>
                <tr>
                  <td><?= htmlspecialchars($cliente['id_cliente']) ?></td>
                  <td><?= htmlspecialchars($cliente['nome']) ?></td>
                  <td><?= !empty($cliente['telefone']) ? htmlspecialchars($cliente['telefone']) : 'Sem telefone' ?></td>
                  <td>
                    <?= $cliente['ultima_compra']
                      ? date('d/m/Y', strtotime($cliente['ultima_compra']))
                      : 'Nunca comprou' ?>
                  </td>
                  <td><?= htmlspecialchars($cliente['total_pedidos']) ?></td>
                  <td>R$ <?= number_format($cliente['total_gasto'], 2, ',', '.') ?></td>
                  <td>
                    <?php if (!empty($cliente['telefone'])): ?>
                      <a href="https://wa.me/55<?= preg_replace('/\D/', '', $cliente['telefone']) ?>?text=Olá%20<?= urlencode($cliente['nome']) ?>,%20sentimos%20sua%20falta!%20Temos%20novidades%20para%20você."
                         target="_blank" class="btn btn-success btn-sm">WhatsApp</a>
                    <?php else: ?>
                      <span class="text-muted">Sem contato</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="7" class="text-center">Nenhum cliente inativo encontrado.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Coluna direita: Contrato e Usuário -->
  <div class="col-md-6">
    <div class="card mb-4 bg-dark text-light">
      <div class="card-header bg-primary text-white">
        📅 Informações do Contrato
      </div>
      <div class="card-body">
        <p><strong>Número do Contrato:</strong> <?= $contrato['numero'] ?? '---' ?></p>
        <p><strong>Dias para expirar:</strong> <?= $contrato['dias_restantes'] ?? '---' ?></p>
      </div>
    </div>

    <div class="card mb-4 bg-dark text-light">
      <div class="card-header bg-secondary text-white">
        ⚙️ Configurações do Usuário
      </div>
      <div class="card-body">
        <a href="/usuario/alterar-senha" class="btn btn-outline-light">Alterar Senha</a>
        <a href="/usuario/editar-dados" class="btn btn-outline-info">Atualizar Dados</a>
      </div>
    </div>
  </div>
</div>

<!-- Linha extra: Resumo do dia -->
<div class="row">
  <div class="col-md-12">
    <div class="card mb-4 bg-dark text-light">
      <div class="card-header bg-success text-white">
        📊 Resumo do Dia
      </div>
      <div class="card-body d-flex justify-content-around">
        <div><strong>Pedidos Hoje:</strong> <?= $resumoDia['pedidos'] ?? 0 ?></div>
        <div><strong>Total Vendido:</strong> R$ <?= number_format($resumoDia['total_vendido'] ?? 0, 2, ',', '.') ?></div>
        <div><strong>Produto Mais Vendido:</strong> <?= $resumoDia['produto_top'] ?? '---' ?></div>
      </div>
    </div>
  </div>
</div>

<!-- Linha extra: Alertas -->
<div class="row">
  <div class="col-md-12">
    <div class="card mb-4 bg-dark text-light">
      <div class="card-header bg-warning">
        ⚠️ Alertas
      </div>
      <div class="card-body">
        <ul>
          <li>Produtos com estoque baixo: <?= $alertas['estoque_baixo'] ?? 0 ?></li>
          <li>Clientes com pagamentos pendentes: <?= $alertas['pendencias'] ?? 0 ?></li>
          <li>Contratos próximos do vencimento: <?= $alertas['contratos_vencendo'] ?? 0 ?></li>
        </ul>
      </div>
    </div>
  </div>
</div>

