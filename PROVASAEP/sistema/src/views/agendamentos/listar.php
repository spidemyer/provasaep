<div class="page-heading">
    <div><span class="eyebrow">Atendimento</span><h1>Agendamentos</h1><p class="muted">Consultas futuras e atendimentos já realizados.</p></div>
</div>
<form class="panel form-grid compact-form" method="post" action="?rota=agendamento-salvar">
    <input type="hidden" name="csrf_token" value="<?= h($csrfToken) ?>">
    <h2 class="full-width">Novo agendamento</h2>
    <label>Pet
        <select name="pet_id" required>
            <option value="">Selecione um pet</option>
            <?php foreach ($pets as $pet): ?><option value="<?= h($pet['id']) ?>"><?= h($pet['nome']) ?> — <?= h($pet['tutor_nome']) ?></option><?php endforeach; ?>
        </select>
    </label>
    <label>Data e hora<input type="datetime-local" name="data_hora" required></label>
    <label>Motivo<input name="motivo" maxlength="200" placeholder="Ex.: consulta de rotina"></label>
    <label>Observações<input name="observacoes" maxlength="1000"></label>
    <div class="form-actions"><button class="button button-primary" type="submit">Agendar</button></div>
</form>
<h2 class="section-title">Próximos agendamentos</h2>
<div class="appointment-list">
<?php foreach ($agendamentos as $agendamento): ?>
    <article class="appointment-card">
        <div class="appointment-date"><strong><?= h(date('d/m/Y', strtotime($agendamento['data_hora']))) ?></strong><span><?= h(date('H:i', strtotime($agendamento['data_hora']))) ?></span></div>
        <div class="appointment-details">
            <h2><?= h($agendamento['motivo']) ?> <span class="status"><?= h($agendamento['situacao']) ?></span></h2>
            <p><strong>Pet:</strong> #<?= h($agendamento['pet_id']) ?> · <?= h($agendamento['pet_nome']) ?> · <?= h($agendamento['pet_especie']) ?><?= $agendamento['pet_raca'] ? ' · ' . h($agendamento['pet_raca']) : '' ?> · Nascimento: <?= h($agendamento['pet_data_nascimento'] ? date('d/m/Y', strtotime($agendamento['pet_data_nascimento'])) : 'não informado') ?></p>
            <p><strong>Tutor:</strong> #<?= h($agendamento['tutor_id']) ?> · <?= h($agendamento['tutor_nome']) ?> · <?= h($agendamento['tutor_telefone']) ?> · <?= h($agendamento['tutor_email']) ?></p>
            <p><strong>Endereço:</strong> <?= h($agendamento['tutor_endereco']) ?><?php if ($agendamento['observacoes']): ?> · <strong>Observações:</strong> <?= h($agendamento['observacoes']) ?><?php endif; ?></p>
            <form class="inline-edit" method="post" action="?rota=agendamento-data">
                <input type="hidden" name="csrf_token" value="<?= h($csrfToken) ?>">
                <input type="hidden" name="id" value="<?= h($agendamento['id']) ?>">
                <label>Alterar data e hora<input type="datetime-local" name="data_hora" value="<?= h(date('Y-m-d\TH:i', strtotime($agendamento['data_hora']))) ?>" required></label>
                <button class="button button-secondary" type="submit">Salvar data</button>
            </form>
            <form class="delete-form" method="post" action="?rota=agendamento-excluir" onsubmit="return confirm('Tem certeza de que deseja apagar este agendamento? Esta ação não pode ser desfeita.');">
                <input type="hidden" name="csrf_token" value="<?= h($csrfToken) ?>">
                <input type="hidden" name="id" value="<?= h($agendamento['id']) ?>">
                <button class="button button-danger" type="submit">Apagar agendamento</button>
            </form>
        </div>
    </article>
<?php endforeach; ?>
<?php if ($agendamentos === []): ?><p class="empty-state panel">Nenhum próximo agendamento.</p><?php endif; ?>
</div>
<h2 class="section-title">Agendamentos já realizados</h2>
<div class="appointment-list">
<?php foreach ($realizados as $agendamento): ?>
    <article class="appointment-card completed-card">
        <div class="appointment-date"><strong><?= h(date('d/m/Y', strtotime($agendamento['data_hora']))) ?></strong><span><?= h(date('H:i', strtotime($agendamento['data_hora']))) ?></span></div>
        <div class="appointment-details">
            <h2><?= h($agendamento['motivo']) ?> <span class="status"><?= h($agendamento['situacao']) ?></span></h2>
            <p><strong>Pet:</strong> #<?= h($agendamento['pet_id']) ?> · <?= h($agendamento['pet_nome']) ?> · <?= h($agendamento['pet_especie']) ?><?= $agendamento['pet_raca'] ? ' · ' . h($agendamento['pet_raca']) : '' ?> · Nascimento: <?= h($agendamento['pet_data_nascimento'] ? date('d/m/Y', strtotime($agendamento['pet_data_nascimento'])) : 'não informado') ?></p>
            <p><strong>Tutor:</strong> #<?= h($agendamento['tutor_id']) ?> · <?= h($agendamento['tutor_nome']) ?> · <?= h($agendamento['tutor_telefone']) ?> · <?= h($agendamento['tutor_email']) ?></p>
            <p><strong>Endereço:</strong> <?= h($agendamento['tutor_endereco']) ?><?php if ($agendamento['observacoes']): ?> · <strong>Observações:</strong> <?= h($agendamento['observacoes']) ?><?php endif; ?></p>
            <form class="delete-form" method="post" action="?rota=agendamento-excluir" onsubmit="return confirm('Tem certeza de que deseja apagar este agendamento? Esta ação não pode ser desfeita.');">
                <input type="hidden" name="csrf_token" value="<?= h($csrfToken) ?>">
                <input type="hidden" name="id" value="<?= h($agendamento['id']) ?>">
                <button class="button button-danger" type="submit">Apagar agendamento</button>
            </form>
        </div>
    </article>
<?php endforeach; ?>
<?php if ($realizados === []): ?><p class="empty-state panel">Nenhum agendamento realizado até o momento.</p><?php endif; ?>
</div>
