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

    public function buscar($id_cardgame)
    {
        $stmt = $this->db->prepare("SELECT * FROM cardgames WHERE id_cardgame = ?");
        $stmt->execute([$id_cardgame]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

