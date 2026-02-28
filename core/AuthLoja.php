<?php

class AuthLoja
{
    public static function verificarLogin()
    {
        if (empty($_SESSION['LOJA'])) {
            header("Location: /login");
            exit;
        }
    }
}

