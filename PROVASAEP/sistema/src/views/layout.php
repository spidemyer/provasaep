<?php
$mensagemFlash = $_SESSION['mensagem'] ?? null;
unset($_SESSION['mensagem']);
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($titulo ?? 'Clínica veterinária') ?> | VetCare</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php if (empty($autenticacao)): ?>
<header class="topbar">
    <a class="brand" href="?rota=inicio">VetCare <span>Clínica veterinária</span></a>
    <nav aria-label="Navegação principal">
        <a href="?rota=inicio">Início</a>
        <a href="?rota=tutores">Tutores</a>
        <a href="?rota=pets">Pets</a>
        <a href="?rota=agendamentos">Agendamentos</a>
    </nav>
    <div class="user-menu">
        <span><?= h($_SESSION['usuario']['nome'] ?? '') ?></span>
        <form method="post" action="?rota=sair">
            <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token'] ?? '') ?>">
            <button class="button button-quiet" type="submit">Sair</button>
        </form>
    </div>
</header>
<?php endif; ?>
<main class="container<?= !empty($autenticacao) ? ' login-container' : '' ?>">
    <?php if ($mensagemFlash !== null): ?>
        <div class="alert alert-<?= h($mensagemFlash['tipo']) ?>" role="status"><?= h($mensagemFlash['texto']) ?></div>
    <?php endif; ?>
    <?= $conteudo ?>
</main>
<footer>VetCare · Gestão responsável de atendimentos veterinários</footer>
</body>
</html>
