<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Lojas Cadastradas</h3>
        <a href="/admin/loja/create" class="btn btn-primary">Nova Loja</a>
    </div>

    <div class="card shadow">
        <div class="card-body">

            <?php if (!empty($lojas)): ?>

                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Logo</th>
                                <th>Nome</th>
                                <th>CNPJ</th>
                                <th>Contrato</th>
                                <th>Cor Tema</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($lojas as $loja): ?>
                                <?php
                                   $logoPath = "/public/storage/uploads/lojas/{$loja['id_loja']}/{$loja['logo']}";
                                ?>
                                <tr>
                                    <td><?= $loja['id_loja'] ?></td>
                                    <td>
                                        <?php if (!empty($loja['logo'])): ?>
                                            <img src="<?= $logoPath ?>" alt="Logo" style="height:40px;">
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($loja['nome_loja']) ?></td>
                                    <td><?= htmlspecialchars($loja['cnpj']) ?></td>
                                    <td><?= !empty($loja['contrato_ativo']) ? htmlspecialchars($loja['contrato_ativo']) : 'Sem contrato' ?></td>
                                    <td>
                                        <span style="
                                            display:inline-block;
                                            width:25px;
                                            height:25px;
                                            background:<?= $loja['cor_tema'] ?>;
                                            border-radius:4px;">
                                        </span>
                                        <?= $loja['cor_tema'] ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-info btn-sm" onclick="abrirHistorico(<?= $loja['id_loja'] ?>)">
                                            Histórico de Contratos
                                        </button>
                                        <a href="/admin/loja/edit/<?= $loja['id_loja'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                        <a href="/admin/loja/delete/<?= $loja['id_loja'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir esta loja?')">Excluir</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>

            <?php else: ?>
                <p class="text-muted">Nenhuma loja cadastrada.</p>
            <?php endif; ?>

        </div>
    </div>

</div>

<!-- Modal Histórico -->
<div class="modal fade" id="historicoModal" tabindex="-1" aria-labelledby="historicoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content text-dark">
      <div class="modal-header">
        <h5 class="modal-title" id="historicoModalLabel">Histórico de Contratos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body" id="conteudoHistorico">
        <!-- Conteúdo carregado via JS -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<script>
function abrirHistorico(id_loja) {
    const modalEl = document.getElementById('historicoModal');
    const myModal = new bootstrap.Modal(modalEl, { backdrop: 'static', keyboard: false });
    myModal.show();

    const conteudo = document.getElementById('conteudoHistorico');
    conteudo.innerHTML = '<div class="text-center">Carregando histórico...</div>';

    fetch(`/admin/contrato/historico/${id_loja}`)
        .then(res => res.json())
        .then(data => {
            if (!data || data.length === 0) {
                conteudo.innerHTML = '<p class="text-center">Nenhum histórico encontrado.</p>';
                return;
            }

            let html = `<table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nº Contrato</th>
                                    <th>Tipo</th>
                                    <th>Data Início</th>
                                    <th>Data Fim</th>
                                    <th>Status</th>
                                    <th>Data Movimentação</th>
                                </tr>
                            </thead>
                            <tbody>`;

            data.forEach(h => {
                html += `<tr>
                            <td>${h.id_contrato}</td>
                            <td>${h.tipo_contrato}</td>
                            <td>${new Date(h.data_inicio_contrato).toLocaleDateString('pt-BR')}</td>
                            <td>${new Date(h.data_fim_contrato).toLocaleDateString('pt-BR')}</td>
                            <td>${h.status_contrato}</td>
                            <td>${new Date(h.data_vinculo).toLocaleDateString('pt-BR')}</td>
                         </tr>`;
            });

            html += '</tbody></table>';
            conteudo.innerHTML = html;
        })
        .catch(err => {
            console.error(err);
            conteudo.innerHTML = '<p class="text-danger text-center">Erro ao carregar histórico.</p>';
        });
}
</script>

