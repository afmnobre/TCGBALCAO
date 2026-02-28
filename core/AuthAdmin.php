<?php

class AuthAdmin
{
    public static function verificarLogin()
    {
        if (empty($_SESSION['ADMIN'])) {
            header("Location: /admin/auth");
            exit;
        }
    }
}

