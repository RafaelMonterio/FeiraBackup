<?php
// api/cronograma.php - listar cronograma
require_once 'config/database.php';
$rows = $pdo->query("SELECT * FROM cronograma ORDER BY data, hora")->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows);
?>