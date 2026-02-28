<?php

use Core\Database;

class Loja
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function listar()
    {
        $sql = "SELECT * FROM lojas ORDER BY id_loja DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function total()
    {
        $sql = "SELECT COUNT(*) as total FROM lojas";
        return $this->db->query($sql)->fetch()['total'];
    }

	public function criar($dados)
	{
		$sql = "INSERT INTO lojas
				(nome_loja, cnpj, endereco, cor_tema, data_cadastro)
				VALUES
				(:nome_loja, :cnpj, :endereco, :cor_tema, NOW())";

		$stmt = $this->db->prepare($sql);
		$stmt->execute($dados);

		return $this->db->lastInsertId();
	}

	// arquivo: admin/Models/Loja.php
	public function atualizar($dados)
	{
		$sql = "UPDATE lojas SET
					nome_loja  = :nome_loja,
					cnpj       = :cnpj,
					endereco   = :endereco,
					cor_tema   = :cor_tema
				WHERE id_loja = :id_loja";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([
			'nome_loja' => $dados['nome_loja'],
			'cnpj'      => $dados['cnpj'],
			'endereco'  => $dados['endereco'],
			'cor_tema'  => $dados['cor_tema'],
			'id_loja'   => $dados['id_loja']
		]);
	}

	public function atualizarImagens($id_loja, $logo, $favicon)
	{
		$sql = "UPDATE lojas
				SET logo = :logo, favicon = :favicon
				WHERE id_loja = :id_loja";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([
			'logo' => $logo,
			'favicon' => $favicon,
			'id_loja' => $id_loja
		]);
	}

    public function buscarPorId($id)
	{
		$stmt = $this->db->prepare("SELECT * FROM lojas WHERE id_loja = ?");
		$stmt->execute([$id]);
		return $stmt->fetch();
	}

    // Model: Loja.php
	public function deletar($id_loja)
	{
		$sql = "DELETE FROM lojas WHERE id_loja = :id_loja";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([
			'id_loja' => $id_loja
		]);
	}

}

