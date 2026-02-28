<?php
class UsuariolojaController
{
    /* =========================================================
       LISTAR USUÁRIOS
    ==========================================================*/
public function index()
{
    $usuarioModel = new UsuarioLoja();
    $usuarios = $usuarioModel->listarTodos();

    ob_start();
    require __DIR__ . '/../Views/usuarios_loja/index.php';
    $content = ob_get_clean();

    $title = "Usuários da Loja";
    require __DIR__ . '/../Views/layout/layout.php';
}


    /* =========================================================
       FORM CREATE / EDIT
    ==========================================================*/
    public function form($id_usuario = null)
    {
        $usuarioModel = new UsuarioLoja();
        $lojaModel    = new Loja();

        $usuario = $id_usuario ? $usuarioModel->buscarPorId($id_usuario) : null;
        $lojas   = $lojaModel->listar();

        ob_start();
        require __DIR__ . '/../Views/usuarios_loja/form.php';
        $content = ob_get_clean();

        $title = $id_usuario ? "Editar Usuário" : "Cadastrar Usuário";
        require __DIR__ . '/../Views/layout/layout.php';
    }

    /* =========================================================
       STORE
    ==========================================================*/
    public function store()
    {
        $usuarioModel = new UsuarioLoja();

        $dados = [
            'id_loja' => $_POST['id_loja'],
            'nome'    => $_POST['nome'],
            'email'   => $_POST['email'],
            'senha'   => $_POST['senha'],
            'perfil'  => $_POST['perfil'],
            'ativo'   => $_POST['ativo'] ?? 1
        ];

        $usuarioModel->criar($dados);

        header("Location: /admin/usuarioLoja");
        exit;
    }

    /* =========================================================
       UPDATE
    ==========================================================*/
	public function update($id_usuario)
	{
		$usuarioModel = new UsuarioLoja();

		$dados = [
			'id_usuario' => $id_usuario,
			'id_loja'    => $_POST['id_loja'] ?? null,
			'nome'       => $_POST['nome'] ?? '',
			'email'      => $_POST['email'] ?? '',
			'perfil'     => $_POST['perfil'] ?? '',
			'ativo'      => $_POST['ativo'] ?? 1
		];

		// Validação básica
		if (empty($dados['id_loja']) || empty($dados['nome']) || empty($dados['email']) || empty($dados['perfil'])) {
			die("Erro: Campos obrigatórios não foram preenchidos.");
		}

		$usuarioModel->atualizar($dados);

		// Atualizar senha se enviada
		if (!empty($_POST['senha'])) {
			$usuarioModel->atualizarSenha($id_usuario, $_POST['senha']);
		}

		header("Location: /admin/usuarioLoja");
		exit;
	}



    /* =========================================================
       DELETE
    ==========================================================*/
    public function delete($id_usuario)
    {
        $usuarioModel = new UsuarioLoja();
        $usuarioModel->deletar($id_usuario);

        header("Location: /admin/usuarioLoja");
        exit;
    }
}

