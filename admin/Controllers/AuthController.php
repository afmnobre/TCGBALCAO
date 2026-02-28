<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../Models/Admin.php';

class AuthController extends Controller
{
    public function index()
    {
        // Se já estiver logado como ADMIN
        if (!empty($_SESSION['ADMIN'])) {
            header("Location: /admin/home");
            exit;
        }

        require_once __DIR__ . '/../Views/auth/login.php';
    }

	public function autenticar()
	{
		$email = $_POST['login'] ?? '';
		$senha = $_POST['senha'] ?? '';

		$adminModel = new Admin();
		$admin = $adminModel->buscarPorEmail($email);

		if ($admin && password_verify($senha, $admin['senha'])) {

			$_SESSION['ADMIN'] = [
				'id' => $admin['id_admin'],
				'nome' => $admin['nome'],
				'email' => $admin['email']
			];

			header("Location: /admin/home");
			exit;
		}

		$_SESSION['erro_login_admin'] = "Login inválido";
		header("Location: /admin");
	}


    public function logout()
    {
        unset($_SESSION['ADMIN']);
        session_destroy();
        header("Location: /admin");
        exit;
    }
}

