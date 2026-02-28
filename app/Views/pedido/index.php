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
    <div class="alert w-100 mb-0" style="background-color: #000F00; color: #fff; border: 1px solid #00d400; font-weight: bold;">
      <strong>Total Recebido no Dia:</strong> <span id="totalRecebido">R$ 0,00</span>
    </div>
  </div>
</div>

<!-- Filtros de Cardgames -->
<div class="bg-dark py-3 px-3 w-100 border-bottom border-secondary">
  <div class="container-fluid d-flex align-items-center justify-content-between">

    <div class="d-flex align-items-center flex-grow-1 overflow-hidden">
      <strong class="text-light me-3 text-nowrap">Filtrar por Cardgames:</strong>

      <div class="d-flex flex-row align-items-center flex-nowrap custom-scroll flex-grow-1"
           style="gap: 12px; overflow-x: auto; padding: 10px 0; scrollbar-width: none;">

        <?php foreach ($cardgames as $cardgame): ?>
          <?php $checked = in_array((string)$cardgame['id_cardgame'], array_map('strval', ($_GET['cardgames'] ?? []))) ? 'checked' : ''; ?>

          <label class="magic-card p-0 m-0 flex-shrink-0">
            <input class="magic-check"
                   type="checkbox"
                   name="cardgames[]"
                   value="<?= $cardgame['id_cardgame'] ?>"
                   <?= $checked ?>
                   onchange="filtrarClientes()">

            <img src="<?= $baseAssetUrl ?>/storage/uploads/cardgames/<?= $cardgame['id_cardgame'] ?>/<?= htmlspecialchars($cardgame['imagem_fundo_card']) ?>"
                 alt="<?= htmlspecialchars($cardgame['nome']) ?>"
                 class="img-fluid"> <div class="card-overlay"></div>
          </label>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="ms-4 flex-shrink-0">
      <button type="submit" form="formPedidos" class="btn btn-success px-4 fw-bold text-nowrap">
        💾 Salvar Pedidos
      </button>
    </div>

  </div>
</div>

<form id="formPedidos" method="POST" action="/pedido/salvar">
  <input type="hidden" name="dataSelecionada" id="dataSelecionadaHidden" value="<?= $dataSelecionada ?>">
    <!-- container para os hidden inputs de cardgames -->
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

			// Se o estoque estiver baixo, aplica o vermelho vivo
			if ($controlar === 1 && $estoqueAtual <= $estoqueAlerta) {
				$classeEstoque = 'estoque-alerta-vivo';
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
              <div class="input-group input-group-sm" style="min-width: 120px;">
                <span class="input-group-text bg-secondary text-white border-secondary">R$</span>
				<input type="text"
					   name="variado[<?= $cliente['id_cliente'] ?>]"
					   value="<?= number_format((float)($pedidoCliente['valor_variado'] ?? 0), 2, ',', '.') ?>"
					   class="form-control bg-dark text-light border-secondary text-center"
					   style="width: 55px;"
					   data-cliente="<?= $cliente['id_cliente'] ?>">

				<button class="btn btn-outline-secondary py-0 px-2"
						type="button"
						onclick="abrirPopupVariado(<?= $cliente['id_cliente'] ?>)">
				  📝
				</button>
			  </div>
			</td>
			<td id="total_<?= $cliente['id_cliente'] ?>"
				class="<?php
					$temValor = false;
					$valorVariado = (float)($pedidoCliente['valor_variado'] ?? 0);

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

					// Classes de estrutura e texto
					if ($temValor && ($pedidoCliente['pedido_pago'] ?? 0) == 0) {
						echo 'text-center fw-bold text-white';
					} elseif (($pedidoCliente['pedido_pago'] ?? 0) == 1) {
						echo 'text-center fw-bold text-white';
					} else {
						echo 'table-dark text-center';
					}
				?>"
				style="<?php
					if ($temValor && ($pedidoCliente['pedido_pago'] ?? 0) == 0) {
						// O box-shadow: none mata o efeito de listra (striped) do Bootstrap na célula
						echo 'background-color: #ff0000 !important; color: #ffffff !important; box-shadow: none !important;';
					} elseif (($pedidoCliente['pedido_pago'] ?? 0) == 1) {
						echo 'background-color: #28a745 !important; color: #ffffff !important; box-shadow: none !important;';
					}
				?>"
				data-total="<?php
					$valorItens = 0;
					if (!empty($pedidoCliente['itens'])) {
						foreach ($pedidoCliente['itens'] as $item) {
							$valorItens += $item['quantidade'] * ($item['valor_unitario'] ?? 0);
						}
					}
					$valorTotal = $valorItens + $valorVariado;
					echo $valorTotal;
				?>">
				<?= 'R$ ' . number_format($valorTotal, 2, ',', '.') ?>
			</td>
            <td>
				<input type="checkbox" name="pago[<?= $cliente['id_cliente'] ?>]"
					   onchange="abrirModalPagamento(
						   <?= $pedidoCliente['id_pedido'] ?? 0 ?>,
						   <?= $cliente['id_cliente'] ?>,
						   document.getElementById('total_<?= $cliente['id_cliente'] ?>').dataset.total,
						   this
					   )"
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

<div id="alertVariado" class="custom-alert" style="display:none;">
  <p>⚠️ Não esqueça de colocar a Observação do Valor Variado!</p>
</div>

<!-- Modal Método de Pagamento -->
<div class="modal fade" id="modalPagamento" tabindex="-1" aria-labelledby="modalPagamentoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header">
        <h5 class="modal-title" id="modalPagamentoLabel">Método de Pagamento</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <form id="formPagamento" method="POST" action="/pedido/salvarPagamento">
          <input type="hidden" id="modal_id_pedido" name="id_pedido" value="">
          <input type="hidden" id="modal_id_cliente" name="id_cliente" value="">
          <input type="hidden" name="dataSelecionada" value="<?= $dataSelecionada ?>">

          <!-- Label com total do pedido -->
          <div class="mb-3">
            <label class="fw-bold">Total do Pedido: R$ <span id="totalPedido"></span></label><br>
            <label class="fw-bold">Valor restante a distribuir: R$ <span id="valorRestante"></span></label>
          </div>

          <!-- Tabela 2x2 de tipos de pagamento -->
          <table class="table table-dark table-bordered align-middle">
            <thead>
              <tr>
                <th>Tipo de Pagamento</th>
                <th>Valor</th>
                <th>Selecionar</th>
                <th>Ação</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($tipos_pagamento as $tp): ?>
              <tr>
                <td><?= htmlspecialchars($tp['nome']) ?></td>
                <td>
                  <input type="number" step="0.01" min="0"
                         class="form-control pagamento-valor"
                         name="valor[<?= $tp['id_pagamento'] ?>]"
                         data-id="<?= $tp['id_pagamento'] ?>"
                         value="0.00">
                </td>
                <td>
                  <input type="checkbox" class="form-check-input pagamento-check"
                         data-id="<?= $tp['id_pagamento'] ?>">
                </td>
                <td>
                  <button type="button" class="btn btn-sm btn-info distribuir-btn"
                          data-id="<?= $tp['id_pagamento'] ?>">Distribuir restante</button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
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

