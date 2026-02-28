<?php

use Core\Database;

class UsuarioLoja
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Listar todos os usuários com dados da loja
    public function listarTodos()
    {
        $sql = "SELECT u.*, l.nome_loja
                FROM usuarios_loja u
                JOIN lojas l ON l.id_loja = u.id_loja
                ORDER BY u.id_usuario DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar usuário por ID
    public function buscarPorId($id)
    {
        $sql = "SELECT * FROM usuarios_loja WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_usuario' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Criar usuário
    public function criar($dados)
    {
        $sql = "INSERT INTO usuarios_loja (id_loja, nome, email, senha, perfil, ativo)
                VALUES (:id_loja, :nome, :email, :senha, :perfil, :ativo)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_loja' => $dados['id_loja'],
            'nome'    => $dados['nome'],
            'email'   => $dados['email'],
            'senha'   => password_hash($dados['senha'], PASSWORD_DEFAULT),
            'perfil'  => $dados['perfil'],
            'ativo'   => $dados['ativo'] ?? 1
        ]);
        return $this->db->lastInsertId();
    }

	// Atualizar usuário (sem mexer na senha)
	public function atualizar($dados)
	{
		$sql = "UPDATE usuarios_loja
				SET id_loja = :id_loja,
					nome    = :nome,
					email   = :email,
					perfil  = :perfil,
					ativo   = :ativo
				WHERE id_usuario = :id_usuario";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([
			'id_loja'    => $dados['id_loja'],
			'nome'       => $dados['nome'],
			'email'      => $dados['email'],
			'perfil'     => $dados['perfil'],
			'ativo'      => $dados['ativo'] ?? 1,
			'id_usuario' => $dados['id_usuario']
		]);
	}

    // Deletar usuário
    public function deletar($id)
    {
        $sql = "DELETE FROM usuarios_loja WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_usuario' => $id]);
    }

   	// Atualizar senha de um usuário
	public function atualizarSenha($id_usuario, $novaSenha)
	{
		$sql = "UPDATE usuarios_loja
				SET senha = :senha
				WHERE id_usuario = :id_usuario";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([
			'senha' => password_hash($novaSenha, PASSWORD_DEFAULT),
			'id_usuario' => $id_usuario
		]);
	}

}

