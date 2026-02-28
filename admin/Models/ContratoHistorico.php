<?php

use Core\Database;

class ContratoHistorico
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function registrar($id_loja, $id_contrato, $data_inicio, $data_fim, $tipo, $status)
    {
        $stmt = $this->db->prepare("
            INSERT INTO lojas_contratos_historico (id_loja, id_contrato, data_inicio_contrato, data_fim_contrato, tipo_contrato, status_contrato)
            VALUES (:id_loja, :id_contrato, :data_inicio, :data_fim, :tipo, :status)
        ");
        $stmt->execute([
            'id_loja' => $id_loja,
            'id_contrato' => $id_contrato,
            'data_inicio' => $data_inicio,
            'data_fim' => $data_fim,
            'tipo' => $tipo,
            'status' => $status
        ]);
    }

	public function listarPorLoja($id_loja)
	{
		$stmt = $this->db->prepare("
			SELECT *
			FROM lojas_contratos_historico
			WHERE id_loja = :id_loja
			ORDER BY data_vinculo DESC
		");
		$stmt->execute(['id_loja' => $id_loja]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

}

