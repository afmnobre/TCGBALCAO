</div>

<?php
$loja = $_SESSION['LOJA'] ?? [];

$idLoja      = $loja['id_loja'] ?? 0;
$faviconFile = $loja['favicon'] ?? 'favicon.ico';
$faviconPath = "/storage/uploads/lojas/{$idLoja}/{$faviconFile}";
$corTema     = $loja['cor_tema'] ?? '#000'; // cor dinâmica em hexadecimal
?>
<footer class="text-light text-center py-1 fixed-bottom shadow-sm" style="background-color: <?= htmlspecialchars($corTema) ?>;">
   <div class="container-fluid">
      <div class="mb-2">
         <img src="<?= htmlspecialchars($faviconPath) ?>" alt="Favicon da Loja" height="20">
      </div>
      <small>© <?= date('Y') ?> - <?= htmlspecialchars($loja['nome_loja'] ?? 'TCGBalcão') ?></small>
   </div>
</footer>

<!-- (PDF export) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<!-- html2canvas (render to canvas) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<!-- Flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Chart.js (gráficos) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Seus scripts locais -->
<script src="/public/js/pedidos.js"></script>
<script src="/public/js/cliente.js"></script>
<script src="/public/js/produto.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    initCalendario(
        <?= isset($datasPendentes) ? json_encode($datasPendentes) : 'null' ?>,
        "<?= isset($dataSelecionada) ? $dataSelecionada : '' ?>"
    );
});
</script>
</body>
</html>

