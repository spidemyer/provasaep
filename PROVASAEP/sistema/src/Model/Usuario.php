<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\ConexaoBanco;

final class Usuario
{
    public static function buscarPorEmail(string $email): ?array
    {
        $consulta = ConexaoBanco::obter()->prepare(
            'SELECT id, nome, email, senha_hash FROM usuario WHERE email = :email'
        );
        $consulta->execute(['email' => strtolower(trim($email))]);
        $usuario = $consulta->fetch();

        return $usuario === false ? null : $usuario;
    }
}