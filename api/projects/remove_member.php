<?php
// api/projects/remove_member.php
// Remove um membro (usuario_id) de um projeto. Pode ser chamado pelo criador do projeto, pelo próprio membro (para sair) ou por um admin.
require_once '../config/database.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = trim($data['id'] ?? '');
$member_id = trim($data['member_id'] ?? '');
$actor_id = trim($data['usuario_id'] ?? ''); // quem está solicitando a remoção

if (empty($id) || empty($member_id)) {
    http_response_code(400);
    echo json_encode(['error' => 'Dados incompletos']);
    exit;
}

// Buscar projeto
$stmt = $pdo->prepare("SELECT criado_por, membros FROM projetos WHERE id = ?");
$stmt->execute([$id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$project) {
    http_response_code(404);
    echo json_encode(['error' => 'Projeto não encontrado']);
    exit;
}
$membros = json_decode($project['membros'] ?? '[]', true);
if (!is_array($membros)) $membros = [];

// Se actor_id informado, verificar permissões: admin OR criador OR o próprio membro
if ($actor_id !== '') {
    $stmtU = $pdo->prepare("SELECT role FROM usuarios WHERE id = ? LIMIT 1");
    $stmtU->execute([$actor_id]);
    $actor = $stmtU->fetch(PDO::FETCH_ASSOC);
    $actorRole = $actor['role'] ?? null;
    if ($actorRole !== 'admin' && $actor_id !== $project['criado_por'] && $actor_id !== $member_id) {
        http_response_code(403);
        echo json_encode(['error' => 'Permissão negada']);
        exit;
    }
}

if (!in_array($member_id, $membros, true)) {
    // já não é membro
    echo json_encode(['success' => true, 'membros' => $membros, 'info' => 'Usuário não estava na lista de membros']);
    exit;
}

// remover
$membros = array_values(array_filter($membros, function($m) use ($member_id) { return $m !== $member_id; }));
$upd = $pdo->prepare("UPDATE projetos SET membros = ? WHERE id = ?");
$upd->execute([json_encode($membros), $id]);

echo json_encode(['success' => true, 'membros' => $membros]);

?>