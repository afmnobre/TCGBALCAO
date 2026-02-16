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
        require_once __DIR__ . '/../../core/AuthLoja.php';
    }

    public function autenticar()
    {
        $email = $_POST['login'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $loja = Loja::buscarPorLogin($email);

        if ($loja && password_verify($senha, $loja['senha'])) {
            $_SESSION['LOJA'] = [
                'id'   => $loja['id_loja'],
                'nome' => $loja['nome_loja'],
                'logo' => $loja['logo_loja'],
                'cor'  => $loja['cor_tema']
            ];

            $_SESSION['usuario_id'] = $loja['id_usuario'];
            $_SESSION['perfil']     = $loja['perfil'];
            $_SESSION['loja_id']    = $loja['id_loja'];

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
        unset($_SESSION['usuario_id']);
        unset($_SESSION['perfil']);
        unset($_SESSION['loja_id']);
        session_destroy();
        header("Location: /login");
    }
}

