<?php
require_once __DIR__ . '/../../core/Database.php';

use Core\Database;

class Loja
{
	public static function buscarPorLogin($email)
	{
		$db = Database::getInstance();
		// Adicionei u.nome abaixo
		$sql = "SELECT l.*, u.id_usuario, u.nome, u.senha, u.perfil
				FROM lojas l
				INNER JOIN usuarios_loja u ON l.id_loja = u.id_loja
				WHERE u.email = :email AND u.ativo = 1";
		$stmt = $db->prepare($sql);
		$stmt->execute(['email' => $email]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

    public static function buscarPorId($id_loja)
    {
        $db = Database::getInstance();
        $sql = "SELECT id_loja, nome_loja, endereco, cnpj, logo, cor_tema, favicon, numero_contrato
                FROM lojas
                WHERE id_loja = :id_loja";
        $stmt = $db->prepare($sql);
        $stmt->execute(['id_loja' => $id_loja]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

