<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Painel Admin' ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS Customizado -->
    <!--<link rel="stylesheet" href="/public/css/admin.css">-->
</head>

<body class="bg-dark text-light d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-black shadow-sm">
    <div class="container-fluid">

        <a class="navbar-brand fw-bold" href="/admin/home">
            TCG Balcão Admin
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarAdmin">

            <!-- MENU ESQUERDA -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <!-- LOJAS -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button"
                       data-bs-toggle="dropdown">
                        Lojas
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="/admin/loja">Lista de Lojas</a></li>
                        <li><a class="dropdown-item" href="/admin/loja/create">Nova Loja</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/admin/usuarioLoja">Usuários das Lojas</a></li>
                        <li><a class="dropdown-item" href="/admin/contrato">Contratos</a></li>
                    </ul>
                </li>

                <!-- CLIENTES -->
                <li class="nav-item">
                    <a class="nav-link" href="/admin/cliente">
                        Clientes
                    </a>
                </li>

                <!-- CARDGAMES -->
                <li class="nav-item">
                    <a class="nav-link" href="/admin/cardgame">
                        Cardgames
                    </a>
                </li>

                <!-- MÉTODOS PAGAMENTO -->
                <li class="nav-item">
                    <a class="nav-link" href="/admin/tipopagamento">
                        Métodos Pagamento
                    </a>
                </li>

                <!-- AUDITORIA -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#"
                       data-bs-toggle="dropdown">
                        Auditoria
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="/admin/auditoria">
                            Logs Gerais
                        </a></li>
                        <li><a class="dropdown-item" href="/admin/logPedidos">
                            Logs Pedidos
                        </a></li>
                    </ul>
                </li>

            </ul>

            <!-- DIREITA (USUÁRIO LOGADO) -->
            <ul class="navbar-nav ms-auto">

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#"
                       data-bs-toggle="dropdown">
                        <?= $_SESSION['ADMIN']['nome'] ?? 'Admin' ?>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                        <li>
                            <a class="dropdown-item text-danger" href="/admin/auth/logout">
                                Logout
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>

        </div>
    </div>
</nav>

<main class="flex-fill container-fluid mt-4">

