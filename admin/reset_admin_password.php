<?php
// Reset admin password to a known value. Use carefully.
require_once __DIR__ . '/../api/config/database.php';

$new = $_POST['password'] ?? $_GET['password'] ?? 'FeiraTech01*';

// only allow from CLI or localhost
if (php_sapi_name() !== 'cli') {
    $host = $_SERVER['REMOTE_ADDR'] ?? '';
    if ($host !== '127.0.0.1' && $host !== '::1' && ($host !== ($_SERVER['SERVER_ADDR'] ?? ''))) {
        http_response_code(403);
        echo "Forbidden\n";
        exit;
    }
}

$hash = password_hash($new, PASSWORD_DEFAULT);
try {
    $stmt = $pdo->prepare("UPDATE usuarios SET senha_hash = ? WHERE role = 'admin'");
    $stmt->execute([$hash]);
    echo "OK: admin password reset to provided value.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

?>
