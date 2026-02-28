<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="text-light">Dashboard Financeiro</h2>
  <button id="btnExportPDFTop" class="btn btn-primary btn-sm">📊 Exportar Planilha</button>
</div>

	<div class="card bg-dark border-secondary mb-4">
		<div class="card-body">
			<form method="GET" action="/relatorio" class="row g-3 align-items-end">
				<div class="col-md-4">
					<label class="form-label text-light">Mês</label>
					<select name="mes" id="filtroMes" class="form-select bg-dark text-light border-secondary">
						<option value="0" <?= ($mes_selecionado == 0) ? 'selected' : '' ?>>Todos os Meses</option>
						<?php
						$meses = [1=>'Janeiro', 2=>'Fevereiro', 3=>'Março', 4=>'Abril', 5=>'Maio', 6=>'Junho', 7=>'Julho', 8=>'Agosto', 9=>'Setembro', 10=>'Outubro', 11=>'Novembro', 12=>'Dezembro'];
						foreach($meses as $num => $nome):
							$sel = ($num == $mes_selecionado) ? 'selected' : '';
						?>
							<option value="<?= $num ?>" <?= $sel ?>><?= $nome ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="col-md-4">
					<label class="form-label text-light">Ano</label>
					<select name="ano" id="filtroAno" class="form-select bg-dark text-light border-secondary">
						<?php
						$anos = $anos_disponiveis ?? [date('Y')];
						foreach($anos as $a):
							$selAno = ($a == ($ano_selecionado ?? date('Y'))) ? 'selected' : '';
						?>
							<option value="<?= $a ?>" <?= $selAno ?>><?= $a ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="col-md-4">
					<button type="submit" class="btn btn-primary w-100">📊 Filtrar Relatório</button>
				</div>
			</form>
		</div>
	</div>


    <div class="bg-dark text-white p-4 rounded">
      <div class="row" id="kpis"></div>

      <div class="card bg-secondary mt-4">
        <div class="card-body">
            <h6>Comparativo Mensal</h6>
            <div style="height: 300px; width: 100%;">
                <canvas id="graficoMensal"></canvas>
            </div>
        </div>
      </div>

      <div class="card bg-secondary mt-4">
        <div class="card-body">
          <h6>Relatório Detalhado Anual</h6>
          <div class="table-responsive">
            <table class="table table-dark table-striped mt-3" id="tblDesempenho">
              <thead>
                <tr>
                  <th>Mês</th><th>Média/Dia</th><th>Média/Semana</th><th>Total Mês</th><th>Média/Ped</th><th>Média/Cli</th><th>Menor</th><th>Maior</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="row mt-4 align-items-stretch">
        <div class="col-md-6 d-flex">
          <div class="card bg-secondary w-100">
            <div class="card-body">
              <h6>Top 5 Clientes</h6>
              <table class="table table-dark table-striped table-ajustada" id="tblClientes">
                <thead><tr><th>Cliente</th><th>Total</th></tr></thead><tbody></tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-6 d-flex">
          <div class="card bg-secondary w-100">
            <div class="card-body d-flex align-items-center justify-content-center card-grafico">
              <div style="width: 100%; position: relative;"><canvas id="graficoClientes"></canvas></div>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-end mb-2 mt-4">
        <select id="filtroGraficoProdutos" class="form-select w-auto">
          <option value="quantidade">Quantidade</option><option value="valor">Valor</option>
        </select>
      </div>

      <div class="row align-items-stretch">
        <div class="col-md-6 d-flex">
          <div class="card bg-secondary w-100">
            <div class="card-body">
              <h6>Top 5 Produtos</h6>
              <table class="table table-dark table-striped table-ajustada" id="tblProdutos">
                <thead><tr><th>Produto</th><th>Qtd</th><th>Valor</th></tr></thead><tbody></tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-6 d-flex">
          <div class="card bg-secondary w-100">
            <div class="card-body d-flex align-items-center justify-content-center card-grafico">
              <div style="width: 100%; position: relative;"><canvas id="graficoProdutos"></canvas></div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-4 align-items-stretch">
        <div class="col-md-6 d-flex">
          <div class="card bg-secondary w-100">
            <div class="card-body">
              <h6>Meios de Pagamento</h6>
              <table class="table table-dark table-striped table-ajustada" id="tblPagamentos">
                <thead><tr><th>Tipo</th><th>Total</th><th>%</th></tr></thead><tbody></tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-6 d-flex">
          <div class="card bg-secondary w-100">
            <div class="card-body d-flex align-items-center justify-content-center card-grafico">
              <div style="width: 100%; position: relative;"><canvas id="graficoPagamentos"></canvas></div>
            </div>
          </div>
        </div>
      </div>
    </div>
