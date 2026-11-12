<?php
declare(strict_types=1);

namespace App\Core;

use PDO;

final class ConexaoBanco
{
    private static ?PDO $conexao = null;

    public static function obter(): PDO
    {
        if (self::$conexao instanceof PDO) {
            return self::$conexao;
        }

        $arquivo = dirname(__DIR__, 2) . '/config/database.ini';
        $configuracao = parse_ini_file($arquivo, true, INI_SCANNER_TYPED);
        if ($configuracao === false || !isset($configuracao['database'])) {
            throw new \RuntimeException('Não foi possível ler config/database.ini.');
        }
        $banco = $configuracao['database'];

        $host = getenv('SAEP_DB_HOST') ?: (string) $banco['db_host'];
        $porta = getenv('SAEP_DB_PORT') ?: (string) $banco['db_port'];
        $nome = getenv('SAEP_DB_NAME') ?: (string) $banco['db_name'];
        $usuario = getenv('SAEP_DB_USER') ?: (string) $banco['db_user'];
        $senha = getenv('SAEP_DB_PASS') ?: (string) $banco['db_pass'];
        $dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s', $host, $porta, $nome);

        self::$conexao = new PDO($dsn, $usuario, $senha, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        return self::$conexao;
    }

    public static function chaveCriptografia(): string
    {
        $configuracao = parse_ini_file(
            dirname(__DIR__, 2) . '/config/database.ini',
            true,
            INI_SCANNER_TYPED
        );
        if ($configuracao === false || !isset($configuracao['database']['encryption_key'])) {
            throw new \RuntimeException('Defina encryption_key na seção [database] de database.ini.');
        }

        return getenv('SAEP_ENCRYPTION_KEY')
            ?: (string) $configuracao['database']['encryption_key'];
    }
}