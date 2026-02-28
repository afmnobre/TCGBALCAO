<div class="container mt-4">
    <h3>Contratos</h3>
    <a href="/admin/contrato/form" class="btn btn-success mb-3">Novo Contrato</a>

    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th>Logo</th>
                <th>Loja</th>
                <th>Tipo</th>
                <th>Data Início</th>
                <th>Data Fim</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($contratos as $c):
                $loja = array_filter($lojas, fn($l) => $l['id_loja'] == $c['id_loja']);
                $loja = array_values($loja)[0];
                $logoPath = "/public/storage/uploads/lojas/{$loja['id_loja']}/{$loja['logo']}";
            ?>
            <tr>
                <td><img src="<?= $logoPath ?>" alt="<?= htmlspecialchars($loja['nome_loja']) ?>" width="50"></td>
                <td><?= htmlspecialchars($loja['nome_loja']) ?></td>
                <td><?= ucfirst($c['tipo']) ?></td>
                <td><?= date('d/m/Y', strtotime($c['data_inicio'])) ?></td>
                <td><?= date('d/m/Y', strtotime($c['data_fim'])) ?></td>
                <td><?= ucfirst($c['status']) ?></td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="abrirContrato(<?= $c['id_contrato'] ?>)">
                        Visualizar Contrato
                    </button>
                    <a href="/admin/contrato/form/<?= $c['id_contrato'] ?>" class="btn btn-sm btn-primary">Editar</a>
                    <a href="/admin/contrato/delete/<?= $c['id_contrato'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir este contrato?')">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="contratoModal" tabindex="-1" aria-labelledby="contratoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content text-dark">
      <div class="modal-header">
        <h5 class="modal-title" id="contratoModalLabel">Contrato</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body" id="conteudoContrato">
        <!-- Conteúdo do contrato será injetado pelo JS -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<script>
const contratos = <?= json_encode($contratos) ?>;
const lojas = <?= json_encode($lojas) ?>;

function abrirContrato(id_contrato) {
    const contrato = contratos.find(c => c.id_contrato == id_contrato);
    const loja = lojas.find(l => l.id_loja == contrato.id_loja);

    const numeroContrato = loja.numero_contrato || `CT-${contrato.id_contrato.toString().padStart(4, '0')}`;

    const html = `
        <p><strong>Número do Contrato:</strong> ${numeroContrato}</p>
        <p><strong>Loja:</strong> ${loja.nome_loja}
            <img src="/public/storage/uploads/lojas/${loja.id_loja}/${loja.logo}" style="height:40px; vertical-align:middle;">
        </p>
        <p><strong>Tipo:</strong> ${contrato.tipo}</p>
        <p><strong>Data Início:</strong> ${new Date(contrato.data_inicio).toLocaleDateString('pt-BR')}</p>
        <p><strong>Data Fim:</strong> ${new Date(contrato.data_fim).toLocaleDateString('pt-BR')}</p>
        <p><strong>Status:</strong> ${contrato.status}</p>
        <hr>
        <h5>Contrato de Aluguel de Software - TCG BALCÃO</h5>
        <p>
            Este contrato estabelece os termos e condições para utilização do software TCG BALCÃO pela loja <strong>${loja.nome_loja}</strong>.
            O software será fornecido conforme o plano escolhido (${contrato.tipo}) e dentro do período de ${new Date(contrato.data_inicio).toLocaleDateString('pt-BR')} até ${new Date(contrato.data_fim).toLocaleDateString('pt-BR')}.
        </p>
        <p>
            A TCG BALCÃO se compromete a fornecer suporte técnico, atualizações do sistema e manutenção durante o período de vigência do contrato.
        </p>
        <p>Assinatura da Loja: ______________________</p>
        <p>Assinatura TCG BALCÃO: ______________________</p>
    `;

    document.getElementById('conteudoContrato').innerHTML = html;

    const myModal = new bootstrap.Modal(document.getElementById('contratoModal'));
    myModal.show();
}

</script>

