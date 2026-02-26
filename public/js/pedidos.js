// Inicialização do calendário Flatpickr
function initCalendario(datasPendentes, dataSelecionada) {
    flatpickr("#dataPedido", {
        locale: "pt",
        dateFormat: "Y-m-d",       // valor interno usado para navegação (?data=YYYY-MM-DD)
        altInput: true,            // cria um campo alternativo para exibir
        altFormat: "d/m/Y",        // formato amigável para o usuário
        defaultDate: dataSelecionada,
        onDayCreate: function(dObj, dStr, fp, dayElem) {
            const data = dayElem.dateObj.toISOString().split('T')[0];
            if (datasPendentes.includes(data)) {
                dayElem.classList.add("has-pedido");
                dayElem.title = "Pedido não pago";
            }
        },
        onChange: function(selectedDates, dateStr, instance) {
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

    // Inicializa e abre o modal Bootstrap
    const modal = new bootstrap.Modal(document.getElementById('popupVariado'));
    modal.show();
}

function salvarDescricaoVariado() {
    if (clienteAtual) {
        const campoHidden = document.getElementById('observacao_variado_' + clienteAtual);
        campoHidden.value = document.getElementById('descricaoVariado').value;
    }

    // Fecha o modal Bootstrap
    const modalEl = document.getElementById('popupVariado');
    const modal = bootstrap.Modal.getInstance(modalEl);
    modal.hide();
}

// Inicialização geral da tela de pedidos
function initPedidos(datasPendentes, dataSelecionada) {
    initCalendario(datasPendentes, dataSelecionada);
    initPesquisaCliente();
    filtrarClientes(); // reaplica filtro de cardgames ao carregar
}

// recibos de PAGAMENTO
function abrirRecibo(idPedido) {
    const iframe = document.getElementById('iframeRecibo');
    iframe.src = '/pedido/recibo/' + idPedido;

    const modal = new bootstrap.Modal(document.getElementById('modalRecibo'));
    modal.show();
}

function fecharRecibo() {
    const modalEl = document.getElementById('modalRecibo');
    const iframe = document.getElementById('iframeRecibo');

    iframe.src = ''; // limpa o iframe
    const modal = bootstrap.Modal.getInstance(modalEl);
    if (modal) modal.hide();
}

function imprimirRecibo() {
    const iframe = document.getElementById('iframeRecibo');
    iframe.contentWindow.focus();
    iframe.contentWindow.print();
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
        let valor = variadoInput.value.replace(/\./g, '').replace(',', '.');
        total += parseFloat(valor) || 0;
    }

    // Atualizar label e data-total
    const totalLabel = document.getElementById(`total_${clienteId}`);
    if (totalLabel) {
        totalLabel.textContent = "R$ " + total.toFixed(2).replace('.', ',');
        totalLabel.dataset.total = total.toFixed(2); // <-- Atualiza o data-total
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
            let valorTexto = td.textContent.replace('R$', '').trim();
            // Remove todos os pontos (separador de milhar) e troca vírgula por ponto
            valorTexto = valorTexto.replace(/\./g, '').replace(',', '.');
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
    // Atualiza totais quando quantidade de produto ou valor variado muda
    document.querySelectorAll('input[type="number"], input[name^="variado"]').forEach(input => {
        input.addEventListener('input', () => {
            const clienteId = input.dataset.cliente;
            if (clienteId) {
                calcularTotal(clienteId);
            }
        });
        filtrarClientes();
    });

    // Filtro por cardgames
    document.querySelectorAll('input[name="cardgames[]"]').forEach(cb => {
        cb.addEventListener('change', () => {
            atualizarHiddenCardgames();
            filtrarClientes();
        });
    });

    // Atualiza hidden dos cardgames ao salvar pedidos
    const formPedidosEl = document.getElementById('formPedidos');
        if (formPedidosEl) {
            formPedidosEl.addEventListener('submit', atualizarHiddenCardgames);
        }

    // Atualiza total recebido quando checkbox de pago muda
    document.querySelectorAll('input[name^="pago"]').forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            calcularTotalRecebido();
        });
    });

    // Calcula totais iniciais por cliente
    document.querySelectorAll('td[id^="total_"]').forEach(td => {
        const clienteId = td.id.replace('total_', '');
        calcularTotal(clienteId);
    });

    // Calcula total geral inicial
    calcularTotalRecebido();

    // Pesquisa dinâmica por nome de cliente
    const inputPesquisa = document.getElementById("pesquisaCliente");
    if (inputPesquisa) {
        inputPesquisa.addEventListener("input", function() {
            const filtro = this.value.toLowerCase();

            document.querySelectorAll("table tbody tr").forEach(tr => {
                const nomeCliente = tr.querySelector("td").innerText.toLowerCase();
                tr.style.display = nomeCliente.includes(filtro) ? "" : "none";
            });
        });
    }
});


