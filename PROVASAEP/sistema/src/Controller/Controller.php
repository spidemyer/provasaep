<?php
declare(strict_types=1);

namespace App\Controller;

abstract class Controller
{
    protected function renderizar(string $visao, array $dados = []): void
    {
        extract($dados, EXTR_SKIP);
        ob_start();
        require dirname(__DIR__) . '/views/' . $visao . '.php';
        $conteudo = (string) ob_get_clean();
        require dirname(__DIR__) . '/views/layout.php';
    }

    protected function redirecionar(string $rota): never
    {
        header('Location: ?rota=' . rawurlencode($rota));
        exit;
    }

    protected function mensagem(string $tipo, string $texto): void
    {
        $_SESSION['mensagem'] = ['tipo' => $tipo, 'texto' => $texto];
    }

    public function validarCsrf(): void
    {
        $token = (string) ($_POST['csrf_token'] ?? '');
        if ($token === '' || !hash_equals((string) ($_SESSION['csrf_token'] ?? ''), $token)) {
            http_response_code(419);
            throw new \RuntimeException('A sessão do formulário expirou. Atualize a página e tente novamente.');
        }
    }

    protected function exigirPost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            throw new \RuntimeException('Método não permitido.');
        }
    }
}