<?php

class LojaController
{
    private $uploadBasePath;

    public function __construct()
    {
        // Caminho absoluto para pastas de uploads de lojas dentro do public
        $this->uploadBasePath = __DIR__ . '/../../public/storage/uploads/lojas/';
    }

    /* =========================================================
       LISTAR LOJAS
    ==========================================================*/
    public function index()
    {
        $lojaModel = new Loja();
        $lojas = $lojaModel->listar();

        $storageUrl = '/storage/uploads/lojas/';

        ob_start();
        require __DIR__ . '/../Views/lojas/index.php';
        $content = ob_get_clean();

        $title = "Lojas";
        require __DIR__ . '/../Views/layout/layout.php';
    }

    /* =========================================================
       FORM CREATE
    ==========================================================*/
    public function create()
    {
        ob_start();
        require __DIR__ . '/../Views/lojas/form.php';
        $content = ob_get_clean();

        $title = "Cadastrar Loja";
        require __DIR__ . '/../Views/layout/layout.php';
    }

    /* =========================================================
       STORE
    ==========================================================*/
	/* =========================================================
	   STORE
	==========================================================*/
	public function store()
	{
		$lojaModel = new Loja();

		// 1️⃣ Dados da loja (sem número de contrato)
		$dados = [
			'nome_loja' => $_POST['nome_loja'] ?? null,
			'cnpj'      => $_POST['cnpj'] ?? null,
			'endereco'  => $_POST['endereco'] ?? null,
			'cor_tema'  => $_POST['cor_tema'] ?? null,
		];

		// Cria loja no banco
		$id_loja = $lojaModel->criar($dados);

		// 2️⃣ Caminho da loja
		$lojaPath = $this->uploadBasePath . $id_loja . '/';

		// Cria diretório da loja se não existir
		if (!is_dir($lojaPath)) {
			if (!mkdir($lojaPath, 0755, true)) {
				die("Erro: não foi possível criar o diretório da loja em $lojaPath");
			}
		}

		// 3️⃣ Upload do LOGO
		$logoNome = null;
		if (!empty($_FILES['logo']['name']) && is_uploaded_file($_FILES['logo']['tmp_name'])) {
			$ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
			$logoNome = 'logo.' . strtolower($ext);

			if (!move_uploaded_file($_FILES['logo']['tmp_name'], $lojaPath . $logoNome)) {
				die("Erro: não foi possível salvar o logo");
			}
		}

		// 4️⃣ Upload do FAVICON
		$faviconNome = null;
		if (!empty($_FILES['favicon']['name']) && is_uploaded_file($_FILES['favicon']['tmp_name'])) {
			$ext = pathinfo($_FILES['favicon']['name'], PATHINFO_EXTENSION);
			$faviconNome = 'favicon.' . strtolower($ext);

			if (!move_uploaded_file($_FILES['favicon']['tmp_name'], $lojaPath . $faviconNome)) {
				die("Erro: não foi possível salvar o favicon");
			}
		}

		// 5️⃣ Atualiza imagens no banco
		$lojaModel->atualizarImagens($id_loja, $logoNome, $faviconNome);

		// 6️⃣ Redireciona para lista de lojas
		header("Location: /admin/loja");
		exit;
	}


    /* =========================================================
       FORM EDIT
    ==========================================================*/
    public function edit($id)
    {
        $lojaModel     = new Loja();
        $contratoModel = new Contrato();

        $loja      = $lojaModel->buscarPorId($id);
        $contratos = $contratoModel->listarAtivos();

        ob_start();
        require __DIR__ . '/../Views/lojas/form.php';
        $content = ob_get_clean();

        $title = "Editar Loja";
        require __DIR__ . '/../Views/layout/layout.php';
    }

	/* =========================================================
	   UPDATE
	==========================================================*/
	public function update($id)
	{
		$lojaModel = new Loja();
		$lojaAtual = $lojaModel->buscarPorId($id);

		// 1️⃣ Atualiza dados da loja
		$dados = [
			'nome_loja' => $_POST['nome_loja'],
			'cnpj'      => $_POST['cnpj'],
			'endereco'  => $_POST['endereco'],
			'cor_tema'  => $_POST['cor_tema'],
			'id_loja'   => $id
		];
		$lojaModel->atualizar($dados);

		// 2️⃣ Diretório da loja
		$lojaPath = $this->uploadBasePath . $id;
		if (!is_dir($lojaPath)) {
			if (!mkdir($lojaPath, 0755, true)) {
				die("Erro: não foi possível criar o diretório da loja em {$lojaPath}");
			}
		}

		// Inicializa com os valores atuais
		$logoNome = $lojaAtual['logo'];
		$faviconNome = $lojaAtual['favicon'];

		// 3️⃣ Upload logo
		if (!empty($_FILES['logo']['name'])) {
			if (!empty($lojaAtual['logo']) && file_exists($lojaPath . '/' . $lojaAtual['logo'])) {
				unlink($lojaPath . '/' . $lojaAtual['logo']);
			}

			$ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
			$logoNome = 'logo.' . strtolower($ext);

			if (!move_uploaded_file($_FILES['logo']['tmp_name'], $lojaPath . '/' . $logoNome)) {
				die("Erro: não foi possível enviar o logo.");
			}
		}

		// 4️⃣ Upload favicon
		if (!empty($_FILES['favicon']['name'])) {
			if (!empty($lojaAtual['favicon']) && file_exists($lojaPath . '/' . $lojaAtual['favicon'])) {
				unlink($lojaPath . '/' . $lojaAtual['favicon']);
			}

			$ext = pathinfo($_FILES['favicon']['name'], PATHINFO_EXTENSION);
			$faviconNome = 'favicon.' . strtolower($ext);

			if (!move_uploaded_file($_FILES['favicon']['tmp_name'], $lojaPath . '/' . $faviconNome)) {
				die("Erro: não foi possível enviar o favicon.");
			}
		}

		// 5️⃣ Atualiza imagens no banco de uma vez
		$lojaModel->atualizarImagens($id, $logoNome, $faviconNome);

		header("Location: /admin/loja");
		exit;
	}


	/* =========================================================
	   DELETE
	==========================================================*/
	public function delete($id)
	{
		$lojaModel = new Loja();
		$lojaPath  = $this->uploadBasePath . $id;

		// 1️⃣ Remove diretório da loja e todos os arquivos
		if (is_dir($lojaPath)) {
			$files = glob($lojaPath . '/*'); // pega todos os arquivos
			foreach ($files as $file) {
				if (is_file($file)) {
					unlink($file); // apaga arquivo
				}
			}
			rmdir($lojaPath); // remove o diretório
		}

		// 2️⃣ Remove loja do banco
		$lojaModel->deletar($id);

		// 3️⃣ Redireciona para a lista de lojas
		header("Location: /admin/loja");
		exit;
	}

}

