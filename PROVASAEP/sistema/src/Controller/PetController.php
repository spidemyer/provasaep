<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Pet;
use App\Model\Tutor;

final class PetController extends Controller
{
    public function listar(): void
    {
        $this->renderizar('pets/listar', [
            'titulo' => 'Pets',
            'pets' => Pet::listarTodos(),
            'tutores' => Tutor::listar(),
            'csrfToken' => $_SESSION['csrf_token'],
        ]);
    }

    public function salvar(): void
    {
        $this->exigirPost();
        $this->validarCsrf();
        $dados = [
            'nome' => trim((string) ($_POST['nome'] ?? '')),
            'especie' => trim((string) ($_POST['especie'] ?? '')),
            'raca' => trim((string) ($_POST['raca'] ?? '')) ?: null,
            'data_nascimento' => trim((string) ($_POST['data_nascimento'] ?? '')) ?: null,
            'tutor_id' => filter_input(INPUT_POST, 'tutor_id', FILTER_VALIDATE_INT),
        ];
        if ($dados['nome'] === '' || $dados['especie'] === '' || !$dados['tutor_id']) {
            throw new \InvalidArgumentException('Informe nome, espécie e tutor do pet.');
        }
        Pet::salvar($dados);
        $this->mensagem('sucesso', 'Pet cadastrado e associado ao tutor.');
        $this->redirecionar('pets');
    }
}