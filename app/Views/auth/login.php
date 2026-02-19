<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Login da Loja</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');

    body {
      font-family: 'Press Start 2P', monospace;
      background-color: #0d0d0d; /* fundo preto elegante */
      color: #33ff66; /* verde suave estilo terminal */
      height: 100vh;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-container {
      background-color: #111;
      border: 1px solid #33ff66;
      padding: 40px;
      border-radius: 6px;
      box-shadow: 0 0 15px rgba(51,255,102,0.4);
      width: 400px;
      text-align: center;
    }

    .login-container h2 {
      margin-bottom: 20px;
      text-shadow: 0 0 5px #33ff66;
      font-size: 16px;
    }

    .login-container label {
      display: block;
      text-align: left;
      margin-bottom: 5px;
      font-size: 10px;
    }

    .login-container input {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #33ff66;
      border-radius: 4px;
      background-color: #000;
      color: #33ff66;
      font-family: 'Press Start 2P', monospace;
      font-size: 12px;
    }

    .login-container input:focus {
      outline: none;
      box-shadow: 0 0 10px #33ff66;
    }

    .login-container button {
      width: 100%;
      padding: 14px;
      background-color: transparent;
      border: 1px solid #33ff66;
      border-radius: 4px;
      color: #33ff66;
      font-family: 'Press Start 2P', monospace;
      font-size: 14px;
      cursor: pointer;
      animation: blink 2s infinite; /* animação arcade */
    }

    @keyframes blink {
      0%, 50%, 100% { opacity: 1; }
      25%, 75% { opacity: 0; }
    }

    .error-message {
      color: red;
      margin-bottom: 15px;
      font-weight: bold;
      font-size: 10px;
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
      <label for="login">LOGIN:</label>
      <input type="text" id="login" name="login" required>

      <label for="senha">SENHA:</label>
      <input type="password" id="senha" name="senha" required>

      <button type="submit">PRESS START</button>
    </form>
  </div>
</body>
</html>

