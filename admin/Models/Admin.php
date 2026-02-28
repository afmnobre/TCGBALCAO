<?php

require_once __DIR__ . '/../../core/Database.php';

use Core\Database;

class Admin
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function buscarPorEmail($email)
    {
        $sql = "SELECT * FROM admin_lojas WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        return $stmt->fetch();
    }
}

