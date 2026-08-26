<?php
require_once __DIR__ . '/auth.php';
require_admin();
$pdo = getPDO();

// Exemplos de dados administrativos (contagens)
$total_users = $pdo->query('SELECT COUNT(*) FROM usuarios')->fetchColumn();
$total_projects = $pdo->query('SELECT COUNT(*) FROM projetos')->fetchColumn();
$pending_projects = $pdo->query("SELECT COUNT(*) FROM projetos WHERE status = 'pendente'")->fetchColumn();

?><!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Painel Admin — Feira</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<header class="admin-header">
    <h1>Painel do Administrador</h1>
    <div class="actions">
        <a href="create_admin_cli.php" target="_blank">Criar admin (CLI)</a>
        <a href="?logout=1">Sair</a>
    </div>
</header>
<main class="admin-grid">
    <section class="card">
        <h2>Visão geral</h2>
        <ul>
            <li>Total de usuários: <strong><?=htmlspecialchars($total_users)?></strong></li>
            <li>Total de projetos: <strong><?=htmlspecialchars($total_projects)?></strong></li>
            <li>Projetos pendentes: <strong><?=htmlspecialchars($pending_projects)?></strong></li>
        </ul>
    </section>

    <section class="card">
        <h2>Gerenciar usuários</h2>
        <p><a href="#" onclick="alert('Implementar lista/edição de usuários aqui')">Listar e editar usuários</a></p>
    </section>

    <section class="card">
        <h2>Aprovações</h2>
        <p><a href="#" onclick="alert('Implementar fluxo de aprovação de projetos')">Aprovar/Reprovar projetos</a></p>
    </section>

    <section class="card">
        <h2>Logs</h2>
        <p><a href="#" onclick="alert('Implementar exibição de logs')">Ver registros de ações</a></p>
    </section>
</main>

<footer class="admin-footer">Painel administrativo — Ações sensíveis requerem precaução.</footer>

</body>
</html>

<?php
if (isset($_GET['logout'])) {
    logout_user();
    header('Location: login.php');
    exit;
}
?>