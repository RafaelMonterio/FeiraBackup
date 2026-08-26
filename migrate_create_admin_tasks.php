<?php
// Executar este script uma vez para criar a tabela admin_tasks
require __DIR__ . '/api/config/database.php';
try {
    $sql = "CREATE TABLE IF NOT EXISTS admin_tasks (
        id VARCHAR(50) PRIMARY KEY,
        projeto_id VARCHAR(50) NULL,
        titulo VARCHAR(255) NOT NULL,
        descricao TEXT,
        criado_por VARCHAR(50) NULL,
        status ENUM('open','in_progress','resolved') DEFAULT 'open',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $pdo->exec($sql);
    echo "OK: tabela admin_tasks criada ou já existente.\n";
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage() . PHP_EOL;
    exit(1);
}
?>