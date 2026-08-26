<?php
// api/projects/find.php
// Localiza um projeto pela chave (o id do projeto) + senha de acesso
// geradas no cadastro. Usado no botão "Encontrar meu projeto".
require_once '../config/database.php';

$data = json_decode(file_get_contents('php://input'), true);
$chave = trim($data['chave'] ?? '');
$nome = trim($data['nome'] ?? '');

if (empty($chave) || empty($nome)) {
    http_response_code(400);
    echo json_encode(['error' => 'Informe a chave e o nome do projeto']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM projetos WHERE id = ?");
$stmt->execute([$chave]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$project) {
    http_response_code(404);
    echo json_encode(['error' => 'Chave não encontrada']);
    exit;
}
// Confirma o nome do projeto para evitar entrada indevida apenas com a chave.
if (mb_strtolower(trim((string)$project['nome'] ?? '')) !== mb_strtolower($nome)) {
    http_response_code(401);
    echo json_encode(['error' => 'Nome do projeto não corresponde à chave informada']);
    exit;
}

// Nunca expor o hash da senha nem o documento (arquivo grande) por aqui.
unset($project['senha_acesso']);
unset($project['documento']);

echo json_encode(['success' => true, 'project' => $project]);
