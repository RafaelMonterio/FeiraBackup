<?php
require_once '../config/database.php';

$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');

if (empty($email)) {
    http_response_code(400);
    echo json_encode(['error' => 'E-mail é obrigatório']);
    exit;
}

$stmt = $pdo->prepare("SELECT id, nome FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    http_response_code(404);
    echo json_encode(['error' => 'E-mail não encontrado']);
    exit;
}

// ensure password_resets table exists
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS password_resets (
        token VARCHAR(64) PRIMARY KEY,
        user_id VARCHAR(50) NOT NULL,
        expires DATETIME NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
} catch (PDOException $e) {
    // ignore - table might already exist
}

// generate token and store
$token = bin2hex(random_bytes(16));
$expires = (new DateTime('+1 hour'))->format('Y-m-d H:i:s');
$ins = $pdo->prepare('INSERT INTO password_resets (token, user_id, expires) VALUES (:t, :u, :e)');
$ins->execute([':t' => $token, ':u' => $user['id'], ':e' => $expires]);

// build reset link (returned for development/testing and displayed in popup)
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$base = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$link = "http://$host$base/reset_password.php?token=$token";

// try to send email (best-effort)
$subject = 'Recuperação de senha - Feira Tech';
$message = "Olá {$user['nome']},\n\nRecebemos uma solicitação para redefinir sua senha. Abra este link para definir uma nova senha:\n\n$link\n\nSe você não solicitou, ignore esta mensagem.\n";
$headers = 'From: no-reply@' . ($host) . "\r\n";
$sent = false;
if (function_exists('mail')) {
    $sent = mail($email, $subject, $message, $headers);
}

// Return link in response so front-end can show popup while in production/testing
echo json_encode(['success' => true, 'sent' => $sent, 'resetLink' => $link]);
?>