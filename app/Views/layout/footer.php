</main>
<br><br><br>
<?php
$loja = $_SESSION['LOJA'] ?? [];

$idLoja      = $loja['id_loja'] ?? 0;
$faviconFile = $loja['favicon'] ?? 'favicon.ico';
$faviconPath = "/storage/uploads/{$idLoja}/{$faviconFile}";
$corTema     = $loja['cor_tema'] ?? '#000'; // cor dinâmica em hexadecimal
?>
<footer class="text-light text-center py-1 fixed-bottom shadow-sm" style="background-color: <?= htmlspecialchars($corTema) ?>;">
   <div class="container-fluid">
    <div class="mb-2">
      <img src="<?= htmlspecialchars($faviconPath) ?>" alt="Favicon da Loja" height="20">
    </div>
    <small>© <?= date('Y') ?> - <?= htmlspecialchars($loja['nome_loja'] ?? 'TCGBalcão') ?></small>
  </div>

  <!-- Scripts globais -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>

  <script src="/public/js/pedidos.js"></script>
  <script src="/public/js/cliente.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@latest/index.js"></script>
  <script src="/public/js/produto.js"></script>

  <script>
    initCalendario(
      <?= json_encode($datasPendentes) ?>,
      "<?= $dataSelecionada ?>"
    );
  </script>
</footer>

</body>
</html>

