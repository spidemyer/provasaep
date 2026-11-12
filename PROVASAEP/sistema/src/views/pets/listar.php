<div class="page-heading">
    <div><span class="eyebrow">Cadastros</span><h1>Pets</h1><p class="muted">Todo pet deve estar associado a um tutor.</p></div>
</div>
<form class="panel form-grid compact-form" method="post" action="?rota=pet-salvar">
    <input type="hidden" name="csrf_token" value="<?= h($csrfToken) ?>">
    <h2 class="full-width">Cadastrar pet</h2>
    <label>Nome<input name="nome" maxlength="120" required></label>
    <label>Espécie<input name="especie" placeholder="Ex.: cão, gato" maxlength="60" required></label>
    <label>Raça<input name="raca" maxlength="80"></label>
    <label>Data de nascimento<input type="date" name="data_nascimento"></label>
    <label class="full-width">Tutor
        <select name="tutor_id" required>
            <option value="">Selecione um tutor</option>
            <?php foreach ($tutores as $tutor): ?><option value="<?= h($tutor['id']) ?>"><?= h($tutor['nome']) ?></option><?php endforeach; ?>
        </select>
    </label>
    <div class="form-actions"><button class="button button-primary" type="submit">Cadastrar pet</button></div>
</form>
<h2 class="section-title">Pets cadastrados</h2>
<div class="table-wrap">
<table>
    <thead><tr><th>Nome</th><th>Espécie</th><th>Raça</th><th>Nascimento</th><th>Tutor</th></tr></thead>
    <tbody>
    <?php foreach ($pets as $pet): ?>
        <tr><td><?= h($pet['nome']) ?></td><td><?= h($pet['especie']) ?></td><td><?= h($pet['raca'] ?: '—') ?></td><td><?= h($pet['data_nascimento'] ?: '—') ?></td><td><?= h($pet['tutor_nome']) ?></td></tr>
    <?php endforeach; ?>
    <?php if ($pets === []): ?><tr><td colspan="5" class="empty-state">Nenhum pet cadastrado.</td></tr><?php endif; ?>
    </tbody>
</table>
</div>
