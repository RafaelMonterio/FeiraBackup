<?php
// api/cronograma_update.php
require_once 'config/database.php';
$data = json_decode(file_get_contents('php://input'), true);
$id = trim($data['id'] ?? '');
if (!$id) { http_response_code(400); echo json_encode(['error'=>'ID é obrigatório']); exit; }
$fields = [];
$params = [];
foreach (['titulo','data','hora','local','status'] as $f) {
    if (isset($data[$f])) { $fields[] = "$f = :$f"; $params[":$f"] = $data[$f]; }
}
if (empty($fields)) { http_response_code(400); echo json_encode(['error'=>'Nenhum campo para atualizar']); exit; }
$params[':id'] = $id;
$sql = "UPDATE cronograma SET " . implode(', ', $fields) . " WHERE id = :id";
$stmt = $pdo->prepare($sql);
try { $stmt->execute($params); echo json_encode(['success'=>true]); }
catch (PDOException $e) { http_response_code(500); echo json_encode(['error'=>$e->getMessage()]); }
?>