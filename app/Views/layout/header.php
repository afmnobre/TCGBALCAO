<!DOCTYPE html>
<?php
$logo = $_SESSION['LOJA']['logo'] ?? 'default.png';
$cor  = $_SESSION['LOJA']['cor'] ?? '#333';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Sistema TCGBalcão</title>
    <style>
        body { font-family: Arial, sans-serif; margin:0; }
        header { background-color: <?= $cor ?>; color: #fff; padding: 10px; }
        nav a { margin: 0 10px; color: #fff; text-decoration: none; }
        nav a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<header>
    <img src="/storage/uploads/<?= $logo ?>" alt="Logo da Loja" height="40">
    <nav>
        <a href="/home">🏠 Home</a>
        <a href="/pedido">📦 Pedidos</a>
        <a href="/cliente">👥 Clientes</a>
        <a href="/produto">🎴 Produtos & Estoque</a>
        <a href="/relatorio">📊 Relatórios</a>
        <a href="/logout">🚪 Logout</a>
    </nav>
</header>
<main>