// Atualiza o hidden sempre que o calendário muda
const dataPedidoEl = document.getElementById('dataPedido');
if (dataPedidoEl) {
  dataPedidoEl.addEventListener('change', function() { document.getElementById('dataSelecionadaHidden').value = this.value;
 });
}

// Inicializa o hidden com o valor atual do calendário
const dataHidden = document.getElementById('dataSelecionadaHidden');
const dataPedido = document.getElementById('dataPedido');

if (dataHidden && dataPedido) {
    dataHidden.value = dataPedido.value;
}



// Model Pagamennto - Calculo de valores.
let totalPedido = 0;
window.abrirModalPagamento = function(idPedido, idCliente, valorTotal, checkboxEl) {
    const total = parseFloat(valorTotal) || 0;

    if (total <= 0) {
        alert("Este pedido não possui valor para rateio de pagamento.");
        if (checkboxEl) checkboxEl.checked = false; // desmarca se não houver valor
        return;
    }

    totalPedido = total;
    document.getElementById('totalPedido').textContent = totalPedido.toFixed(2);
    document.getElementById('valorRestante').textContent = totalPedido.toFixed(2);

    // IDs
    document.getElementById('modal_id_pedido').value = idPedido;
    document.getElementById('modal_id_cliente').value = idCliente;

    const form = document.getElementById('formPagamento');

    // Remove campos antigos
    form.querySelectorAll('input[name^="variado"], input[name^="observacao_variado"], input[name^="itens"]').forEach(el => el.remove());

    // Copia variado
    const campoVariado = document.querySelector(`input[name="variado[${idCliente}]"]`);
    if (campoVariado) {
        const hiddenVariado = document.createElement('input');
        hiddenVariado.type = 'hidden';
        hiddenVariado.name = `variado[${idCliente}]`;
        hiddenVariado.value = campoVariado.value;
        form.appendChild(hiddenVariado);
    }

    // Copia observação
    const campoObs = document.getElementById(`observacao_variado_${idCliente}`);
    if (campoObs) {
        const hiddenObs = document.createElement('input');
        hiddenObs.type = 'hidden';
        hiddenObs.name = `observacao_variado[${idCliente}]`;
        hiddenObs.value = campoObs.value;
        form.appendChild(hiddenObs);
    }

    // Copia itens
    document.querySelectorAll(`input[name^="itens[${idCliente}]"]`).forEach(input => {
        const clone = document.createElement('input');
        clone.type = 'hidden';
        clone.name = input.name;
        clone.value = input.value;
        form.appendChild(clone);
    });

    // --- NOVAS FUNCIONALIDADES DE RATEIO ---
    totalPedido = parseFloat(valorTotal);
    document.getElementById('totalPedido').textContent = totalPedido.toFixed(2);
    document.getElementById('valorRestante').textContent = totalPedido.toFixed(2);

    // Resetar valores e checkboxes
    document.querySelectorAll('.pagamento-valor').forEach(el => el.value = "0.00");
    document.querySelectorAll('.pagamento-check').forEach(el => el.checked = false);

    // Colocar valor total no campo de Crédito e marcar o checkbox
    const campoCredito = document.querySelector('.pagamento-valor[data-id="credito"]');
    const checkCredito = document.querySelector('.pagamento-check[data-id="credito"]');
    if (campoCredito) campoCredito.value = totalPedido.toFixed(2);
    if (checkCredito) checkCredito.checked = true;

    // Abre modal Bootstrap
    const modal = new bootstrap.Modal(document.getElementById('modalPagamento'));
    modal.show();
}


