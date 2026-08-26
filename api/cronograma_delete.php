<?php
// api/cronograma_delete.php
require_once 'config/database.php';
$data = json_decode(file_get_contents('php://input'), true);
$id = trim($data['id'] ?? '');
if (!$id) { http_response_code(400); echo json_encode(['error'=>'ID é obrigatório']); exit; }
$stmt = $pdo->prepare("DELETE FROM cronograma WHERE id = ?");
try { $stmt->execute([$id]); echo json_encode(['success'=>true]); }
catch (PDOException $e) { http_response_code(500); echo json_encode(['error'=>$e->getMessage()]); }
?>