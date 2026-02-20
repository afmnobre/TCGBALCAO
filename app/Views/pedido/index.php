<h2 class="text-lght mb-4">Pedidos do Balcão</h2>

<!-- Filtros principais -->
<div class="row mb-3">
  <div class="col-md-3">
    <label class="form-label text-light">Data:</label>
    <input type="text" id="dataPedido" value="<?= $dataSelecionada ?>" class="form-control bg-dark text-light border-secondary">
  </div>
  <div class="col-md-4">
        <label class="form-label text-light">Pesquisar Cliente:</label>
    <input type="text" id="pesquisaCliente" placeholder="Digite o nome..." class="form-control bg-dark text-light border-secondary">
  </div>
  <div class="col-md-5 d-flex align-items-end">
    <div class="alert alert-secondary w-100 mb-0">
      <strong>Total Recebido no Dia:</strong> <span id="totalRecebido">R$ 0,00</span>
    </div>
  </div>
</div>

<!-- Filtros de Cardgames -->
<div class="bg-dark py-3 px-2 w-100">
  <div class="container-fluid">
    <strong class="text-light">Filtrar por Cardgames:</strong>
    <div class="d-flex flex-wrap gap-2 mt-2">
      <?php foreach ($cardgames as $cardgame): ?>
        <?php $checked = in_array($cardgame['id_cardgame'], ($_GET['cardgames'] ?? [])) ? 'checked' : ''; ?>

        <div class="card border-0 bg-transparent text-light" style="width: 100px; height: 80px; position: relative;">
          <img src="/public/images/cartas_fundo/<?= htmlspecialchars($cardgame['imagem_fundo_card']) ?>"
               alt="<?= htmlspecialchars($cardgame['nome']) ?>"
               class="card-img"
               style="width: 100%; height: 100%; object-fit: cover;">
          <div class="card-img-overlay d-flex flex-column justify-content-center align-items-center p-0">
            <input class="form-check-input mb-1"
                   type="checkbox"
                   name="cardgames[]"
                   value="<?= $cardgame['id_cardgame'] ?>"
                   <?= $checked ?>
                   onchange="filtrarClientes()">
            <small class="fw-bold"><?= htmlspecialchars($cardgame['nome']) ?></small>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<br>

<!-- Botão salvar -->
<button type="submit" form="formPedidos" class="btn btn-success mb-3">
  💾 Salvar Pedidos
</button>

