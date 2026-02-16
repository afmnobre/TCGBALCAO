<?php
$loja = $_SESSION['LOJA'] ?? [];

$idLoja = $loja['id_loja'] ?? 0;
$nomeLoja = $loja['nome_loja'] ?? 'Sistema TCGBalcão';
$corHeader = $loja['cor_tema'] ?? '#333';
$logo = $loja['logo'] ?? 'logo.png';
$favicon = $loja['favicon'] ?? 'favicon.ico';

$logoPath = "/storage/uploads/{$idLoja}/{$logo}";
$faviconPath = "/storage/uploads/{$idLoja}/{$favicon}";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($nomeLoja) ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="icon" href="<?= htmlspecialchars($faviconPath) ?>" type="image/x-icon">
</head>
<body>
<header style="background-color: <?= htmlspecialchars($corHeader) ?>; padding: 15px;">
    <div class="container" style="display:flex; align-items:center; justify-content:space-between;">
        <div style="display:flex; align-items:center; gap:15px;">
            <img src="<?= htmlspecialchars($logoPath) ?>" alt="Logo da Loja" height="40">
            <h1><?= htmlspecialchars($nomeLoja) ?></h1>
        </div>
        <nav>
            <a href="/home">🏠 Home</a>
            <a href="/pedido">📦 Pedidos</a>
            <a href="/cliente">👥 Clientes</a>
            <a href="/produto">🎴 Produtos & Estoque</a>
            <a href="/relatorio">📊 Relatórios</a>
            <a href="/logout">🚪 Logout</a>
        </nav>
    </div>
</header>
<main>


