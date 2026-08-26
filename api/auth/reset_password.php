<?php
require_once '../config/database.php';

$data = json_decode(file_get_contents('php://input'), true);
$token = $data['token'] ?? '';
$password = $data['password'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // simple info endpoint
    echo json_encode(['ok' => true, 'method' => 'GET']);
    exit;
}

if (empty($token) || empty($password)) {
    http_response_code(400);
    echo json_encode(['error' => 'Token e nova senha são obrigatórios']);
    exit;
}

$stmt = $pdo->prepare('SELECT user_id, expires FROM password_resets WHERE token = ?');
$stmt->execute([$token]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) { http_response_code(400); echo json_encode(['error'=>'Token inválido']); exit; }
if (new DateTime() > new DateTime($row['expires'])) { http_response_code(400); echo json_encode(['error'=>'Token expirado']); exit; }

$userId = $row['user_id'];
$hash = password_hash($password, PASSWORD_DEFAULT);
$upd = $pdo->prepare('UPDATE usuarios SET senha_hash = ? WHERE id = ?');
try {
    $upd->execute([$hash, $userId]);
    $del = $pdo->prepare('DELETE FROM password_resets WHERE token = ?');
    $del->execute([$token]);
    echo json_encode(['success' => true, 'message' => 'Senha atualizada com sucesso']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

?>