<br><br>

<script>
let graficoMensal, graficoPagamentos, graficoClientes, graficoProdutos;
let modoGraficoProdutos = 'quantidade';
const coresPalette = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#6f42c1', '#fd7e14'];

function carregarDashboard(){
    const ano = document.getElementById('filtroAno').value;
    const mes = document.getElementById('filtroMes').value;
    fetch(`/relatorio/dados?ano=${ano}&mes=${mes}`)
        .then(res=>res.json())
        .then(data=>{
            renderKPI(data.metricas);
            renderMensal(data.comparativo);
            renderDesempenho(data.desempenho);
            renderTopClientes(data.topClientes);
            renderTopProdutos(data.topProdutos);
            renderPagamentos(data.pagamentos);
        }).catch(err=>console.error('Erro ao carregar dashboard:',err));
}

function ajustarAlturaGrafico(idTabela, idCanvas) {
    const tabela = document.getElementById(idTabela);
    const canvas = document.getElementById(idCanvas);
    if(!tabela || !canvas) return;
    setTimeout(() => {
        const altura = tabela.offsetHeight;
        canvas.parentElement.style.height = altura + "px";
        canvas.style.height = altura + "px";
    }, 200);
}

function renderKPI(metricas){
    const kpiDiv = document.getElementById('kpis');
    if(!kpiDiv) return;
    const totalAno = metricas.reduce((acc,m)=>acc+parseFloat(m.total_mes),0);
    const totalPedidos = metricas.reduce((acc,m)=>acc+parseInt(m.pedidos_mes),0);
    const ticketMedio = totalPedidos>0 ? (totalAno/totalPedidos).toFixed(2) : 0;
    const cards = [
        {titulo:'Total do Período', valor: totalAno.toLocaleString('pt-BR',{style:'currency',currency:'BRL'})},
        {titulo:'Total de Pedidos', valor: totalPedidos},
        {titulo:'Ticket Médio', valor: parseFloat(ticketMedio).toLocaleString('pt-BR',{style:'currency',currency:'BRL'})}
    ];
    kpiDiv.innerHTML = cards.map(c=>`
        <div class="col-md-4 mb-3">
            <div class="card bg-secondary p-3 text-center h-100">
                <h6 class="text-light">${c.titulo}</h6>
                <h4 class="mb-0 text-white">${c.valor}</h4>
            </div>
        </div>`).join('');
}

function renderMensal(dados){
    const anoAtual = parseInt(document.getElementById('filtroAno').value);
    const anoAnterior = anoAtual - 1;
    let valoresAnoAtual = new Array(12).fill(0), valoresAnoAnterior = new Array(12).fill(0);
    dados.forEach(d=>{
        const idx = parseInt(d.mes)-1;
        if(parseInt(d.ano)===anoAtual) valoresAnoAtual[idx] = d.total;
        if(parseInt(d.ano)===anoAnterior) valoresAnoAnterior[idx] = d.total;
    });
    if(graficoMensal) graficoMensal.destroy();
    graficoMensal = new Chart(document.getElementById('graficoMensal'),{
        type:'bar',
        data:{
            labels: ["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"],
            datasets:[{label:`${anoAtual}`,data:valoresAnoAtual,backgroundColor:'#4e73df'},{label:`${anoAnterior}`,data:valoresAnoAnterior,backgroundColor:'#858796'}]
        },
        options:{ responsive:true, maintainAspectRatio: false, plugins:{legend:{labels:{color:'#FFF'}}}, scales:{x:{ticks:{color:'#FFF'}},y:{beginAtZero:true,ticks:{color:'#FFF'}}} }
    });
}

