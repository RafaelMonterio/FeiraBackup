<?php
require_once '../config/database.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? '';
$usuario_id = $data['usuario_id'] ?? null;

if (empty($id)) {
    http_response_code(400);
    echo json_encode(['error' => 'ID do projeto é obrigatório']);
    exit;
}

// Se um usuario_id for enviado, só permite editar o próprio projeto
// (a área administrativa pode editar sem enviar usuario_id).
if ($usuario_id !== null) {
    $stmt = $pdo->prepare("SELECT criado_por FROM projetos WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        http_response_code(404);
        echo json_encode(['error' => 'Projeto não encontrado']);
        exit;
    }
    if ($row['criado_por'] !== $usuario_id) {
        http_response_code(403);
        echo json_encode(['error' => 'Você só pode editar projetos que você mesmo cadastrou']);
        exit;
    }
}

$fields = [];
$params = [];
// Campos permitidos para edição (frontend/administrador devem validar permissões)
$basicFields = ['nome','resumo','descricao','curso','turma','periodo','professor_id','github','site','imagem','capa','ods','links','documento','stand_id','criado_por','senha_acesso','status','votos','qr_link','qr_code'];
// Mesmo sendo o dono do projeto, um aluno NUNCA pode alterar estes campos
// diretamente por aqui — são de uso exclusivo da administração. Isso evita
// que alguém chame esta API manualmente (fora da UI) e mude nome, professor,
// dono, status de aprovação, votos ou a própria senha de acesso do projeto.
$adminOnlyFields = ['nome','professor_id','criado_por','status','votos','senha_acesso','stand_id'];
foreach ($basicFields as $field) {
    if ($usuario_id !== null && in_array($field, $adminOnlyFields, true)) {
        continue;
    }
    if (isset($data[$field])) {
        $fields[] = "$field = ?";
        $params[] = $data[$field];
    }
}
// Campos JSON
// "team"/"membros" (integrantes) também são de uso exclusivo da administração.
if ($usuario_id === null) {
    if (isset($data['team'])) {
        $fields[] = "equipe = ?";
        $params[] = json_encode($data['team']);
    }
    if (isset($data['membros'])) {
        // aceita array ou JSON string
        $m = $data['membros'];
        if (!is_string($m)) $m = json_encode($m);
        $fields[] = "membros = ?";
        $params[] = $m;
    }
}
if (isset($data['objetivos'])) {
    $obj = $data['objetivos'];
    $fields[] = "objetivos = ?";
    $params[] = is_string($obj) ? $obj : json_encode($obj);
}
if (isset($data['tecnologias'])) {
    $tech = $data['tecnologias'];
    $fields[] = "tecnologias = ?";
    $params[] = is_string($tech) ? $tech : json_encode($tech);
}
if (empty($fields)) {
    http_response_code(400);
    echo json_encode(['error' => 'Nenhum campo para atualizar']);
    exit;
}
$params[] = $id;
$sql = "UPDATE projetos SET " . implode(', ', $fields) . " WHERE id = ?";
$stmt = $pdo->prepare($sql);
if ($stmt->execute($params)) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao atualizar projeto']);
}
?>