<form id="formPedidos" method="POST" action="/pedido/salvar">
  <input type="hidden" name="dataSelecionada" id="dataSelecionadaHidden" value="<?= $dataSelecionada ?>">
  <div id="cardgamesSelecionados"></div>

  <!-- Tabela de pedidos -->
  <div class="table-responsive">
    <table class="table table-dark table-striped align-middle">
      <thead>
        <tr>
          <th>Cliente</th>
          <?php foreach ($produtos as $produto): ?>
            <?php
              $classeEstoque = '';
              $estoqueAtual   = (int)($produto['estoque_atual'] ?? 0);
              $estoqueAlerta  = (int)($produto['estoque_alerta'] ?? 0);
              $controlar      = (int)($produto['controlar_estoque'] ?? 0);

              if ($controlar === 1 && $estoqueAtual <= $estoqueAlerta) {
                  $classeEstoque = 'table-danger';
              }
            ?>
            <th class="<?= $classeEstoque ?>">
              <?= htmlspecialchars($produto['emoji']) ?> <?= htmlspecialchars($produto['nome']) ?>
            </th>
          <?php endforeach; ?>
          <th>💰 Variado</th>
          <th>Total</th>
          <th>Pago?</th>
          <th>Recibo</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($clientes as $cliente): ?>
          <?php $pedidoCliente = $pedidosPorCliente[$cliente['id_cliente']][0] ?? null; ?>
          <tr data-cardgames="<?= implode(',', $cliente['cardgames']) ?>">
            <td><?= htmlspecialchars($cliente['nome']) ?></td>

            <?php if ($pedidoCliente): ?>
              <input type="hidden" name="id_pedido[<?= $cliente['id_cliente'] ?>]" value="<?= $pedidoCliente['id_pedido'] ?>">
            <?php endif; ?>
            <input type="hidden" name="observacao_variado[<?= $cliente['id_cliente'] ?>]"
                   id="observacao_variado_<?= $cliente['id_cliente'] ?>"
                   value="<?= $pedidoCliente['observacao_variado'] ?? '' ?>">

            <?php foreach ($produtos as $produto): ?>
              <?php
                $valorItem = 0;
                if ($pedidoCliente && !empty($pedidoCliente['itens'])) {
                  foreach ($pedidoCliente['itens'] as $item) {
                    if ($item['id_produto'] == $produto['id_produto']) {
                      $valorItem = $item['quantidade'];
                      break;
                    }
                  }
                }
              ?>
              <td>
                <input type="number"
                       name="itens[<?= $cliente['id_cliente'] ?>][<?= $produto['id_produto'] ?>]"
                       value="<?= $valorItem ?>" min="0"
                       class="form-control form-control-sm bg-dark text-light border-secondary"
                       style="width:70px;"
                       data-preco="<?= $produto['valor_venda'] ?>"
                       data-cliente="<?= $cliente['id_cliente'] ?>">
              </td>
            <?php endforeach; ?>

            <td>
              <div class="input-group input-group-sm">
                <input type="text"
                       name="variado[<?= $cliente['id_cliente'] ?>]"
                       value="<?= number_format((float)($pedidoCliente['valor_variado'] ?? 0), 2, ',', '.') ?>"
                       class="form-control bg-dark text-light border-secondary"
                       data-cliente="<?= $cliente['id_cliente'] ?>">
                  <button type="button" onclick="abrirPopupVariado(<?= $cliente['id_cliente'] ?>)">📝</button>
              </div>
            </td>
            <td id="total_<?= $cliente['id_cliente'] ?>"
                class="<?php
                    $temValor = false;
                    $valorVariado = (float)($pedidoCliente['valor_variado'] ?? 0);

                    // Verifica se tem itens ou valor variado
                    if ($valorVariado > 0) {
                        $temValor = true;
                    } elseif (!empty($pedidoCliente['itens'])) {
                        foreach ($pedidoCliente['itens'] as $item) {
                            if ($item['quantidade'] > 0) {
                                $temValor = true;
                                break;
                            }
                        }
                    }

                    // Define a classe visual do Bootstrap
                    if ($temValor && ($pedidoCliente['pedido_pago'] ?? 0) == 0) {
                        echo 'table-danger text-center fw-bold'; // vermelho: tem valor mas não pago
                    } elseif (($pedidoCliente['pedido_pago'] ?? 0) == 1) {
                        echo 'table-success text-center fw-bold'; // verde: pago
                    } else {
                        echo 'table-dark text-center'; // padrão escuro
                    }
                ?>">
                <?= $pedidoCliente
                    ? 'R$ '.number_format($pedidoCliente['valor_variado'],2,',','.')
                    : 'R$ 0,00' ?>
                </td>

            <td>
              <input type="checkbox"
                     name="pago[<?= $cliente['id_cliente'] ?>]"
                     onchange="abrirModalPagamento(<?= $pedidoCliente['id_pedido'] ?? 0 ?>, <?= $cliente['id_cliente'] ?>)"
                     <?= ($pedidoCliente && $pedidoCliente['pedido_pago'] == 1) ? 'checked' : '' ?>>
            </td>
            <td class="text-center">
              <?php if ($pedidoCliente && $pedidoCliente['pedido_pago'] == 1): ?>
                <a href="#"
                   onclick="abrirRecibo(<?= (int)$pedidoCliente['id_pedido'] ?>); return false;"
                   title="Abrir recibo do pedido">🧾</a>
              <?php else: ?>
                —
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</form>


<!-- Modal Observação Valor Variado -->
<div class="modal fade" id="popupVariado" tabindex="-1" aria-labelledby="observacaoVariadoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header">
        <h5 class="modal-title" id="observacaoVariadoLabel">Descrição do Valor Variado</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <textarea id="descricaoVariado" class="form-control bg-dark text-light border-secondary" rows="5"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" onclick="salvarDescricaoVariado()">Salvar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Método de Pagamento -->
<div class="modal fade" id="modalPagamento" tabindex="-1" aria-labelledby="modalPagamentoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header">
        <h5 class="modal-title" id="modalPagamentoLabel">Método de Pagamento</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <form id="formPagamento" method="POST" action="/pedido/salvarPagamento">
          <input type="hidden" id="modal_id_pedido" name="id_pedido[]" value="">
          <input type="hidden" id="modal_id_cliente" name="id_cliente" value="">
          <input type="hidden" name="dataSelecionada" value="<?= $dataSelecionada ?>">

          <!-- Tipos de pagamento -->
          <div class="d-flex flex-wrap gap-2">
            <?php foreach ($tipos_pagamento as $tp): ?>
              <label class="form-check-label">
                <input type="checkbox" class="form-check-input me-1" name="pagamentos[]" value="<?= $tp['id_pagamento'] ?>">
                <?= htmlspecialchars($tp['nome']) ?>
              </label>
            <?php endforeach; ?>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="salvarPagamento()">Salvar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Recibo -->
<div class="modal fade" id="modalRecibo" tabindex="-1" aria-labelledby="modalReciboLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 480px;">
    <div class="modal-content">
      <div class="modal-header bg-dark text-light">
        <h5 class="modal-title" id="modalReciboLabel">Recibo do Pedido</h5>
        <div class="ms-auto">
          <button type="button" class="btn btn-outline-light btn-sm me-2" onclick="imprimirRecibo()">🖨️ Imprimir</button>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
      </div>
      <div class="modal-body p-0">
        <iframe id="iframeRecibo" src="" style="width:100%; height:640px; border:none;"></iframe>
      </div>
    </div>
  </div>
</div>