// Atualiza restante sempre que valores mudam
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('pagamento-valor')) {
        atualizarRestante();
    }
});

function atualizarRestante() {
    let soma = 0;
    document.querySelectorAll('.pagamento-valor').forEach(el => {
        soma += parseFloat(el.value) || 0;
    });
    let restante = totalPedido - soma;
    document.getElementById('valorRestante').textContent = restante.toFixed(2);
}

// Checkbox divide automaticamente e redistribui ao desmarcar
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('pagamento-check')) {
        const selecionados = Array.from(document.querySelectorAll('.pagamento-check:checked'));
        const desmarcado = !e.target.checked;

        if (desmarcado) {
            // Zera o campo do pagamento desmarcado
            const campo = document.querySelector(`.pagamento-valor[data-id="${e.target.dataset.id}"]`);
            if (campo) campo.value = "0.00";
        }

        if (selecionados.length > 0) {
            // Divide o total entre os selecionados
            let valorDividido = totalPedido / selecionados.length;

            // Arredondar para 2 casas decimais
            valorDividido = Math.round(valorDividido * 100) / 100;

            // Distribuir valores
            let soma = 0;
            selecionados.forEach((chk, index) => {
                const campo = document.querySelector(`.pagamento-valor[data-id="${chk.dataset.id}"]`);
                if (campo) {
                    // Último campo recebe o ajuste para fechar o total
                    if (index === selecionados.length - 1) {
                        campo.value = (totalPedido - soma).toFixed(2);
                    } else {
                        campo.value = valorDividido.toFixed(2);
                        soma += valorDividido;
                    }
                }
            });
        }

        atualizarRestante();
    }
});

// Botão "Distribuir restante"
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('distribuir-btn')) {
        const restante = parseFloat(document.getElementById('valorRestante').textContent);
        const campo = document.querySelector(`.pagamento-valor[data-id="${e.target.dataset.id}"]`);
        if (campo) {
            campo.value = (parseFloat(campo.value) + restante).toFixed(2);
            atualizarRestante();
        }
    }
});

//Alerta para o MODAL VARIADO
function mostrarAlertaVariado(callback) {
    const alertBox = document.getElementById('alertVariado');
    alertBox.style.display = 'block';

    // Esconde automaticamente depois de alguns segundos
    setTimeout(() => {
        alertBox.style.display = 'none';
        if (typeof callback === 'function') {
            callback(); // abre o modal depois que o alerta some
        }
    }, 3000); // tempo visível
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('input[name^="variado"]').forEach(input => {
        input.addEventListener('blur', function() {
            let valor = this.value.replace(/\./g, '').replace(',', '.');
            let num = parseFloat(valor) || 0;

            if (num > 0) {
                mostrarAlertaVariado(() => {
                    abrirPopupVariado(this.dataset.cliente);
                });
            }
        });
    });
});

function salvarPagamento() {
    const form = document.getElementById('formPagamento');

    // Remove campos antigos de pagamento
    form.querySelectorAll('input[name^="pagamento"]').forEach(el => el.remove());

    // Copia os valores dos métodos de pagamento
    document.querySelectorAll('.pagamento-valor').forEach(input => {
        const idPagamento = input.dataset.id;
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = `pagamento[${idPagamento}]`;
        hidden.value = input.value;
        form.appendChild(hidden);
    });

    // 🔹 Copia também os cardgames selecionados para o form do modal
    form.querySelectorAll('input[name="cardgamesSelecionados[]"]').forEach(el => el.remove()); // limpa antigos
    document.querySelectorAll('input[name="cardgames[]"]:checked').forEach(cb => {
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'cardgamesSelecionados[]';
        hidden.value = cb.value;
        form.appendChild(hidden);
    });

    form.submit();
}



document.addEventListener('DOMContentLoaded', () => {
    const modalPagamento = document.getElementById('modalPagamento');
    if (modalPagamento) {
        modalPagamento.addEventListener('hidden.bs.modal', () => {
            filtrarClientes();
        });
    }
});


