<h2>Login da Loja</h2>

<?php if (!empty($_SESSION['erro_login'])): ?>
    <p style="color:red"><?= $_SESSION['erro_login']; unset($_SESSION['erro_login']); ?></p>
<?php endif; ?>

<form method="POST" action="/autenticar">
    <label>Login da Loja</label><br>
    <input type="text" name="login" required><br><br>

    <label>Senha</label><br>
    <input type="password" name="senha" required><br><br>

    <button type="submit">Entrar</button>
</form>

