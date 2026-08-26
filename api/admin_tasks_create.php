<?php
// api/admin_tasks_create.php
require_once 'config/database.php';
$data = json_decode(file_get_contents('php://input'), true);
$created_by = $data['created_by'] ?? null; // usuario_id do aluno
$project_id = $data['project_id'] ?? null;
$title = trim($data['title'] ?? '');
$description = trim($data['description'] ?? '');
if (!$created_by || !$title) { http_response_code(400); echo json_encode(['error'=>'Dados incompletos']); exit; }
$id = 't'.bin2hex(random_bytes(6));
$stmt = $pdo->prepare("INSERT INTO admin_tasks (id, project_id, titulo, descricao, criado_por, status, criado_em) VALUES (:id, :pid, :title, :desc, :creator, 'open', NOW())");
try {
    $stmt->execute([':id'=>$id,':pid'=>$project_id,':title'=>$title,':desc'=>$description,':creator'=>$created_by]);
    // criar notificacao para todos admins
    $admins = $pdo->query("SELECT id FROM usuarios WHERE role = 'admin'")->fetchAll(PDO::FETCH_COLUMN);
    $notifStmt = $pdo->prepare("INSERT INTO notificacoes (id, usuario_id, mensagem, tipo, lida, criada_em) VALUES (:nid, :uid, :msg, 'task', 0, NOW())");
    foreach ($admins as $aid) {
        $nid = 'n'.bin2hex(random_bytes(6));
        $msg = "Nova requisicao: $title (projeto: $project_id)";
        $notifStmt->execute([':nid'=>$nid,':uid'=>$aid,':msg'=>$msg]);
    }
    echo json_encode(['success'=>true,'id'=>$id]);
} catch (PDOException $e) { http_response_code(500); echo json_encode(['error'=>$e->getMessage()]); }
?>