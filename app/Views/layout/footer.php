<?php
$loja = $_SESSION['LOJA'] ?? [];

$idLoja    = $loja['id_loja'] ?? 0;
$corFooter = $loja['cor_tema'] ?? '#eee';
$favicon   = $loja['favicon'] ?? 'favicon.ico';

$faviconPath = "/storage/uploads/{$idLoja}/{$favicon}";
?>
<footer style="background-color: <?= htmlspecialchars($corFooter) ?>; text-align:center; padding:10px;">
    <small>© <?= date('Y') ?> - <?= htmlspecialchars($loja['nome_loja'] ?? 'TCGBalcão') ?></small>
    <?php if ($favicon): ?>
        <div style="margin-top:5px;">
            <img src="<?= htmlspecialchars($faviconPath) ?>" alt="Favicon da Loja" height="20">
        </div>
    <?php endif; ?>
</footer>
</main>
</body>
</html>


