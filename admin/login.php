<?php
require_once __DIR__ . '/auth.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    $token = $_POST['csrf_token'] ?? '';

    if (!$email) $errors[] = 'Email inválido.';
    if (!$password) $errors[] = 'Senha é obrigatória.';
    if (!csrf_check($token)) $errors[] = 'Token CSRF inválido.';

    if (empty($errors)) {
        if (login_user($email, $password)) {
            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = 'Email ou senha incorretos.';
        }
    }
}

?><!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login Administrativo — Feira</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="admin-card">
    <h1>Área Administrativa</h1>
    <?php if ($errors): ?>
        <div class="errors">
            <?php foreach ($errors as $e): ?>
                <div class="err"><?=htmlspecialchars($e)?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="post" action="">
        <label>Email<br><input type="email" name="email" required></label>
        <label>Senha<br><div style="display:flex;gap:8px;align-items:center;"><input id="admin-pass" type="password" name="password" required style="flex:1;"><button type="button" onclick="(function(){var p=document.getElementById('admin-pass');p.type = p.type === 'password' ? 'text' : 'password';})();">Mostrar</button></div></label>
        <input type="hidden" name="csrf_token" value="<?=htmlspecialchars(csrf_token())?>">
        <button type="submit">Entrar</button>
    </form>
    <p class="note">Somente administradores autorizados.</p>
</div>
</body>
</html>