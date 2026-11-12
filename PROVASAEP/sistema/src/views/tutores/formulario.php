<div class="page-heading">
    <div><span class="eyebrow">Cadastros</span><h1><?= h($titulo) ?></h1></div>
    <a class="button button-quiet" href="?rota=tutores">Voltar</a>
</div>
<form class="panel form-grid" method="post" action="?rota=<?= h($rotaSalvar) ?>">
    <input type="hidden" name="csrf_token" value="<?= h($csrfToken) ?>">
    <?php if ($tutor !== null): ?><input type="hidden" name="id" value="<?= h($tutor['id']) ?>"><?php endif; ?>
    <label>Nome completo<input name="nome" value="<?= h($tutor['nome'] ?? '') ?>" maxlength="150" required></label>
    <label>E-mail<input type="email" name="email" value="<?= h($tutor['email'] ?? '') ?>" maxlength="180" required></label>
    <label>Telefone<input name="telefone" value="<?= h($tutor['telefone'] ?? '') ?>" maxlength="30"></label>
    <label>CPF
        <input name="cpf" inputmode="numeric" pattern="[0-9.\- ]{11,14}" maxlength="14" <?= $tutor === null ? 'required' : '' ?>>
        <?php if ($tutor !== null): ?><small>Deixe em branco para manter o CPF criptografado atual.</small><?php endif; ?>
    </label>
    <label class="full-width">Endereço<input name="endereco" value="<?= h($tutor['endereco'] ?? '') ?>" maxlength="250"></label>
    <div class="form-actions"><button class="button button-primary" type="submit">Salvar tutor</button><a class="button button-quiet" href="?rota=tutores">Cancelar</a></div>
</form>
