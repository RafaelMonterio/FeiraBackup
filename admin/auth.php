<?php
// auth.php — funções comuns de autenticação e CSRF
session_start();

$config = require __DIR__ . '/config.php';

function getPDO(): PDO {
    static $pdo = null;
    global $config;
    if ($pdo === null) {
        $pdo = new PDO($config['dsn'], $config['db_user'], $config['db_pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}

function require_admin(): void {
    if (empty($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
    // Conferir role
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT role FROM usuarios WHERE id = :id');
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $row = $stmt->fetch();
    if (!$row || $row['role'] !== 'admin') {
        http_response_code(403);
        echo 'Acesso negado — é necessário ser administrador.';
        exit;
    }
}

function login_user(string $email, string $password): bool {
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT id, senha_hash, role FROM usuarios WHERE email = :email LIMIT 1');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();
    if (!$user) return false;
    if (!password_verify($password, $user['senha_hash'])) return false;
    // Regenerar id da sessão para evitar fixation
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_role'] = $user['role'];
    return true;
}

function logout_user(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'], $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}

// CSRF helpers
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_check(string $token): bool {
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}
