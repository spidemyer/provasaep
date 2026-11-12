<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Agendamento;
use App\Model\Pet;

final class AgendamentoController extends Controller
{
    public function listar(): void
    {
        $agendamentos = Agendamento::listarOrdenadoPorData();
        $this->renderizar('agendamentos/listar', [
            'titulo' => 'Agendamentos',
            'agendamentos' => array_values(array_filter(
                $agendamentos,
                static fn (array $agendamento): bool => !$agendamento['data_passada']
            )),
            'realizados' => array_values(array_filter(
                $agendamentos,
                static fn (array $agendamento): bool => $agendamento['data_passada']
            )),
            'pets' => Pet::listarTodos(),
            'csrfToken' => $_SESSION['csrf_token'],
        ]);
    }

    public function salvar(): void
    {
        $this->exigirPost();
        $this->validarCsrf();
        $petId = filter_input(INPUT_POST, 'pet_id', FILTER_VALIDATE_INT);
        $dataDigitada = trim((string) ($_POST['data_hora'] ?? ''));
        $data = \DateTimeImmutable::createFromFormat('!Y-m-d\TH:i', $dataDigitada);
        $erros = \DateTimeImmutable::getLastErrors();
        if (!$petId || !$data || ($erros !== false && ($erros['warning_count'] > 0 || $erros['error_count'] > 0))) {
            throw new \InvalidArgumentException('Selecione um pet e informe uma data e hora válidas.');
        }
        Agendamento::salvar([
            'pet_id' => $petId,
            'data_hora' => $data->format('Y-m-d H:i:s'),
            'motivo' => trim((string) ($_POST['motivo'] ?? '')) ?: 'Consulta',
            'status' => 'Agendado',
            'observacoes' => trim((string) ($_POST['observacoes'] ?? '')) ?: null,
        ]);
        $this->mensagem('sucesso', 'Agendamento cadastrado.');
        $this->redirecionar('agendamentos');
    }

    public function atualizarData(): void
    {
        $this->exigirPost();
        $this->validarCsrf();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $dataDigitada = trim((string) ($_POST['data_hora'] ?? ''));
        $data = \DateTimeImmutable::createFromFormat('!Y-m-d\TH:i', $dataDigitada);
        $erros = \DateTimeImmutable::getLastErrors();
        if (!$id || !$data || ($erros !== false && ($erros['warning_count'] > 0 || $erros['error_count'] > 0))) {
            throw new \InvalidArgumentException('Informe uma data e hora válidas.');
        }
        if (!Agendamento::atualizarData($id, $data->format('Y-m-d H:i:s'))) {
            throw new \RuntimeException('Agendamento não encontrado ou sem alteração de data.');
        }
        $this->mensagem('sucesso', 'Data do agendamento atualizada.');
        $this->redirecionar('agendamentos');
    }

    public function excluir(): void
    {
        $this->exigirPost();
        $this->validarCsrf();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id || !Agendamento::excluir($id)) {
            throw new \RuntimeException('Agendamento não encontrado.');
        }

        $this->mensagem('sucesso', 'Agendamento excluído.');
        $this->redirecionar('agendamentos');
    }
}
