// Inicialização do calendário Flatpickr
function initCalendario(datasPendentes, dataSelecionada) {
    flatpickr("#dataPedido", {
        locale: "pt",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d/m/Y",
        defaultDate: dataSelecionada,
        onDayCreate: function(dObj, dStr, fp, dayElem) {
            const data = dayElem.dateObj.toISOString().split('T')[0];
            if (datasPendentes.includes(data)) {
                dayElem.style.background = '#ffcccc';
                dayElem.title = 'Pedido não pago';
            }
        },
        onChange: function(sel, dateStr) {
            window.location = '?data=' + dateStr;
        }
    });
}

// Pesquisa dinâmica de clientes
function initPesquisaCliente() {
    document.getElementById('pesquisaCliente').addEventListener('keyup', function() {
        let filtro = this.value.toLowerCase();
        document.querySelectorAll('#formPedidos table tr').forEach(function(row, index) {
            if (index === 0) return; // ignora cabeçalho
            let nomeCliente = row.cells[0].innerText.toLowerCase();
            row.style.display = nomeCliente.includes(filtro) ? '' : 'none';
        });
    });
}

// Popup para descrição do valor variado
let clienteAtual = null;

function abrirPopupVariado(idCliente) {
    clienteAtual = idCliente;
    const campoHidden = document.getElementById('observacao_variado_' + idCliente);
    document.getElementById('descricaoVariado').value = campoHidden.value;
    document.getElementById('popupVariado').style.display = 'block';
}

function salvarDescricaoVariado() {
    if (clienteAtual) {
        const campoHidden = document.getElementById('observacao_variado_' + clienteAtual);
        campoHidden.value = document.getElementById('descricaoVariado').value;
    }
    fecharPopupVariado();
}

function fecharPopupVariado() {
    document.getElementById('popupVariado').style.display = 'none';
}

// Inicialização geral da tela de pedidos
function initPedidos(datasPendentes, dataSelecionada) {
    initCalendario(datasPendentes, dataSelecionada);
    initPesquisaCliente();
    filtrarClientes(); // reaplica filtro de cardgames ao carregar
}

function abrirRecibo(idPedido) {
    const modal = document.getElementById('modalRecibo');
    const iframe = document.getElementById('iframeRecibo');

    iframe.src = '/pedido/recibo/' + idPedido;
    modal.style.display = 'block';
}

function fecharRecibo() {
    const modal = document.getElementById('modalRecibo');
    const iframe = document.getElementById('iframeRecibo');

    modal.style.display = 'none';
    iframe.src = '';
}

function calcularTotal(clienteId) {
    let total = 0;

    // Somar produtos
    document.querySelectorAll(`input[data-cliente="${clienteId}"][type="number"]`).forEach(input => {
        const qtd = parseFloat(input.value) || 0;
        const preco = parseFloat(input.dataset.preco) || 0;
        total += qtd * preco;
    });

    // Somar variado
    const variadoInput = document.querySelector(`input[name="variado[${clienteId}]"]`);
    if (variadoInput) {
        let variado = parseFloat(variadoInput.value.replace(',', '.')) || 0;
        total += variado;
    }

    // Atualizar label
    const totalLabel = document.getElementById(`total_${clienteId}`);
    if (totalLabel) {
        totalLabel.textContent = "R$ " + total.toFixed(2).replace('.', ',');
    }

    calcularTotalRecebido();
}

// LABEL do PEDIDO que mostra o total recebido no DIA.
function calcularTotalRecebido() {
    let totalDia = 0;

    document.querySelectorAll('td[id^="total_"]').forEach(td => {
        const clienteId = td.id.replace('total_', '');
        const checkboxPago = document.querySelector(`input[name="pago[${clienteId}]"]`);

        if (checkboxPago && checkboxPago.checked) {
            const valorTexto = td.textContent.replace('R$', '').trim().replace('.', '').replace(',', '.');
            const valor = parseFloat(valorTexto) || 0;
            totalDia += valor;
        }
    });

    const totalRecebido = document.getElementById('totalRecebido');
    if (totalRecebido) {
        totalRecebido.textContent = "R$ " + totalDia.toFixed(2).replace('.', ',');
    }
}

// Atualiza hidden de cardgames
function atualizarHiddenCardgames() {
    const container = document.getElementById('cardgamesSelecionados');
    if (!container) return;

    container.innerHTML = '';
    document.querySelectorAll('input[name="cardgames[]"]:checked').forEach(cb => {
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'cardgamesSelecionados[]';
        hidden.value = cb.value;
        container.appendChild(hidden);
    });
}

// Filtro de clientes por cardgames
function filtrarClientes() {
    const selecionados = Array.from(document.querySelectorAll('input[name="cardgames[]"]:checked'))
                              .map(cb => cb.value);

    document.querySelectorAll('#formPedidos table tr').forEach((row, index) => {
        if (index === 0) return;

        const cardgamesCliente = row.dataset.cardgames ? row.dataset.cardgames.split(',') : [];

        if (selecionados.length === 0) {
            row.style.display = '';
        } else {
            const temCardgame = cardgamesCliente.some(cg => selecionados.includes(cg));
            row.style.display = temCardgame ? '' : 'none';
        }
    });
}

// EVENTOS QUE CARREGAM COM A PAGINA
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('input[type="number"], input[name^="variado"]').forEach(input => {
        input.addEventListener('input', () => {
            const clienteId = input.dataset.cliente;
            if (clienteId) {
                calcularTotal(clienteId);
            }
        });
    });

    document.querySelectorAll('input[name="cardgames[]"]').forEach(cb => {
        cb.addEventListener('change', () => {
            atualizarHiddenCardgames();
            filtrarClientes();
        });
    });

    document.getElementById('formPedidos').addEventListener('submit', atualizarHiddenCardgames);

    document.querySelectorAll('input[name^="pago"]').forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            calcularTotalRecebido();
        });
    });

    document.querySelectorAll('td[id^="total_"]').forEach(td => {
        const clienteId = td.id.replace('total_', '');
        calcularTotal(clienteId);
    });

    calcularTotalRecebido();
});

// Atualiza o hidden sempre que o calendário muda
document.getElementById('dataPedido').addEventListener('change', function() {
    document.getElementById('dataSelecionadaHidden').value = this.value;
});

// Inicializa o hidden com o valor atual do calendário
document.getElementById('dataSelecionadaHidden').value = document.getElementById('dataPedido').value;


