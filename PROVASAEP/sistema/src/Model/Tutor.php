<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\ConexaoBanco;

final class Tutor
{
    public static function listar(string $termo = ''): array
    {
        $consulta = ConexaoBanco::obter()->prepare(
            "SELECT id, nome, email, telefone, endereco
             FROM tutor
             WHERE :termo = '' OR nome ILIKE :busca_nome OR email ILIKE :busca_email
                   OR telefone ILIKE :busca_telefone
             ORDER BY nome"
        );
        $consulta->execute([
            'termo' => trim($termo),
            'busca_nome' => '%' . trim($termo) . '%',
            'busca_email' => '%' . trim($termo) . '%',
            'busca_telefone' => '%' . trim($termo) . '%',
        ]);

        return $consulta->fetchAll();
    }

    public static function buscar(int $id): ?array
    {
        $consulta = ConexaoBanco::obter()->prepare(
            'SELECT id, nome, email, telefone, endereco FROM tutor WHERE id = :id'
        );
        $consulta->execute(['id' => $id]);
        $tutor = $consulta->fetch();

        return $tutor === false ? null : $tutor;
    }

    public static function salvar(array $dados, ?int $id = null): void
    {
        $conexao = ConexaoBanco::obter();
        $chave = ConexaoBanco::chaveCriptografia();

        if ($id === null) {
            if ($dados['cpf'] === '') {
                throw new \InvalidArgumentException('Informe o CPF do tutor.');
            }
            $consulta = $conexao->prepare(
                'INSERT INTO tutor (nome, email, telefone, endereco, cpf_criptografado)
                 VALUES (:nome, :email, :telefone, :endereco, pgp_sym_encrypt(:cpf, :chave))'
            );
            $consulta->execute([
                'nome' => $dados['nome'],
                'email' => $dados['email'],
                'telefone' => $dados['telefone'],
                'endereco' => $dados['endereco'],
                'cpf' => $dados['cpf'],
                'chave' => $chave,
            ]);
            return;
        }

        if ($dados['cpf'] === '') {
            $consulta = $conexao->prepare(
                'UPDATE tutor SET nome = :nome, email = :email, telefone = :telefone,
                 endereco = :endereco WHERE id = :id'
            );
            $consulta->execute([
                'nome' => $dados['nome'],
                'email' => $dados['email'],
                'telefone' => $dados['telefone'],
                'endereco' => $dados['endereco'],
                'id' => $id,
            ]);
            return;
        }

        $consulta = $conexao->prepare(
            'UPDATE tutor SET nome = :nome, email = :email, telefone = :telefone,
             endereco = :endereco, cpf_criptografado = pgp_sym_encrypt(:cpf, :chave)
             WHERE id = :id'
        );
        $consulta->execute([
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'telefone' => $dados['telefone'],
            'endereco' => $dados['endereco'],
            'cpf' => $dados['cpf'],
            'chave' => $chave,
            'id' => $id,
        ]);
    }

    public static function excluir(int $id): bool
    {
        $consulta = ConexaoBanco::obter()->prepare(
            'DELETE FROM tutor
             WHERE id = :id
               AND NOT EXISTS (SELECT 1 FROM pet WHERE tutor_id = :tutor_id)'
        );
        $consulta->execute(['id' => $id, 'tutor_id' => $id]);

        return $consulta->rowCount() > 0;
    }
}