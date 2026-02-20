<?php if (!empty($_SESSION['flash'])): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($_SESSION['flash']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
  </div>
  <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="text-light">Clientes da Loja</h2>
  <a href="/cliente/criar" class="btn btn-primary btn-sm">➕ Novo Cliente</a>
</div>

<div class="table-responsive">
  <table class="table table-dark table-striped table-hover align-middle">
    <thead>
      <tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Telefone</th>
        <th>CardGames</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($clientes)): ?>
        <?php foreach ($clientes as $cliente): ?>
          <tr>
            <td><?= htmlspecialchars($cliente['nome']) ?></td>
            <td><?= htmlspecialchars($cliente['email']) ?></td>
            <td class="telefone-coluna"><?= htmlspecialchars($cliente['telefone']) ?></td>
            <td>
              <?php if (!empty($cliente['cardgames'])): ?>
                <div class="d-flex flex-wrap gap-1">
                  <?php foreach ($cliente['cardgames'] as $game): ?>
                    <div class="cliente-cardgame-thumb text-center"
                         title="<?= htmlspecialchars($game['nome']) ?>"
                         style="background-image: url('/public/images/cartas_fundo/<?= htmlspecialchars($game['imagem_fundo_card']) ?>'); background-size: cover; width: 60px; height: 80px; position: relative; border-radius: 4px;">
                      <span class="cliente-cardgame-name bg-dark bg-opacity-75 text-light small px-1" style="position:absolute; bottom:0; left:0; right:0;">
                        <?= htmlspecialchars($game['nome']) ?>
                      </span>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php else: ?>
                <span class="text-muted">Nenhum</span>
              <?php endif; ?>
            </td>
            <td>
              <a href="/cliente/editar/<?= $cliente['id_cliente'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
              <a href="/cliente/excluir/<?= $cliente['id_cliente'] ?>"
                 onclick="return confirm('Tem certeza que deseja excluir este cliente?')"
                 class="btn btn-danger btn-sm">🗑️ Excluir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" class="text-center text-muted">Nenhum cliente cadastrado ainda.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>


