<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Tutor;

final class TutorController extends Controller
{
    public function listar(): void
    {
        $termo = trim((string) ($_GET['busca'] ?? ''));
        $this->renderizar('tutores/listar', [
            'titulo' => 'Tutores',
            'tutores' => Tutor::listar($termo),
            'termo' => $termo,
        ]);
    }

    public function formulario(): void
    {
        $this->renderizar('tutores/formulario', [
            'titulo' => 'Novo tutor',
            'tutor' => null,
            'rotaSalvar' => 'tutor-salvar',
            'csrfToken' => $_SESSION['csrf_token'],
        ]);
    }

    public function editar(): void
    {
        $tutor = Tutor::buscar(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0);
        if ($tutor === null) {
            http_response_code(404);
            throw new \RuntimeException('Tutor não encontrado.');
        }
        $this->renderizar('tutores/formulario', [
            'titulo' => 'Editar tutor',
            'tutor' => $tutor,
            'rotaSalvar' => 'tutor-atualizar',
            'csrfToken' => $_SESSION['csrf_token'],
        ]);
    }

    public function salvar(): void
    {
        $this->exigirPost();
        $this->validarCsrf();
        Tutor::salvar($this->dadosDoFormulario());
        $this->mensagem('sucesso', 'Tutor cadastrado com sucesso.');
        $this->redirecionar('tutores');
    }

    public function atualizar(): void
    {
        $this->exigirPost();
        $this->validarCsrf();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id || Tutor::buscar($id) === null) {
            http_response_code(404);
            throw new \RuntimeException('Tutor não encontrado.');
        }
        Tutor::salvar($this->dadosDoFormulario(), $id);
        $this->mensagem('sucesso', 'Dados do tutor atualizados.');
        $this->redirecionar('tutores');
    }

    public function excluir(): void
    {
        $this->exigirPost();
        $this->validarCsrf();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id || Tutor::buscar($id) === null) {
            throw new \InvalidArgumentException('Tutor não encontrado.');
        }
        if (!Tutor::excluir($id)) {
            throw new \InvalidArgumentException(
                'Não é possível excluir um tutor que possui pets cadastrados.'
            );
        }

        $this->mensagem('sucesso', 'Tutor excluído.');
        $this->redirecionar('tutores');
    }

    private function dadosDoFormulario(): array
    {
        $dados = [
            'nome' => trim((string) ($_POST['nome'] ?? '')),
            'email' => trim((string) ($_POST['email'] ?? '')),
            'telefone' => trim((string) ($_POST['telefone'] ?? '')),
            'endereco' => trim((string) ($_POST['endereco'] ?? '')),
            'cpf' => preg_replace('/\D+/', '', (string) ($_POST['cpf'] ?? '')) ?? '',
        ];
        if ($dados['nome'] === '' || !filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Informe o nome e um e-mail válido.');
        }
        if ($dados['cpf'] !== '' && strlen($dados['cpf']) !== 11) {
            throw new \InvalidArgumentException('O CPF deve conter 11 números.');
        }

        return $dados;
    }
}