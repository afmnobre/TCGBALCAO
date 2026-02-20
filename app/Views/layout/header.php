<?php
$loja = $_SESSION['LOJA'] ?? [];

$idLoja      = $loja['id_loja'] ?? 0;
$nomeLoja    = $loja['nome_loja'] ?? 'Sistema TCGBalcão';
$logoFile    = $loja['logo'] ?? 'logo.png';
$faviconFile = $loja['favicon'] ?? 'favicon.ico';
$corTema     = $loja['cor_tema'] ?? '#000'; // cor em hexadecimal

$logoPath    = "/storage/uploads/{$idLoja}/{$logoFile}";
$faviconPath = "/storage/uploads/{$idLoja}/{$faviconFile}";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($nomeLoja) ?></title>
  <link rel="icon" href="<?= htmlspecialchars($faviconPath) ?>" type="image/x-icon">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Flatpickr Dark Theme -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">

  <!-- Seus CSS locais -->
  <link rel="stylesheet" href="/public/css/style.css">
  <link rel="stylesheet" href="/public/css/pedido.css">
</head>

<body class="bg-dark text-light">

<!-- Header fixo com Navbar Bootstrap -->
<nav class="navbar navbar-expand-lg fixed-top shadow" style="background-color: <?= htmlspecialchars($corTema) ?>;">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center text-white" href="/home">
      <img src="<?= htmlspecialchars($logoPath) ?>" alt="Logo da Loja" height="40" class="me-2">
      <?= htmlspecialchars($nomeLoja) ?>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegação">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link text-white" href="/home">Home</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="/pedido">Pedidos</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="/cliente">Clientes</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="/torneio">Torneios</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="/produto">Produtos & Estoque</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="/relatorio">Relatórios</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="/logout">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="main-content">



