<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\ConexaoBanco;

final class Pet
{
    public static function listarTodos(): array
    {
        return ConexaoBanco::obter()->query(
            'SELECT p.id, p.nome, p.especie, p.raca, p.data_nascimento, t.nome AS tutor_nome
             FROM pet p INNER JOIN tutor t ON t.id = p.tutor_id
             ORDER BY p.nome'
        )->fetchAll();
    }

    public static function salvar(array $dados): void
    {
        $consulta = ConexaoBanco::obter()->prepare(
            'INSERT INTO pet (nome, especie, raca, data_nascimento, tutor_id)
             VALUES (:nome, :especie, :raca, :data_nascimento, :tutor_id)'
        );
        $consulta->execute($dados);
    }
}