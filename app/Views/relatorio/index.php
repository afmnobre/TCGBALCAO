<div class="main-content pt-5">
  <div class="container-fluid py-4">

    <!-- Filtros -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <h4>Dashboard Financeiro</h4>
      <div class="mt-4 text-end">
        <button id="btnExportPDFTop" class="btn btn-primary">Exportar PDF</button>
      </div>
      <div class="d-flex gap-2">
        <select id="filtroAno" class="form-select w-auto">
          <?php for($y=2021;$y<=2026;$y++): ?>
            <option value="<?= $y ?>" <?= $y==2026?'selected':'' ?>><?= $y ?></option>
          <?php endfor; ?>
        </select>

        <select id="filtroMes" class="form-select w-auto">
          <option value="">Ano inteiro</option>
          <?php
          $meses = ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"];
          foreach($meses as $i=>$m): ?>
            <option value="<?= $i+1 ?>"><?= $m ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="bg-dark text-white p-4 rounded">
      <!-- KPIs -->
      <div class="row" id="kpis"></div>

      <!-- Gráfico Mensal -->
      <div class="card bg-secondary mt-4">
        <div class="card-body"><canvas id="graficoMensal"></canvas></div>
      </div>

      <!-- Relatório Detalhado Anual -->
      <div class="card bg-secondary mt-4">
        <div class="card-body">
          <h6>Relatório Detalhado Anual</h6>
          <div class="table-responsive">
            <table class="table table-dark table-striped mt-3" id="tblDesempenho">
              <thead>
                <tr>
                  <th>Mês</th>
                  <th>Média Valor / Dia</th>
                  <th>Média / Semana</th>
                  <th>Total Mês</th>
                  <th>Média / Pedido</th>
                  <th>Média / Cliente</th>
                  <th>Menor Pedido</th>
                  <th>Maior Pedido</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Top Clientes -->
      <div class="row mt-4 align-items-stretch">
        <div class="col-md-6 d-flex">
          <div class="card bg-secondary w-100">
            <div class="card-body d-flex flex-column">
              <h6>Top 5 Clientes</h6>
              <table class="table table-dark table-striped flex-grow-1 mb-0" id="tblClientes">
                <thead><tr><th>Cliente</th><th>Total</th></tr></thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-6 d-flex">
          <div class="card bg-secondary w-100">
            <div class="card-body d-flex align-items-center"><canvas id="graficoClientes" class="w-100"></canvas></div>
          </div>
        </div>
      </div>

      <!-- Top Produtos -->
      <div class="d-flex justify-content-end mb-2">
        <select id="filtroGraficoProdutos" class="form-select w-auto">
          <option value="quantidade">Quantidade</option>
          <option value="valor">Valor</option>
        </select>
      </div>

      <div class="row mt-4 align-items-stretch">
        <div class="col-md-6 d-flex">
          <div class="card bg-secondary w-100">
            <div class="card-body d-flex flex-column">
              <h6>Top 5 Produtos</h6>
              <table class="table table-dark table-striped flex-grow-1 mb-0" id="tblProdutos">
                <thead><tr><th>Produto</th><th>Qtd</th><th>Valor</th></tr></thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-6 d-flex">
          <div class="card bg-secondary w-100">
            <div class="card-body d-flex"><canvas id="graficoProdutos" class="w-100"></canvas></div>
          </div>
        </div>
      </div>

      <!-- Pagamentos -->
      <div class="row mt-4 align-items-stretch">
        <div class="col-md-6 d-flex">
          <div class="card bg-secondary w-100">
            <div class="card-body d-flex flex-column">
              <h6>Meios de Pagamento</h6>
              <table class="table table-dark table-striped flex-grow-1 mb-0" id="tblPagamentos">
                <thead><tr><th>Tipo</th><th>Total</th><th>%</th></tr></thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-6 d-flex">
          <div class="card bg-secondary w-100">
            <div class="card-body d-flex align-items-center"><canvas id="graficoPagamentos" class="w-100"></canvas></div>
          </div>
        </div>
      </div>
    </div>
  </div>

<script>
let graficoMensal, graficoPagamentos, graficoClientes, graficoProdutos;
let modoGraficoProdutos = 'quantidade';

