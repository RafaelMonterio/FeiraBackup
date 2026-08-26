<?php
// api/admin_tasks_list.php
require_once 'config/database.php';
$params = $_GET;
$limit = intval($params['limit'] ?? 100);
$offset = intval($params['offset'] ?? 0);
$stmt = $pdo->prepare("SELECT at.*, u.email as criado_por_email FROM admin_tasks at LEFT JOIN usuarios u ON at.criado_por = u.id ORDER BY at.criado_em DESC LIMIT :lim OFFSET :off");
$stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
$stmt->bindValue(':off', $offset, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);
?>