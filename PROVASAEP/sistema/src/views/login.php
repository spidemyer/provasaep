<section class="login-card">
    <span class="eyebrow">Área da equipe</span>
    <h1>Entrar no VetCare</h1>
    <p class="muted">Acesse a gestão de tutores, pets e consultas.</p>
    <form method="post" action="?rota=login" class="form-stack">
        <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">
        <label>E-mail
            <input type="email" name="email" autocomplete="username" required autofocus>
        </label>
        <label>Senha
            <input type="password" name="senha" autocomplete="current-password" required>
        </label>
        <button class="button button-primary button-wide" type="submit">Entrar</button>
    </form>
</section>
