<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\ConexaoBanco;

final class Agendamento
{
    public static function listarOrdenadoPorData(): array
    {
        return ConexaoBanco::obter()->query(
            "SELECT a.id, a.data_hora, a.motivo, a.status, a.observacoes,
                    CASE
                        WHEN a.status = 'Cancelado' THEN 'Cancelado'
                        WHEN a.data_hora < CURRENT_TIMESTAMP THEN 'Realizado'
                        ELSE a.status
                    END AS situacao,
                    (a.data_hora < CURRENT_TIMESTAMP) AS data_passada,
                    t.id AS tutor_id, t.nome AS tutor_nome, t.email AS tutor_email,
                    t.telefone AS tutor_telefone, t.endereco AS tutor_endereco,
                    p.id AS pet_id, p.nome AS pet_nome, p.especie AS pet_especie,
                    p.raca AS pet_raca, p.data_nascimento AS pet_data_nascimento
             FROM agendamento a
             INNER JOIN pet p ON p.id = a.pet_id
             INNER JOIN tutor t ON t.id = p.tutor_id
             ORDER BY a.data_hora ASC"
        )->fetchAll();
    }

    public static function salvar(array $dados): void
    {
        $consulta = ConexaoBanco::obter()->prepare(
            'INSERT INTO agendamento (pet_id, data_hora, motivo, status, observacoes)
             VALUES (:pet_id, :data_hora, :motivo, :status, :observacoes)'
        );
        $consulta->execute($dados);
    }

    public static function atualizarData(int $id, string $dataHora): bool
    {
        $consulta = ConexaoBanco::obter()->prepare(
            'UPDATE agendamento SET data_hora = :data_hora WHERE id = :id'
        );
        $consulta->execute(['data_hora' => $dataHora, 'id' => $id]);

        return $consulta->rowCount() > 0;
    }

    public static function excluir(int $id): bool
    {
        $consulta = ConexaoBanco::obter()->prepare(
            'DELETE FROM agendamento WHERE id = :id'
        );
        $consulta->execute(['id' => $id]);

        return $consulta->rowCount() > 0;
    }
}