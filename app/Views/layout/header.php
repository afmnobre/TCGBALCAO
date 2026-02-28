<!DOCTYPE html>
<?php
$loja = $_SESSION['LOJA'] ?? [];

$idLoja      = $loja['id_loja'] ?? 0;
$nomeLoja    = $loja['nome_loja'] ?? 'Sistema TCGBalcão';
$logoFile    = $loja['logo'] ?? 'logo.png';
$faviconFile = $loja['favicon'] ?? 'favicon.ico';
$corTema     = $loja['cor_tema'] ?? '#000'; // cor em hexadecimal

// Define a URL base para assets
$baseAssetUrl = '/public';

$logoPath    = "/storage/uploads/lojas/{$idLoja}/{$logoFile}";
$faviconPath = "/storage/uploads/lojas/{$idLoja}/{$faviconFile}";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<?php
$loja = $_SESSION['LOJA'] ?? [];
$idLoja      = $loja['id_loja'] ?? 0;
$nomeLoja    = $loja['nome_loja'] ?? 'Sistema TCGBalcão';
$logoFile    = $loja['logo'] ?? 'logo.png';
$faviconFile = $loja['favicon'] ?? 'favicon.ico';
$corTema     = $loja['cor_tema'] ?? '#000';
$logoPath    = "{$baseAssetUrl}/storage/uploads/lojas/{$idLoja}/{$logoFile}";
$faviconPath = "{$baseAssetUrl}/storage/uploads/lojas/{$idLoja}/{$faviconFile}";

// Dados do Usuário Logado
$usuarioLogado = $_SESSION['USUARIO'] ?? null;
$nomeUsuario   = $usuarioLogado['nome'] ?? 'Usuário';
$perfilUsuario = $usuarioLogado['perfil'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($nomeLoja) ?></title>
    <link rel="icon" href="<?= htmlspecialchars($faviconPath) ?>" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/pedido.css">
    <link rel="stylesheet" href="/public/css/produto.css">
    <link rel="stylesheet" href="/public/css/torneioEliminacao.css">

    <style>
        /* Estilo para o crachá de perfil */
        .badge-perfil {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            background-color: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
        }
    </style>
</head>
<body class="bg-dark text-light">

<nav class="navbar navbar-expand-lg fixed-top shadow" style="background-color: <?= htmlspecialchars($corTema) ?>;">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center text-white" href="/home">
            <img src="<?= htmlspecialchars($logoPath) ?>" alt="Logo da Loja" height="40" class="me-2">
            <?= htmlspecialchars($nomeLoja) ?>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link text-white" href="/home">Home</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="/pedido">Pedidos</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="/cliente">Clientes</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="/torneio">Torneios</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="/produto">Produtos & Estoque</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="/relatorio">Relatórios</a></li>

                <li class="nav-item px-2 d-none d-lg-block">
                    <div class="vr h-100 text-white"></div>
                </li>

                <li class="nav-item d-flex flex-column align-items-end me-3">
                    <span class="text-white fw-bold small" style="line-height: 1;">
                        <?= htmlspecialchars($nomeUsuario) ?>
                    </span>
                    <span class="badge badge-perfil mt-1">
                        <?= htmlspecialchars($perfilUsuario) ?>
                    </span>
                </li>

                <li class="nav-item">
                    <a class="btn btn-outline-light btn-sm" href="/logout">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="main-content">


