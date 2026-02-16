<?php
class AuthAdmin
{
    public static function check()
    {
        session_start();

        if (!isset($_SESSION['admin_id'])) {
            header('Location: /TCGBALCAO/public/admin.php');
            exit;
        }
    }
}

