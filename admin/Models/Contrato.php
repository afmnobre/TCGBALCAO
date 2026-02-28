<?php

use Core\Database;

class Contrato
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

	// Listar todos os contratos (opcional filtro por loja) com nome e logo da loja
	public function listar($id_loja = null)
	{
		if ($id_loja) {
			$stmt = $this->db->prepare("
				SELECT c.*, l.nome_loja, l.logo
				FROM contratos c
				LEFT JOIN lojas l ON c.id_loja = l.id_loja
				WHERE c.id_loja = :id_loja
				ORDER BY c.data_inicio DESC
			");
			$stmt->execute(['id_loja' => $id_loja]);
		} else {
			$stmt = $this->db->query("
				SELECT c.*, l.nome_loja, l.logo
				FROM contratos c
				LEFT JOIN lojas l ON c.id_loja = l.id_loja
				ORDER BY c.data_inicio DESC
			");
		}

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

    // Buscar contrato por id
    public function buscarPorId($id_contrato)
    {
        $stmt = $this->db->prepare("SELECT * FROM contratos WHERE id_contrato = :id_contrato");
        $stmt->execute(['id_contrato' => $id_contrato]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Criar contrato
    public function criar($dados)
    {
        // Se for ativo, desativa outros contratos da mesma loja
        if ($dados['status'] === 'ativo') {
            $stmt = $this->db->prepare("UPDATE contratos SET status = 'suspenso' WHERE id_loja = :id_loja AND status = 'ativo'");
            $stmt->execute(['id_loja' => $dados['id_loja']]);
        }

        $sql = "INSERT INTO contratos (id_loja, tipo, data_inicio, data_fim, status)
                VALUES (:id_loja, :tipo, :data_inicio, :data_fim, :status)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_loja'     => $dados['id_loja'],
            'tipo'        => $dados['tipo'],
            'data_inicio' => $dados['data_inicio'],
            'data_fim'    => $dados['data_fim'],
            'status'      => $dados['status']
        ]);

        $id_contrato = $this->db->lastInsertId();

        // Atualiza status dos usuários da loja
        $this->atualizarUsuariosPorStatus($dados['id_loja'], $dados['status']);

        return $id_contrato;
    }

    // Atualizar contrato
    public function atualizar($dados)
    {
        // Se alterar para ativo, suspende outros contratos
        if ($dados['status'] === 'ativo') {
            $stmt = $this->db->prepare("UPDATE contratos SET status = 'suspenso' WHERE id_loja = :id_loja AND id_contrato != :id_contrato AND status = 'ativo'");
            $stmt->execute([
                'id_loja'     => $dados['id_loja'],
                'id_contrato' => $dados['id_contrato']
            ]);
        }

        $sql = "UPDATE contratos SET
                    tipo = :tipo,
                    data_inicio = :data_inicio,
                    data_fim = :data_fim,
                    status = :status
                WHERE id_contrato = :id_contrato";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'tipo'        => $dados['tipo'],
            'data_inicio' => $dados['data_inicio'],
            'data_fim'    => $dados['data_fim'],
            'status'      => $dados['status'],
            'id_contrato' => $dados['id_contrato']
        ]);

        // Atualiza status dos usuários da loja
        $this->atualizarUsuariosPorStatus($dados['id_loja'], $dados['status']);
    }

    // Deletar contrato
    public function deletar($id_contrato)
    {
        $contrato = $this->buscarPorId($id_contrato);
        if ($contrato) {
            $stmt = $this->db->prepare("DELETE FROM contratos WHERE id_contrato = :id_contrato");
            $stmt->execute(['id_contrato' => $id_contrato]);

            // Atualiza usuários da loja se contrato ativo
            if ($contrato['status'] === 'ativo') {
                $this->atualizarUsuariosPorStatus($contrato['id_loja'], 'suspenso');
            }
        }
    }

    // Atualiza status dos usuários de uma loja
    private function atualizarUsuariosPorStatus($id_loja, $statusContrato)
    {
        $ativo = $statusContrato === 'ativo' ? 1 : 0;
        $stmt = $this->db->prepare("UPDATE usuarios_loja SET ativo = :ativo WHERE id_loja = :id_loja");
        $stmt->execute([
            'ativo'   => $ativo,
            'id_loja' => $id_loja
        ]);
    }

	public function listarAtivos()
	{
		$stmt = $this->db->query("SELECT * FROM contratos WHERE status = 'ativo' ORDER BY data_inicio DESC");
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

}