function carregarDashboard(){
    const ano = document.getElementById('filtroAno').value;
    const mes = document.getElementById('filtroMes').value;

    fetch(`/relatorio/dados?ano=${ano}&mes=${mes}`)
        .then(res=>res.json())
        .then(data=>{
            if(typeof renderKPI==="function") renderKPI(data.metricas);
            if(typeof renderMensal==="function") renderMensal(data.comparativo);
            if(typeof renderDesempenho==="function") renderDesempenho(data.desempenho);
            if(typeof renderTopClientes==="function") renderTopClientes(data.topClientes);
            if(typeof renderTopProdutos==="function") renderTopProdutos(data.topProdutos);
            if(typeof renderPagamentos==="function") renderPagamentos(data.pagamentos);
        })
        .catch(err=>console.error('Erro ao carregar dashboard:',err));
}

/* Ajuste de altura dos gráficos */
function ajustarAlturaGrafico(idTabela, idCanvas) {
    const tabela = document.getElementById(idTabela);
    const canvas = document.getElementById(idCanvas);
    if(!tabela || !canvas) return;
    setTimeout(()=>canvas.style.height = tabela.offsetHeight + "px", 50);
}


/* KPIs */
/* KPIs */
function renderKPI(metricas){
    const kpiDiv = document.getElementById('kpis');
    if(!kpiDiv) return;
    kpiDiv.innerHTML = '';

    // Agrega os dados
    const totalAno = metricas.reduce((acc,m)=>acc+parseFloat(m.total_mes),0);
    const totalPedidos = metricas.reduce((acc,m)=>acc+parseInt(m.pedidos_mes),0);
    const ticketMedio = totalPedidos>0 ? (totalAno/totalPedidos).toFixed(2) : 0;

    // Cria 3 cards
    const cards = [
        {titulo:'Total do Ano', valor:totalAno.toFixed(2)},
        {titulo:'Total de Pedidos', valor:totalPedidos},
        {titulo:'Ticket Médio', valor:ticketMedio}
    ];

    cards.forEach(c=>{
        const col = document.createElement('div');
        col.className = "col-md-4 mb-3"; // 3 cards lado a lado
        col.innerHTML = `<div class="card bg-secondary p-3 text-center">
                            <h6>${c.titulo}</h6>
                            <h5>${c.valor}</h5>
                         </div>`;
        kpiDiv.appendChild(col);
    });
}


function renderTopClientes(clientes){
    const tbody = document.querySelector('#tblClientes tbody');
    if(!tbody) return;
    tbody.innerHTML = clientes.map(c=>
        `<tr>
            <td>${c.nome}</td>
            <td>${parseFloat(c.total).toLocaleString('pt-BR',{style:'currency',currency:'BRL'})}</td>
        </tr>`
    ).join('');
    ajustarAlturaGrafico('tblClientes','graficoClientes');

    if(graficoClientes) graficoClientes.destroy();
    graficoClientes = new Chart(document.getElementById('graficoClientes'),{
        type:'pie',
        data:{
            labels:clientes.map(c=>c.nome),
            datasets:[{
                data:clientes.map(c=>c.total),
                backgroundColor:['#FF0000','#00FF00','#0000FF','#FFFF00','#FF00FF']
            }]
        },
        options:{responsive:true,plugins:{legend:{display:true}}}
    });
}



/* Relatório Detalhado Anual */
function renderDesempenho(dados){
    const tbody = document.querySelector('#tblDesempenho tbody');
    if(!tbody) return;
    tbody.innerHTML = '';
    Object.keys(dados).forEach(mes=>{
        const d = dados[mes];
        tbody.innerHTML += `<tr>
            <td>${mes}</td>
            <td>${parseFloat(d.media_dia).toLocaleString('pt-BR',{style:'currency',currency:'BRL'})}</td>
            <td>${parseFloat(d.media_semana).toLocaleString('pt-BR',{style:'currency',currency:'BRL'})}</td>
            <td>${parseFloat(d.total_mes).toLocaleString('pt-BR',{style:'currency',currency:'BRL'})}</td>
            <td>${parseFloat(d.media_pedido).toLocaleString('pt-BR',{style:'currency',currency:'BRL'})}</td>
            <td>${parseFloat(d.media_cliente).toLocaleString('pt-BR',{style:'currency',currency:'BRL'})}</td>
            <td>${parseFloat(d.menor_pedido).toLocaleString('pt-BR',{style:'currency',currency:'BRL'})}</td>
            <td>${parseFloat(d.maior_pedido).toLocaleString('pt-BR',{style:'currency',currency:'BRL'})}</td>
        </tr>`;
    });
}


