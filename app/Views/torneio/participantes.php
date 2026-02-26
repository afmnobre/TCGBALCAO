<h2 class="text-light mb-3">👥 Selecionar Participantes</h2>

<div class="bg-dark text-light p-3 rounded border border-secondary mb-3">
    <h5 class="mb-1 text-light"><?= htmlspecialchars($torneio['nome_torneio']) ?></h5>
    <p class="mb-0 small" style="color: #bbb;">
        <strong class="text-light">🎴 Cardgame:</strong> <?= htmlspecialchars($torneio['cardgame']) ?> |
        <strong class="text-light">⚙️ Tipo:</strong> <?= htmlspecialchars($torneio['tipo_legivel']) ?>
    </p>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="text-light m-0">👥 Selecionar Participantes</h2>
    <span class="badge bg-primary p-2" id="contadorParticipantes">
        0 Jogadores Selecionados
    </span>
</div>

<div class="mb-3">
    <div class="input-group">
        <span class="input-group-text bg-dark border-secondary text-light">🔍</span>
        <input type="text" id="buscaJogador" class="form-control bg-dark text-light border-secondary"
               placeholder="Digite o nome do jogador para filtrar...">
    </div>
</div>



<form action="/torneio/salvarParticipantes/<?= $torneio['id_torneio'] ?>" method="POST" class="bg-dark text-light p-4 rounded border border-secondary">
    <div class="mb-3">
        <?php if (!empty($clientes)): ?>
            <label class="form-label mb-3 text-light">Selecione os jogadores abaixo:</label>

            <div class="row" id="listaParticipantes">
                <?php foreach ($clientes as $cliente): ?>
                    <div class="col-md-6 mb-2 item-jogador" data-nome="<?= strtolower(htmlspecialchars($cliente['nome'])) ?>">
                        <label class="list-group-item bg-dark text-light border-secondary d-flex justify-content-between align-items-center py-3 px-3 rounded"
                               style="cursor: pointer; border: 1px solid #444;">

                            <div class="text-truncate d-flex align-items-center">
                                <input type="checkbox" name="participantes[]" value="<?= $cliente['id_cliente'] ?>"
                                       class="form-check-input me-3" style="flex-shrink: 0;">

                                <div class="text-truncate">
                                    <span class="fw-bold text-light nome-texto"><?= htmlspecialchars($cliente['nome']) ?></span>
                                    <?php if (!empty($cliente['email'])): ?>
                                        <br class="d-md-none">
                                        <span class="ms-md-2 small" style="color: #aaa;">| <?= htmlspecialchars($cliente['email']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <span class="badge rounded-pill bg-secondary text-dark d-none d-lg-inline ms-3">
                                Cliente
                            </span>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <div class="alert alert-warning border-0 shadow-sm">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Nenhum cliente vinculado a este cardgame na loja.
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-4">
        <input type="hidden" name="id_torneio" value="<?= $torneio['id_torneio'] ?>">
        <button type="submit" class="btn btn-primary">💾 Confirmar Participantes</button>
        <a href="/torneio" class="btn btn-secondary">↩️ Voltar</a>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputBusca = document.getElementById('buscaJogador');
    const itensJogadores = document.querySelectorAll('.item-jogador');

    inputBusca.addEventListener('input', function() {
        const termoBusca = this.value.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, ""); // Remove acentos para busca ficar fácil

        itensJogadores.forEach(item => {
            const nomeJogador = item.getAttribute('data-nome').normalize('NFD').replace(/[\u0300-\u036f]/g, "");

            if (nomeJogador.includes(termoBusca)) {
                item.style.display = 'block'; // Mostra se houver correspondência
            } else {
                item.style.display = 'none'; // Esconde se não houver
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const inputBusca = document.getElementById('buscaJogador');
    const itensJogadores = document.querySelectorAll('.item-jogador');
    const contador = document.getElementById('contadorParticipantes');
    const checkboxes = document.querySelectorAll('input[name="participantes[]"]');

    // Função para atualizar o número no contador
    function atualizarContador() {
        const totalSelecionados = document.querySelectorAll('input[name="participantes[]"]:checked').length;
        contador.innerText = `${totalSelecionados} Jogadores Selecionados`;

        // Opcional: muda a cor do badge se tiver alguém selecionado
        if (totalSelecionados > 0) {
            contador.classList.replace('bg-secondary', 'bg-primary');
        } else {
            contador.classList.replace('bg-primary', 'bg-secondary');
        }
    }

    // Escuta cliques nos checkboxes
    checkboxes.forEach(cb => {
        cb.addEventListener('change', atualizarContador);
    });

    // Lógica do Filtro (que já tínhamos)
    inputBusca.addEventListener('input', function() {
        const termoBusca = this.value.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, "");

        itensJogadores.forEach(item => {
            const nomeJogador = item.getAttribute('data-nome').normalize('NFD').replace(/[\u0300-\u036f]/g, "");
            item.style.display = nomeJogador.includes(termoBusca) ? 'block' : 'none';
        });
    });

    // Executa uma vez ao carregar (caso seja uma edição e já existam marcados)
    atualizarContador();
});


</script>
