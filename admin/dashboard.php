<?php
require_once __DIR__ . '/auth.php';
require_admin();
$pdo = getPDO();
if (isset($_GET['logout'])) {
    logout_user();
    header('Location: login.php');
    exit;
}
$totalUsers = (int)$pdo->query('SELECT COUNT(*) FROM usuarios')->fetchColumn();
$totalProjects = (int)$pdo->query('SELECT COUNT(*) FROM projetos')->fetchColumn();
$pendingProjects = (int)$pdo->query("SELECT COUNT(*) FROM projetos WHERE status = 'pendente'")->fetchColumn();
?><!doctype html>
<html lang="pt-BR">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Painel Admin — Feira</title><link rel="stylesheet" href="styles.css"></head>
<body>
<header class="admin-header"><h1>Painel do Administrador</h1><div class="actions"><a href="create_admin_cli.php" target="_blank">Criar admin (CLI)</a><a href="?logout=1">Sair</a></div></header>
<main class="admin-grid">
<section class="card"><h2>Visão geral</h2><ul><li>Total de usuários: <strong><?=$totalUsers?></strong></li><li>Total de projetos: <strong><?=$totalProjects?></strong></li><li>Projetos pendentes: <strong><?=$pendingProjects?></strong></li></ul></section>
<section class="card"><h2>Gerenciar projetos</h2><p>A edição é feita diretamente na tela de cada projeto, pelo botão <strong>Editar projeto</strong>.</p><p><a href="../index.html#/projetos">Abrir catálogo de projetos</a></p></section>
<section class="card"><h2>Gerenciar usuários</h2><p><a href="#" onclick="alert('Implementar lista/edição de usuários aqui');return false">Listar e editar usuários</a></p></section>
<section class="card"><h2>Aprovações</h2><p><a href="#" onclick="alert('Implementar fluxo de aprovação de projetos');return false">Aprovar/Reprovar projetos</a></p></section>
<section class="card"><h2>Logs</h2><p><a href="#" onclick="alert('Implementar exibição de logs');return false">Ver registros de ações</a></p></section>
</main>
<footer class="admin-footer">Painel administrativo — ações sensíveis requerem precaução.</footer>
</body></html>
