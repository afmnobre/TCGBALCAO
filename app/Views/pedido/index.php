<h2>Pedidos do Balcão</h2>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <div>
        <label>Data:</label>
        <input type="text" id="dataPedido" value="<?= $dataSelecionada ?>">
    </div>
    <div>
        <label>Pesquisar Cliente:</label>
        <input type="text" id="pesquisaCliente" placeholder="Digite o nome...">
    </div>
    <div>
        <label>Total Recebido no Dia:</label>
        <span id="totalRecebido">R$ 0,00</span>
    </div>
</div>

<!-- Filtros de Cardgames -->
<div class="filtro-cardgames">
    <strong>Filtrar por Cardgames:</strong>
    <?php foreach ($cardgames as $cardgame): ?>
        <?php $checked = in_array($cardgame['id_cardgame'], ($_GET['cardgames'] ?? [])) ? 'checked' : ''; ?>
        <label class="cardgame-item">
            <input type="checkbox"
                   name="cardgames[]"
                   value="<?= $cardgame['id_cardgame'] ?>"
                   <?= $checked ?>
                   onchange="filtrarClientes()">
            <div class="cardgame-thumb"
                 style="background-image: url('/public/images/cartas_fundo/<?= htmlspecialchars($cardgame['imagem_fundo_card']) ?>');">
                <span class="cardgame-name"><?= htmlspecialchars($cardgame['nome']) ?></span>
            </div>
        </label>
    <?php endforeach; ?>
</div>

<button type="submit" form="formPedidos" style="background:red; color:white; padding:10px 20px; border:none; cursor:pointer;">
    Salvar Pedidos
</button>

<form id="formPedidos" method="POST" action="/pedido/salvar">
    <!-- Hidden para enviar a data selecionada -->
    <input type="hidden" name="dataSelecionada" id="dataSelecionadaHidden" value="<?= $dataSelecionada ?>">

    <!-- Hidden para manter os cardgames selecionados -->
    <div id="cardgamesSelecionados"></div>

    <table border="1" cellpadding="5" cellspacing="0" style="width:100%; margin-top:20px;">
        <tr>
            <th>Cliente</th>
            <?php foreach ($produtos as $produto): ?>
                <?php
                    $classeEstoque = '';
                    $estoqueAtual   = (int)($produto['estoque_atual'] ?? 0);
                    $estoqueAlerta  = (int)($produto['estoque_alerta'] ?? 0);
                    $controlar      = (int)($produto['controlar_estoque'] ?? 0);

                    // Se controla estoque e está abaixo ou igual ao alerta → destaque
                    if ($controlar === 1 && $estoqueAtual <= $estoqueAlerta) {
                        $classeEstoque = 'estoque-alerta';
                    }
                ?>
                <th class="<?= $classeEstoque ?>"> <?= htmlspecialchars($produto['emoji']) ?>  <?= htmlspecialchars($produto['nome']) ?></th>
            <?php endforeach; ?>
            <th>Variado</th>
            <th>Total</th>
            <th>Pago?</th>
            <th>Recibo</th>
        </tr>

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
                               value="<?= $valorItem ?>" min="0" style="width:60px;"
                               data-preco="<?= $produto['valor_venda'] ?>"
                               data-cliente="<?= $cliente['id_cliente'] ?>">
                    </td>
                <?php endforeach; ?>

                <td>
                    <input type="text"
                           name="variado[<?= $cliente['id_cliente'] ?>]"
                           value="<?= $pedidoCliente ? number_format($pedidoCliente['valor_variado'],2,',','.') : '0,00' ?>"
                           style="width:80px;"
                           data-cliente="<?= $cliente['id_cliente'] ?>">
                    <button type="button" onclick="abrirPopupVariado(<?= $cliente['id_cliente'] ?>)">📝</button>
                </td>
<td id="total_<?= $cliente['id_cliente'] ?>" class="<?= $cliente['classe_total'] ?>">
    <?= $pedidoCliente ? 'R$ '.number_format($pedidoCliente['valor_variado'],2,',','.') : 'R$ 0,00' ?>
</td>




                <td>
                    <input type="checkbox"
                           name="pago[<?= $cliente['id_cliente'] ?>]"
                           <?= ($pedidoCliente && $pedidoCliente['pedido_pago'] == 1) ? 'checked' : '' ?>>
                </td>

                <td style="text-align:center">
                    <?php if ($pedidoCliente && $pedidoCliente['pedido_pago'] == 1): ?>
                        <a href="#"
                           onclick="abrirRecibo(<?= (int)$pedidoCliente['id_pedido'] ?>); return false;"
                           title="Abrir recibo do pedido">
                           🧾
                        </a>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</form>

<!-- Popup variado -->
<div id="popupVariado" style="display:none; position:fixed; top:20%; left:30%; background:#fff; border:1px solid #ccc; padding:20px;">
    <h3>Descrição do Valor Variado</h3>
    <textarea id="descricaoVariado" rows="5" cols="40"></textarea><br><br>
    <button onclick="salvarDescricaoVariado()">Salvar</button>
    <button onclick="fecharPopupVariado()">Fechar</button>
</div>

<!-- Modal recibo -->
<div id="modalRecibo" style="display:none; position:fixed; top:5%; left:50%; transform:translateX(-50%);
     background:#fff; border:1px solid #ccc; padding:10px; width:460px; height:680px; z-index:9999;">
    <div style="text-align:right;">
        <button onclick="fecharRecibo()">Fechar ✖</button>
    </div>
    <iframe id="iframeRecibo" src="" style="width:100%; height:95%; border:none;"></iframe>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="/public/css/pedido.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="/public/js/pedidos.js"></script>

<script>
    initPedidos(
        <?= json_encode($datasPendentes) ?>,
        "<?= $dataSelecionada ?>"
    );
</script>


