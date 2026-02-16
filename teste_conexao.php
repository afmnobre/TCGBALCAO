<?php

require 'core/Database.php';

use Core\Database;

try {
    $db = Database::getInstance();

    $stmt = $db->query("SELECT 1");
    $stmt->fetch();

    echo "✅ CONEXÃO COM BANCO OK!";
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage();
}

