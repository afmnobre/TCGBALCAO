<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Admin - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
        }

        .login-card {
            background-color: #1e1e2f;
        }

        .form-control {
            background-color: #2a2a3d;
            border: 1px solid #3a3a55;
            color: #fff;
        }

        .form-control:focus {
            background-color: #2a2a3d;
            border-color: #6c63ff;
            box-shadow: 0 0 0 0.2rem rgba(108, 99, 255, 0.25);
        }

        .btn-primary {
            background-color: #6c63ff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #5a52e0;
        }

        .footer-text {
            color: #aaa;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-5 col-lg-4">

            <div class="card login-card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">

                    <div class="text-center mb-4">
                        <h4 class="fw-bold text-light">Painel Administrativo</h4>
                        <p class="text-secondary small">Acesso restrito</p>
                    </div>

                    <?php if (!empty($_SESSION['erro_login_admin'])): ?>
                        <div class="alert alert-danger text-center">
                            <?= $_SESSION['erro_login_admin']; unset($_SESSION['erro_login_admin']); ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="/admin/auth/autenticar">

                        <div class="mb-3">
                            <label class="form-label text-light">Email</label>
                            <input type="text"
                                   name="login"
                                   class="form-control"
                                   placeholder="Digite seu email"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-light">Senha</label>
                            <input type="password"
                                   name="senha"
                                   class="form-control"
                                   placeholder="Digite sua senha"
                                   required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Entrar
                            </button>
                        </div>

                    </form>

                </div>
            </div>

            <div class="text-center mt-3 footer-text">
                <small>© <?= date('Y') ?> TCGBALCAO - Admin</small>
            </div>

        </div>
    </div>
</div>

</body>
</html>

