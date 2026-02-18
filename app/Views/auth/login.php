<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Login da Loja</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');

    body {
      font-family: 'Press Start 2P', monospace;
      background-color: #000; /* fundo preto estilo terminal */
      color: #00ff00; /* verde neon */
      height: 100vh;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-container {
      background-color: #111;
      border: 2px solid #00ff00;
      padding: 40px;
      border-radius: 8px;
      box-shadow: 0 0 20px #00ff00;
      width: 400px;
      text-align: center;
    }

    .login-container h2 {
      margin-bottom: 20px;
      text-shadow: 0 0 5px #00ff00;
    }

    .login-container label {
      display: block;
      text-align: left;
      margin-bottom: 5px;
    }

    .login-container input {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #00ff00;
      border-radius: 5px;
      background-color: #000;
      color: #00ff00;
      font-family: 'Press Start 2P', monospace;
    }

    .login-container input:focus {
      outline: none;
      box-shadow: 0 0 10px #00ff00;
    }

    .login-container button {
      width: 100%;
      padding: 12px;
      background-color: #00ff00;
      border: none;
      border-radius: 5px;
      color: #000;
      font-family: 'Press Start 2P', monospace;
      font-size: 14px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .login-container button:hover {
      background-color: #33ff33;
    }

    .error-message {
      color: red;
      margin-bottom: 15px;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>LOGIN DA LOJA</h2>

    <?php if (!empty($_SESSION['erro_login'])): ?>
      <p class="error-message">
        <?= $_SESSION['erro_login']; unset($_SESSION['erro_login']); ?>
      </p>
    <?php endif; ?>

    <form method="POST" action="/autenticar">
      <label for="login">Login:</label>
      <input type="text" id="login" name="login" required>

      <label for="senha">Senha:</label>
      <input type="password" id="senha" name="senha" required>

      <button type="submit">ENTRAR</button>
    </form>
  </div>
</body>
</html>