function renderDesempenho(dados){
    const tbody = document.querySelector('#tblDesempenho tbody');
    if(!tbody) return;
    tbody.innerHTML = Object.keys(dados).map(mes=>{
        const d = dados[mes];
        return `<tr>
            <td>${mes}</td>
            <td>${parseFloat(d.media_dia).toLocaleString('pt-BR',{style:'currency',currency:'BRL'})}</td>
            <td>${parseFloat(d.media_semana).toLocaleString('pt-BR',{style:'currency',currency:'BRL'})}</td>
            <td>${parseFloat(d.total_mes).toLocaleString('pt-BR',{style:'currency',currency:'BRL'})}</td>
            <td>${parseFloat(d.media_pedido).toLocaleString('pt-BR',{style:'currency',currency:'BRL'})}</td>
            <td>${parseFloat(d.media_cliente).toLocaleString('pt-BR',{style:'currency',currency:'BRL'})}</td>
            <td>${parseFloat(d.menor_pedido).toLocaleString('pt-BR',{style:'currency',currency:'BRL'})}</td>
            <td>${parseFloat(d.maior_pedido).toLocaleString('pt-BR',{style:'currency',currency:'BRL'})}</td>
        </tr>`;
    }).join('');
}

function renderTopClientes(clientes){
    document.querySelector('#tblClientes tbody').innerHTML = clientes.map(c=>`<tr><td>${c.nome}</td><td>${parseFloat(c.total).toLocaleString('pt-BR',{style:'currency',currency:'BRL'})}</td></tr>`).join('');
    ajustarAlturaGrafico('tblClientes','graficoClientes');
    if(graficoClientes) graficoClientes.destroy();
    graficoClientes = new Chart(document.getElementById('graficoClientes'),{
        type:'pie',
        data:{ labels:clientes.map(c=>c.nome), datasets:[{data:clientes.map(c=>parseFloat(c.total)), backgroundColor:coresPalette, borderWidth:1, borderColor:'#2c2f33'}] },
        options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{position:'right',labels:{color:'#FFF',boxWidth:12,font:{size:11}}} } }
    });
}

function renderTopProdutos(produtos){
    document.querySelector('#tblProdutos tbody').innerHTML = produtos.map(p=>`<tr><td>${p.nome}</td><td>${p.total_vendido}</td><td>${parseFloat(p.total_valor).toLocaleString('pt-BR',{style:'currency',currency:'BRL'})}</td></tr>`).join('');
    ajustarAlturaGrafico('tblProdutos','graficoProdutos');
    if(graficoProdutos) graficoProdutos.destroy();
    graficoProdutos = new Chart(document.getElementById('graficoProdutos'),{
        type:'pie',
        data:{ labels:produtos.map(p=>p.nome), datasets:[{data:produtos.map(p=>modoGraficoProdutos=='quantidade'?p.total_vendido:parseFloat(p.total_valor)), backgroundColor:coresPalette, borderWidth:1, borderColor:'#2c2f33'}] },
        options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{position:'right',labels:{color:'#FFF',boxWidth:12,font:{size:11}}} } }
    });
}

