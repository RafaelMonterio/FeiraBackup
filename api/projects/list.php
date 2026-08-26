<?php
// api/projects/list.php
require_once '../config/database.php';

$curso = $_GET['course'] ?? 'all';
$status = $_GET['status'] ?? 'all';
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'votes';
$criado_por = $_GET['criado_por'] ?? '';

$sql = "SELECT p.*, t.nome as professor_nome
    FROM projetos p
    LEFT JOIN professores t ON p.professor_id = t.id
    WHERE 1=1";
$params = [];

if ($curso !== 'all' && $curso !== '') {
    $sql .= " AND p.curso = ?";
    $params[] = $curso;
}
if ($status !== 'all' && $status !== '') {
    $sql .= " AND p.status = ?";
    $params[] = $status;
}
if (!empty($criado_por)) {
    $sql .= " AND p.criado_por = ?";
    $params[] = $criado_por;
}
if (!empty($search)) {
    $sql .= " AND (p.nome LIKE ? OR p.resumo LIKE ? OR p.descricao LIKE ?)";
    $like = "%$search%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

switch ($sort) {
    case 'votes':
        $sql .= " ORDER BY p.votos DESC";
        break;
    case 'recent':
        $sql .= " ORDER BY p.created_at DESC";
        break;
    case 'az':
        $sql .= " ORDER BY p.nome ASC";
        break;
    default:
        $sql .= " ORDER BY p.votos DESC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Nunca expor o hash da senha de acesso. O documento anexado (PDF/DOC) só
// é necessário na página de detalhes do projeto, não na listagem — remover
// aqui evita carregar arquivos grandes à toa em toda consulta ao catálogo.
foreach ($projects as &$p) {
    unset($p['senha_acesso']);
    unset($p['documento']);
}

echo json_encode($projects);
