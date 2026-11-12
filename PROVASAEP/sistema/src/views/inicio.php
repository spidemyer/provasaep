<section class="welcome">
    <span class="eyebrow">Painel principal</span>
    <h1>Olá, <?= h($usuario['nome']) ?>!</h1>
    <p class="muted">Acesse os recursos da clínica e acompanhe os registros.</p>
</section>
<section class="stat-grid" aria-label="Resumo do sistema">
    <article class="stat-card"><span>Tutores</span><strong><?= h($contagens['tutores']) ?></strong><a href="?rota=tutores">Gerenciar tutores →</a></article>
    <article class="stat-card"><span>Pets</span><strong><?= h($contagens['pets']) ?></strong><a href="?rota=pets">Gerenciar pets →</a></article>
    <article class="stat-card"><span>Agendamentos</span><strong><?= h($contagens['agendamentos']) ?></strong><a href="?rota=agendamentos">Ver agenda →</a></article>
</section>
