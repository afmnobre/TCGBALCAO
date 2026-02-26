<?php

require_once __DIR__ . '/../Models/Loja.php';
require_once __DIR__ . '/../Models/Usuarios.php';

class AuthController extends Controller
{
    public function login()
    {
        // Se já está logado, não mostra o formulário
        if (!empty($_SESSION['LOJA'])) {
            header("Location: /home");
            exit;
        }

        // Só mostra o formulário se não estiver logado
        require_once __DIR__ . '/../../app/Views/auth/login.php';
    }

	public function autenticar()
	{
		$email = $_POST['login'] ?? '';
		$senha = $_POST['senha'] ?? '';

		$loja = Loja::buscarPorLogin($email);

		if ($loja && password_verify($senha, $loja['senha'])) {
			$_SESSION['LOJA'] = [
				'id_loja'    => $loja['id_loja'],
				'nome_loja'  => $loja['nome_loja'],
				'logo'       => $loja['logo'],
				'cor_tema'   => $loja['cor_tema'],
				'favicon'    => $loja['favicon'] ?? null
			];

			// Certifique-se de que 'nome' existe no array $loja retornado do banco
			$_SESSION['USUARIO'] = [
				'id_usuario' => $loja['id_usuario'],
				'nome'       => $loja['nome'] ?? 'Usuário sem nome',
				'perfil'     => $loja['perfil'],
				'email'      => $email
			];

			header("Location: /home");
			exit;
		}

        $_SESSION['erro_login'] = "Login inválido";
        header("Location: /login");
    }

    public function logout()
    {
        session_start();
        unset($_SESSION['LOJA']);
        unset($_SESSION['USUARIO']);
        session_destroy();
        header("Location: /login");
    }
}


