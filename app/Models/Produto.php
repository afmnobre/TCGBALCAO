<?php
require_once __DIR__ . '/../../core/Database.php';

use Core\Database;

class Produto
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Lista apenas produtos ativos da loja (para pedidos)
     */
    public function listarAtivosPorLoja($id_loja)
    {
        $sql = "SELECT id_produto, nome, emoji, valor_venda,
                    controlar_estoque, estoque_atual, estoque_alerta, ordem_exibicao
                FROM produtos
                WHERE id_loja = :id_loja AND ativo = 1
                ORDER BY ordem_exibicao ASC, nome ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_loja' => $id_loja]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lista todos os produtos da loja (ativos e desativados, para CRUD)
     */
    public function listarTodosPorLoja($id_loja)
    {
        $sql = "SELECT *
                FROM produtos
                WHERE id_loja = :id_loja
                ORDER BY ordem_exibicao ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_loja' => $id_loja]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

	public function criar($dados)
	{
		// Busca a maior ordem atual para a loja
		$sqlOrdem = "SELECT MAX(ordem_exibicao) as ultima FROM produtos WHERE id_loja = :id_loja";
		$stmtOrdem = $this->db->prepare($sqlOrdem);
		$stmtOrdem->execute(['id_loja' => $dados['id_loja']]);
		$res = $stmtOrdem->fetch(PDO::FETCH_ASSOC);
		$novaOrdem = ($res['ultima'] ?? 0) + 1;

		$sql = "INSERT INTO produtos
				   (id_loja, nome, emoji, valor_venda, valor_compra, controlar_estoque, estoque_atual, id_fornecedor, ativo, ordem_exibicao)
				VALUES
				   (:id_loja, :nome, :emoji, :valor_venda, :valor_compra, :controlar_estoque, :estoque_atual, :id_fornecedor, :ativo, :ordem)";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([
			'id_loja'           => $dados['id_loja'],
			'nome'              => $dados['nome'],
			'emoji'             => $dados['emoji'],
			'valor_venda'       => $dados['valor_venda'],
			'valor_compra'      => $dados['valor_compra'],
			'controlar_estoque' => $dados['controlar_estoque'],
			'estoque_atual'     => $dados['estoque_atual'],
			'id_fornecedor'     => $dados['id_fornecedor'],
			'ativo'             => $dados['ativo'],
			'ordem'             => $novaOrdem // Ordem automática
		]);
	}

    public function buscar($id_produto, $id_loja)
    {
        $sql = "SELECT * FROM produtos
                WHERE id_produto = :id_produto
                AND id_loja = :id_loja";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_produto' => $id_produto,
            'id_loja'    => $id_loja
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($dados)
    {
        $sql = "UPDATE produtos SET
                    nome = :nome,
                    emoji = :emoji,
                    valor_venda = :valor_venda,
                    valor_compra = :valor_compra,
                    controlar_estoque = :controlar_estoque,
                    estoque_atual = :estoque_atual,
                    estoque_alerta = :estoque_alerta,
                    ordem_exibicao = :ordem_exibicao,
                    id_fornecedor = :id_fornecedor
                WHERE id_produto = :id_produto";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'id_produto'        => $dados['id_produto'],
            'nome'              => $dados['nome'],
            'emoji'             => $dados['emoji'],
            'valor_venda'       => $dados['valor_venda'],
            'valor_compra'      => $dados['valor_compra'],
            'controlar_estoque' => $dados['controlar_estoque'],
            'estoque_atual'     => $dados['estoque_atual'],
            'estoque_alerta'    => $dados['estoque_alerta'],
            'ordem_exibicao'    => $dados['ordem_exibicao'],
            'id_fornecedor'     => $dados['id_fornecedor']
        ]);
    }

    public function ativar($id_produto, $id_loja)
    {
        $sql = "UPDATE produtos
            SET ativo = 1
            WHERE id_produto = :id_produto
            AND id_loja = :id_loja";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
        'id_produto' => $id_produto,
        'id_loja'    => $id_loja
        ]);
    }

    public function desativar($id_produto, $id_loja)
    {
        $sql = "UPDATE produtos
                SET ativo = 0
                WHERE id_produto = :id_produto
                AND id_loja = :id_loja";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_produto' => $id_produto,
            'id_loja'    => $id_loja
        ]);
    }
    public function atualizarOrdem($id_produto, $ordem)
    {
        $sql = "UPDATE produtos SET ordem_exibicao = :ordem WHERE id_produto = :id_produto";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'ordem'      => $ordem,
            'id_produto' => $id_produto
        ]);
    }
    public function atualizarEstoque($id_produto, $quantidade)
    {
        $sql = "UPDATE produtos
                SET estoque_atual = estoque_atual + :quantidade
                WHERE id_produto = :id_produto AND controlar_estoque = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'quantidade' => $quantidade,
            'id_produto' => $id_produto
        ]);
    }
    public function estoqueAtual($id_loja)
    {
        $sql = "SELECT nome, estoque_atual
                FROM produtos
                WHERE id_loja = :id_loja AND controlar_estoque = 1
                ORDER BY nome ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_loja' => $id_loja]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }


}


