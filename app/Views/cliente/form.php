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
<div class="cards-grid mt-3">
  <?php if (!empty($cardgames)): ?>
    <?php foreach ($cardgames as $cg): ?>
      <?php $checked = (!empty($cardgamesCliente) && in_array($cg['id_cardgame'], array_column($cardgamesCliente, 'id_cardgame'))) ? 'checked' : ''; ?>

      <label class="magic-card">
        <input class="magic-check"
               type="checkbox"
               name="cardgames[]"
               value="<?= $cg['id_cardgame'] ?>"
               <?= $checked ?>>

        <img src="/storage/uploads/cardgames/<?= htmlspecialchars($cg['id_cardgame']) ?>/<?= htmlspecialchars($cg['imagem_fundo_card']) ?>"
             alt="<?= htmlspecialchars($cg['nome']) ?>">

        <div class="card-overlay">
          <small><?= htmlspecialchars($cg['nome']) ?></small>
        </div>
      </label>

    <?php endforeach; ?>
  <?php else: ?>
    <p class="text-muted">Nenhum cardgame cadastrado.</p>
  <?php endif; ?>
</div>

<br><br>




  <!-- Botão -->
  <button class="btn btn-primary" type="submit">Salvar</button>
</form>



