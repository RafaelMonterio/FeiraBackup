<?php
// api/cronograma_create.php
require_once 'config/database.php';
$data = json_decode(file_get_contents('php://input'), true);
$title = trim($data['title'] ?? '');
$date = trim($data['date'] ?? '');
$time = trim($data['time'] ?? '');
$location = trim($data['location'] ?? '');
$status = $data['status'] ?? 'agendado';
if (!$title || !$date || !$time || !$location) {
    http_response_code(400);
    echo json_encode(['error' => 'Dados incompletos']);
    exit;
}
$id = 's' . bin2hex(random_bytes(6));
$stmt = $pdo->prepare("INSERT INTO cronograma (id, data, hora, titulo, local, status) VALUES (:id, :date, :time, :title, :location, :status)");
try {
    $stmt->execute([':id'=>$id,':date'=>$date,':time'=>$time,':title'=>$title,':location'=>$location,':status'=>$status]);
    echo json_encode(['success'=>true,'id'=>$id]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error'=>$e->getMessage()]);
}
?>