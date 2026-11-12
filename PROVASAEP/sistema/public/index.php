<?php
declare(strict_types=1);

use App\Controller\AgendamentoController;
use App\Controller\AuthController;
use App\Controller\HomeController;
use App\Controller\PetController;
use App\Controller\TutorController;

ini_set('session.use_strict_mode', '1');
session_set_cookie_params([
    'httponly' => true,
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'samesite' => 'Lax',
    'path' => '/',
]);
session_start();

spl_autoload_register(static function (string $classe): void {
    $prefixo = 'App\\';
    if (!str_starts_with($classe, $prefixo)) {
        return;
    }
    $arquivo = dirname(__DIR__) . '/src/' . str_replace('\\', '/', substr($classe, strlen($prefixo))) . '.php';
    if (is_file($arquivo)) {
        require $arquivo;
    }
});

function h(mixed $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (isset($_SESSION['usuario'])) {
    if (time() - (int) ($_SESSION['ultima_atividade'] ?? 0) > 1800) {
        $_SESSION = [];
        session_destroy();
        session_start();
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['mensagem'] = ['tipo' => 'erro', 'texto' => 'Sua sessão expirou. Entre novamente.'];
    } else {
        $_SESSION['ultima_atividade'] = time();
    }
}

$rota = (string) ($_GET['rota'] ?? 'inicio');
$rotas = [
    'login' => [AuthController::class, 'login', false],
    'sair' => [AuthController::class, 'logout', true],
    'inicio' => [HomeController::class, 'index', true],
    'tutores' => [TutorController::class, 'listar', true],
    'tutor-novo' => [TutorController::class, 'formulario', true],
    'tutor-editar' => [TutorController::class, 'editar', true],
    'tutor-salvar' => [TutorController::class, 'salvar', true],
    'tutor-atualizar' => [TutorController::class, 'atualizar', true],
    'tutor-excluir' => [TutorController::class, 'excluir', true],
    'pets' => [PetController::class, 'listar', true],
    'pet-salvar' => [PetController::class, 'salvar', true],
    'agendamentos' => [AgendamentoController::class, 'listar', true],
    'agendamento-salvar' => [AgendamentoController::class, 'salvar', true],
    'agendamento-data' => [AgendamentoController::class, 'atualizarData', true],
    'agendamento-excluir' => [AgendamentoController::class, 'excluir', true],
];

try {
    if (!isset($rotas[$rota])) {
        http_response_code(404);
        throw new RuntimeException('Página não encontrada.');
    }
    [$classe, $metodo, $requerAutenticacao] = $rotas[$rota];
    if ($requerAutenticacao && !isset($_SESSION['usuario'])) {
        header('Location: ?rota=login');
        exit;
    }
    $controller = new $classe();
    $controller->$metodo();
} catch (InvalidArgumentException $erro) {
    $_SESSION['mensagem'] = ['tipo' => 'erro', 'texto' => $erro->getMessage()];
    http_response_code(303);
    header('Location: ?rota=' . rawurlencode(match ($rota) {
        'tutor-salvar', 'tutor-atualizar', 'tutor-excluir' => 'tutores',
        'pet-salvar' => 'pets',
        'agendamento-salvar', 'agendamento-data', 'agendamento-excluir' => 'agendamentos',
        default => 'login',
    }));
    exit;
} catch (Throwable $erro) {
    error_log((string) $erro);
    http_response_code(http_response_code() < 400 ? 500 : http_response_code());
    $mensagem = $erro instanceof RuntimeException
        ? $erro->getMessage()
        : 'Não foi possível concluir a operação. Verifique a conexão e a configuração do sistema.';
    echo '<!doctype html><html lang="pt-BR"><meta charset="utf-8"><title>Erro</title>'
        . '<body><main><h1>Não foi possível abrir esta página</h1><p>' . h($mensagem) . '</p>'
        . '<p><a href="?rota=inicio">Voltar ao sistema</a></p></main></body></html>';
}
