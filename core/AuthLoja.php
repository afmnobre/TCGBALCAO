<?php
if (!empty($_SESSION['erro_login'])) {
    echo "<div style='color:red'>" . $_SESSION['erro_login'] . "</div>";
    unset($_SESSION['erro_login']);
}
?>

<h2>Login da Loja</h2>

<form method="POST" action="/auth/autenticar">
    <label>Login:</label><br>
    <input type="text" name="login" required><br><br>

    <label>Senha:</label><br>
    <input type="password" name="senha" required><br><br>

    <button type="submit">Entrar</button>
</form>

