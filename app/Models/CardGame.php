<?php

require_once __DIR__ . '/../../core/Database.php';
use Core\Database;

class CardGame
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function listarTodos()
    {
        $stmt = $this->db->query("SELECT * FROM cardgames ORDER BY nome");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

