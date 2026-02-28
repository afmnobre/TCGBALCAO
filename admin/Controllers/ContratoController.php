<?php

class ContratoController
{
    // Listar contratos
	public function index() {
		$contratoModel = new Contrato();
		$lojaModel = new Loja();

		$contratos = $contratoModel->listar();
		$lojas = $lojaModel->listar(); // <-- adiciona isso

		ob_start();
		require __DIR__ . '/../Views/contratos/index.php';
		$content = ob_get_clean();

		$title = "Contratos";
		require __DIR__ . '/../Views/layout/layout.php';
	}


    // Formulário create/edit
    public function form($id_contrato = null)
    {
        $contratoModel = new Contrato();
        $lojaModel = new Loja();

        $contrato = $id_contrato ? $contratoModel->buscarPorId($id_contrato) : null;
        $lojas = $lojaModel->listar();

        ob_start();
        require __DIR__ . '/../Views/contratos/form.php';
        $content = ob_get_clean();

        $title = $id_contrato ? "Editar Contrato" : "Cadastrar Contrato";
        require __DIR__ . '/../Views/layout/layout.php';
    }

    // Criar
    public function store()
    {
        $contratoModel = new Contrato();
        $historicoModel = new ContratoHistorico(); // <-- instanciando histórico

        $dados = [
            'id_loja'     => $_POST['id_loja'],
            'tipo'        => $_POST['tipo'],
            'data_inicio' => $_POST['data_inicio'],
            'data_fim'    => $_POST['data_fim'],
            'status'      => $_POST['status']
        ];

        // Criar contrato
        $id_contrato = $contratoModel->criar($dados);

        // Salvar histórico
        $historicoModel->registrar(
            $dados['id_loja'],
            $id_contrato,
            $dados['data_inicio'],
            $dados['data_fim'],
            $dados['tipo'],
            $dados['status']
        );

        header("Location: /admin/contrato");
        exit;
    }

    // Atualizar
    public function update($id_contrato)
    {
        $contratoModel = new Contrato();
        $historicoModel = new ContratoHistorico(); // <-- instanciando histórico

        $dados = [
            'id_contrato' => $id_contrato,
            'id_loja'     => $_POST['id_loja'],
            'tipo'        => $_POST['tipo'],
            'data_inicio' => $_POST['data_inicio'],
            'data_fim'    => $_POST['data_fim'],
            'status'      => $_POST['status']
        ];

        // Atualizar contrato
        $contratoModel->atualizar($dados);

        // Salvar histórico
        $historicoModel->registrar(
            $dados['id_loja'],
            $id_contrato,
            $dados['data_inicio'],
            $dados['data_fim'],
            $dados['tipo'],
            $dados['status']
        );

        header("Location: /admin/contrato");
        exit;
    }

    // Deletar
    public function delete($id_contrato)
    {
        $contratoModel = new Contrato();
        $contratoModel->deletar($id_contrato);

        header("Location: /admin/contrato");
        exit;
    }

	// Retorna o histórico de contratos de uma loja em JSON
	public function historico($id_loja)
	{
		$historicoModel = new ContratoHistorico();
		$historicos = $historicoModel->listarPorLoja($id_loja);

		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($historicos);
		exit;
	}
}

