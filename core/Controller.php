<?php
class Controller
{
    protected string $baseUrl;
    protected string $publicUrl;
    protected string $storageUrl;

    public function __construct()
    {
        // Base da URL, para /login e /admin funcionar
        $this->baseUrl = '/'; // ajuste se não estiver na raiz
        $this->publicUrl = $this->baseUrl . 'public/';
        $this->storageUrl = $this->baseUrl . 'public/storage/';
    }

    protected function view($view, $data = [])
    {
        extract($data);

        $basePath = (strpos($_SERVER['SCRIPT_NAME'], 'admin.php') !== false)
            ? realpath(__DIR__ . '/../admin/Views')
            : realpath(__DIR__ . '/../app/Views');

        if ($basePath === false) {
            die("BasePath inválido para views");
        }

        $header = $basePath . DIRECTORY_SEPARATOR . 'layout' . DIRECTORY_SEPARATOR . 'header.php';
        $footer = $basePath . DIRECTORY_SEPARATOR . 'layout' . DIRECTORY_SEPARATOR . 'footer.php';
        $file   = $basePath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $view) . '.php';

        if (!file_exists($file)) {
            die("View não encontrada: " . $file);
        }

        require_once $header;
        require_once $file;
        require_once $footer;
    }

    protected function rawView($view, $data = [])
    {
        extract($data);

        $basePath = (strpos($_SERVER['SCRIPT_NAME'], 'admin.php') !== false)
            ? realpath(__DIR__ . '/../admin/Views')
            : realpath(__DIR__ . '/../app/Views');

        if ($basePath === false) {
            die("BasePath inválido para views");
        }

        $baseAssetUrl = '/public';

        $file = $basePath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $view) . '.php';

        if (!file_exists($file)) {
            die("View não encontrada: " . $file);
        }

        require_once $file;
    }

    protected function model($model)
    {
        $basePath = (strpos($_SERVER['SCRIPT_NAME'], 'admin.php') !== false)
            ? realpath(__DIR__ . '/../admin/Models')
            : realpath(__DIR__ . '/../app/Models');

        if ($basePath === false) {
            die("BasePath inválido para models");
        }

        $file = $basePath . DIRECTORY_SEPARATOR . $model . '.php';

        if (!file_exists($file)) {
            die("Model não encontrado: " . $file);
        }

        require_once $file;
        return new $model();
    }
}



