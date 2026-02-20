<h2 class="text-light"><?= isset($cliente) ? 'Editar Cliente' : 'Cadastrar Cliente' ?></h2>

<form method="POST" action="<?= isset($cliente) ? '/cliente/atualizar/'.$cliente['id_cliente'] : '/cliente/salvar' ?>"
      class="bg-dark text-light p-4 rounded">

  <!-- Linha com Telefone, Nome e Email -->
  <div class="row mb-3">
    <div class="col-md-4">
      <label for="telefone" class="form-label text-light">Telefone (Celular):</label>
      <input type="text" id="telefone" name="telefone"
             value="<?= htmlspecialchars($cliente['telefone'] ?? '') ?>"
             maxlength="15" required
             class="form-control bg-dark text-light border-secondary">
      <span id="statusCliente" class="small"></span>
    </div>

    <div class="col-md-4">
      <label for="nome" class="form-label text-light">Nome:</label>
      <input type="text" id="nome" name="nome"
             value="<?= htmlspecialchars($cliente['nome'] ?? '') ?>"
             class="form-control bg-dark text-light border-secondary">
    </div>

    <div class="col-md-4">
      <label for="email" class="form-label text-light">Email:</label>
      <input type="email" id="email" name="email"
             value="<?= htmlspecialchars($cliente['email'] ?? '') ?>"
             class="form-control bg-dark text-light border-secondary">
    </div>
  </div>

<!-- CardGames -->
<h3 class="text-light">CardGames que o cliente joga:</h3>
<div class="d-flex flex-wrap gap-3 mb-3">
  <?php if (!empty($cardgames)): ?>
    <?php foreach ($cardgames as $cg): ?>
      <?php $checked = (!empty($cardgamesCliente) && in_array($cg['id_cardgame'], array_column($cardgamesCliente, 'id_cardgame'))) ? 'checked' : ''; ?>

      <div class="card border-0 bg-transparent text-light" style="width: 105px; aspect-ratio: 63/88; position: relative;">
        <!-- Imagem -->
        <img src="/public/images/cartas_fundo/<?= htmlspecialchars($cg['imagem_fundo_card']) ?>"
             alt="<?= htmlspecialchars($cg['nome']) ?>"
             class="card-img" style="width: 100%; height: 100%; object-fit: cover;">

        <!-- Overlay com checkbox e nome -->
        <div class="card-img-overlay d-flex flex-column justify-content-center align-items-center p-0">
          <input class="form-check-input mb-1" type="checkbox" name="cardgames[]" value="<?= $cg['id_cardgame'] ?>" <?= $checked ?>>
          <small class="fw-bold"><?= htmlspecialchars($cg['nome']) ?></small>
        </div>
      </div>

    <?php endforeach; ?>
  <?php else: ?>
    <p class="text-muted">Nenhum cardgame cadastrado.</p>
  <?php endif; ?>
</div>




  <!-- Botão -->
  <button class="btn btn-primary" type="submit">Salvar</button>
</form>



