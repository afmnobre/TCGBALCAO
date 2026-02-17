</div>
<?php
$loja = $_SESSION['LOJA'] ?? [];

$idLoja      = $loja['id_loja'] ?? 0;
$corFooter   = $loja['cor_tema'] ?? '#eee';
$faviconFile = $loja['favicon'] ?? 'favicon.ico'; // pega o nome do banco

$faviconPath = "/storage/uploads/{$idLoja}/{$faviconFile}";
?>
<footer style="background-color: <?= htmlspecialchars($corFooter) ?>; text-align:center; padding:10px;">
    <div style="margin-top:5px;">
        <img src="<?= htmlspecialchars($faviconPath) ?>" alt="Favicon da Loja" height="20">
    </div> &nbsp;&nbsp;
    <small>© <?= date('Y') ?> - <?= htmlspecialchars($loja['nome_loja'] ?? 'TCGBalcão') ?></small>
</footer>
</main>
</body>
</html>

