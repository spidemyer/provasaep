<div class="page-heading">
    <div><span class="eyebrow">Cadastros</span><h1>Tutores</h1><p class="muted">Pesquise, consulte e atualize os dados dos responsáveis.</p></div>
    <a class="button button-primary" href="?rota=tutor-novo">Novo tutor</a>
</div>
<form class="search-bar" method="get" action="">
    <input type="hidden" name="rota" value="tutores">
    <label class="sr-only" for="busca">Buscar tutor</label>
    <input id="busca" name="busca" value="<?= h($termo) ?>" placeholder="Nome, e-mail ou telefone">
    <button class="button button-secondary" type="submit">Buscar</button>
    <?php if ($termo !== ''): ?><a class="button button-quiet" href="?rota=tutores">Limpar</a><?php endif; ?>
</form>
<div class="table-wrap">
<table>
    <thead><tr><th>Nome</th><th>E-mail</th><th>Telefone</th><th>Endereço</th><th>Ações</th></tr></thead>
    <tbody>
    <?php foreach ($tutores as $tutor): ?>
        <tr>
            <td><?= h($tutor['nome']) ?></td><td><?= h($tutor['email']) ?></td>
            <td><?= h($tutor['telefone']) ?></td><td><?= h($tutor['endereco']) ?></td>
            <td class="table-actions">
                <a class="text-link" href="?rota=tutor-editar&id=<?= h($tutor['id']) ?>">Editar</a>
                <form method="post" action="?rota=tutor-excluir" onsubmit="return confirm('Deseja excluir este tutor? Tutores com pets cadastrados não podem ser excluídos.');">
                    <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">
                    <input type="hidden" name="id" value="<?= h($tutor['id']) ?>">
                    <button class="text-button" type="submit">Excluir</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if ($tutores === []): ?><tr><td colspan="5" class="empty-state">Nenhum tutor encontrado.</td></tr><?php endif; ?>
    </tbody>
</table>
</div>
