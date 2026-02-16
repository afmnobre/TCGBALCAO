<?php
class AuthMiddleware
{
    public static function verificarLogin()
    {
        if (empty($_SESSION['LOJA'])) {
            header("Location: /login");
            exit;
        }
    }
}

