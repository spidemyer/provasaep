<?php
declare(strict_types=1);

namespace App\Core;

final class Criptografia
{
    public static function chave(): string
    {
        return ConexaoBanco::chaveCriptografia();
    }
}