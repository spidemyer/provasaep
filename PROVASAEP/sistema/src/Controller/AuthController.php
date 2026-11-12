<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Usuario;

final class AuthController extends Controller
{
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validarCsrf();
            $email = trim((string) ($_POST['email'] ?? ''));
            $senha = (string) ($_POST['senha'] ?? '');
            $usuario = filter_var($email, FILTER_VALIDATE_EMAIL)
                ? Usuario::buscarPorEmail($email)
                : null;

            if ($usuario === null || !password_verify($senha, $usuario['senha_hash'])) {
                $this->mensagem('erro', 'E-mail ou senha inválidos.');
                $this->redirecionar('login');
            }

            session_regenerate_id(true);
            $_SESSION['usuario'] = [
                'id' => (int) $usuario['id'],
                'nome' => $usuario['nome'],
                'email' => $usuario['email'],
            ];
            $_SESSION['ultima_atividade'] = time();
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $this->redirecionar('inicio');
        }

        $this->renderizar('login', ['titulo' => 'Acessar o sistema', 'autenticacao' => true]);
    }

    public function logout(): void
    {
        $this->exigirPost();
        $this->validarCsrf();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $parametros = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $parametros['path'], $parametros['domain'], $parametros['secure'], $parametros['httponly']);
        }
        session_destroy();
        header('Location: ?rota=login');
        exit;
    }
}