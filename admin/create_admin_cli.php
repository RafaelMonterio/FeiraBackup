<?php
// Script CLI para criar um admin de forma segura. Executar via CLI: php create_admin_cli.php
// IMPORTANTE: após criar o admin inicial, mover/deletar este arquivo ou restringir acesso.

if (php_sapi_name() !== 'cli') {
    echo "Acesso inválido. Execute este script apenas via linha de comando.\n";
    exit(1);
}

$config = require __DIR__ . '/config.php';
$pdo = new PDO($config['dsn'], $config['db_user'], $config['db_pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

function prompt($msg) {
    echo $msg . ': ';
    return trim(fgets(STDIN));
}

echo "Criador de conta de administrador (CLI)\n";
$email = prompt('Email do administrador (ex: admin@exemplo.com)');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Email inválido. Abortando.\n";
    exit(1);
}

// Ler senha duas vezes (não ecoa no Windows por default; para segurança, recomenda criar senha forte antes)
echo "Digite a senha (visível ao digitar no Windows).\n";
$password = prompt('Senha');
$confirm = prompt('Confirmar senha');
if ($password === '' || $password !== $confirm) {
    echo "Senhas não conferem ou vazias. Abortando.\n";
    exit(1);
}

// Gerar id simples
$id = 'u' . bin2hex(random_bytes(6));
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare('INSERT INTO usuarios (id, nome, email, senha_hash, role, created_at) VALUES (:id, :nome, :email, :senha_hash, :role, NOW())');
$stmt->execute([
    ':id' => $id,
    ':nome' => 'Administrador',
    ':email' => $email,
    ':senha_hash' => $hash,
    ':role' => 'admin'
]);

echo "Conta de administrador criada com sucesso (id={$id}). Delete ou mova este script após o uso.\n";
