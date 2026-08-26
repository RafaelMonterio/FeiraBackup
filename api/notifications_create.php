<?php
// api/notifications_create.php
require_once 'config/database.php';
$data = json_decode(file_get_contents('php://input'), true);
$to_user = $data['to_user'] ?? null;
$message = trim($data['message'] ?? '');
$type = $data['type'] ?? 'info';
$read = 0;
if (!$to_user || !$message) { http_response_code(400); echo json_encode(['error'=>'Dados incompletos']); exit; }
$id = 'n'.bin2hex(random_bytes(6));
$stmt = $pdo->prepare("INSERT INTO notificacoes (id, usuario_id, mensagem, tipo, lida, criada_em) VALUES (:id, :uid, :msg, :type, :read, NOW())");
try { $stmt->execute([':id'=>$id,':uid'=>$to_user,':msg'=>$message,':type'=>$type,':read'=>$read]); echo json_encode(['success'=>true,'id'=>$id]); }
catch (PDOException $e) { http_response_code(500); echo json_encode(['error'=>$e->getMessage()]); }
?>