function renderPagamentos(pagamentos) {
    const totalGeral = pagamentos.reduce((acc,p)=>acc+parseFloat(p.total),0);
    document.querySelector('#tblPagamentos tbody').innerHTML = pagamentos.map(p=>`<tr><td>${p.nome}</td><td>${parseFloat(p.total).toLocaleString('pt-BR',{style:'currency',currency:'BRL'})}</td><td>${totalGeral>0?((p.total/totalGeral)*100).toFixed(1):0}%</td></tr>`).join('');
    ajustarAlturaGrafico('tblPagamentos','graficoPagamentos');
    if(graficoPagamentos) graficoPagamentos.destroy();
    graficoPagamentos = new Chart(document.getElementById('graficoPagamentos'),{
        type:'pie',
        data:{ labels:pagamentos.map(p=>p.nome), datasets:[{data:pagamentos.map(p=>parseFloat(p.total)), backgroundColor:coresPalette, borderWidth:1, borderColor:'#2c2f33'}] },
        options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{position:'right',labels:{color:'#FFF',boxWidth:12,font:{size:11}}} } }
    });
}

/* FUNÇÃO EXPORTAR EXCEL */
window.exportExcel = function() {
    const btn = document.getElementById('btnExportPDFTop'); // Reutilizando o botão
    const originalText = btn.innerText;
    btn.innerText = "Gerando Excel...";
    btn.disabled = true;

    try {
        // 1. Criar um novo livro de trabalho (Workbook)
        const wb = XLSX.utils.book_new();
        let ws_data = [];

        // 2. Adicionar Título Geral e KPIs
        ws_data.push(["DASHBOARD FINANCEIRO"]);
        ws_data.push(["Gerado em: " + new Date().toLocaleString()]);
        ws_data.push([]); // Linha vazia

        // 3. Função auxiliar para extrair dados de uma tabela HTML
        const extrairTabela = (idTabela, titulo) => {
            const table = document.getElementById(idTabela);
            if (!table) return;

            ws_data.push([titulo.toUpperCase()]); // Título da seção

            // Converte a tabela HTML para array de arrays
            const rows = Array.from(table.querySelectorAll('tr'));
            rows.forEach(row => {
                const rowData = Array.from(row.querySelectorAll('th, td')).map(cell => {
                    // Limpa formatação de moeda (R$) para o Excel tratar como número, se possível
                    let val = cell.innerText.replace('R$', '').trim();
                    return isNaN(val.replace('.', '').replace(',', '.')) ? cell.innerText : val;
                });
                ws_data.push(rowData);
            });
            ws_data.push([]); // Espaço entre tabelas
        };

        // 4. Mapear todas as suas tabelas
        extrairTabela('tblDesempenho', 'Relatório Detalhado Anual');
        extrairTabela('tblClientes', 'Top 5 Clientes');
        extrairTabela('tblProdutos', 'Top 5 Produtos');
        extrairTabela('tblPagamentos', 'Meios de Pagamento');

        // 5. Converter array para folha (Worksheet)
        const ws = XLSX.utils.aoa_to_sheet(ws_data);

        // 6. Adicionar a folha ao livro
        XLSX.utils.book_append_sheet(wb, ws, "Relatório Financeiro");

        // 7. Salvar o arquivo
        XLSX.writeFile(wb, "Relatorio_Financeiro.xlsx");

    } catch (err) {
        console.error("Erro ao exportar Excel:", err);
        alert("Erro ao gerar planilha.");
    } finally {
        btn.innerText = originalText;
        btn.disabled = false;
    }
};

document.addEventListener('DOMContentLoaded',()=>{
    carregarDashboard();
    document.getElementById('filtroAno').addEventListener('change',carregarDashboard);
    document.getElementById('filtroMes').addEventListener('change',carregarDashboard);
    document.getElementById('filtroGraficoProdutos').addEventListener('change',e=>{ modoGraficoProdutos=e.target.value; carregarDashboard(); });
    document.getElementById('btnExportPDFTop').addEventListener('click', exportExcel);
});
</script>