/* Top Produtos */
function renderTopProdutos(produtos){
    const tbody = document.querySelector('#tblProdutos tbody');
    if(!tbody) return;
    tbody.innerHTML = '';
    let labels=[], valores=[];
    produtos.forEach(p=>{
        tbody.innerHTML += `<tr>
            <td>${p.nome}</td>
            <td>${p.total_vendido}</td>
            <td>${parseFloat(p.total_valor).toLocaleString('pt-BR',{style:'currency',currency:'BRL'})}</td>
        </tr>`;
        labels.push(p.nome);
        valores.push(modoGraficoProdutos=='quantidade'?p.total_vendido:p.total_valor);
    });
    ajustarAlturaGrafico('tblProdutos','graficoProdutos');

    if(graficoProdutos) graficoProdutos.destroy();
    graficoProdutos = new Chart(document.getElementById('graficoProdutos'),{
        type:'pie',
        data:{labels,datasets:[{data:valores,backgroundColor:['#FF0000','#00FF00','#0000FF','#FFFF00','#FF00FF']}]},
        options:{responsive:true,plugins:{legend:{display:true}}}
    });
}



/* Pagamentos */
function renderPagamentos(pagamentos){
    const tbody = document.querySelector('#tblPagamentos tbody');
    if(!tbody) return;

    const totalGeral = pagamentos.reduce((acc,p)=>acc+parseFloat(p.total),0);
    tbody.innerHTML = pagamentos.map(p=>{
        const perc = totalGeral>0 ? ((p.total/totalGeral)*100).toFixed(1) : 0;
        return `<tr>
            <td>${p.nome}</td>
            <td>${parseFloat(p.total).toLocaleString('pt-BR',{style:'currency',currency:'BRL'})}</td>
            <td>${perc}%</td>
        </tr>`;
    }).join('');

    ajustarAlturaGrafico('tblPagamentos','graficoPagamentos');

    if(graficoPagamentos) graficoPagamentos.destroy();
    graficoPagamentos = new Chart(document.getElementById('graficoPagamentos'),{
        type:'pie',
        data:{labels:pagamentos.map(p=>p.nome),datasets:[{data:pagamentos.map(p=>p.total),backgroundColor:['#FF0000','#00FF00','#0000FF','#FFFF00']}]},
        options:{responsive:true}
    });
}


/* Exportar PDF */
window.exportPDF = function(){
    const element = document.querySelector('.container-fluid');
    if(!element) return;
    const opt = {
        margin:10,
        filename:`Relatorio_${new Date().getFullYear()}.pdf`,
        image:{type:'jpeg',quality:0.98},
        html2canvas:{scale:2,useCORS:true},
        jsPDF:{unit:'mm',format:'a4',orientation:'portrait'}
    };
    html2pdf().set(opt).from(element).save();
};

/* Eventos */
document.addEventListener('DOMContentLoaded',()=>{
    carregarDashboard();
    document.getElementById('filtroAno').addEventListener('change',carregarDashboard);
    document.getElementById('filtroMes').addEventListener('change',carregarDashboard);
    document.getElementById('filtroGraficoProdutos').addEventListener('change',e=>{
        modoGraficoProdutos=e.target.value;
        carregarDashboard();
    });

    const btnTop = document.getElementById('btnExportPDFTop');
    const btnBottom = document.getElementById('btnExportPDFBottom');
    if(btnTop) btnTop.addEventListener('click',exportPDF);
    if(btnBottom) btnBottom.addEventListener('click',exportPDF);
});

function renderMensal(dados){
    if(!dados || !Array.isArray(dados)) return;

    const anoAtual = new Date().getFullYear();
    const anoAnterior = anoAtual - 1;

    // Cria labels fixos de 1 a 12
    const mesesLabels = ["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"];

    // Inicializa arrays com 12 posições
    let valoresAnoAtual = new Array(12).fill(0);
    let valoresAnoAnterior = new Array(12).fill(0);

    dados.forEach(d=>{
        const idx = parseInt(d.mes)-1;
        if(parseInt(d.ano)===anoAtual) valoresAnoAtual[idx] = d.total;
        if(parseInt(d.ano)===anoAnterior) valoresAnoAnterior[idx] = d.total;
    });

    const ctx = document.getElementById('graficoMensal').getContext('2d');
    if(graficoMensal) graficoMensal.destroy();

    graficoMensal = new Chart(ctx,{
        type:'bar',
        data:{
            labels:mesesLabels,
            datasets:[
                {label:`${anoAtual}`,data:valoresAnoAtual,backgroundColor:'#FF0000'},
                {label:`${anoAnterior}`,data:valoresAnoAnterior,backgroundColor:'#00FF00'}
            ]
        },
        options:{
            responsive:true,
            plugins:{legend:{display:true}},
            scales:{x:{stacked:false},y:{beginAtZero:true}}
        }
    });
}



</script>

