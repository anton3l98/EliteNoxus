<?php
/**
 * Archivo: api/posts/create_post.php
 * Crea un nuevo post/publicación
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/constants.php';

session_start();

if (!isset($_SESSION['agente_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$contenido = $input['contenido'] ?? '';
$tipo = $input['tipo'] ?? 'texto';

if (empty($contenido)) {
    http_response_code(400);
    echo json_encode(['error' => 'El contenido no puede estar vacío']);
    exit;
}

$usuario_id = $_SESSION['agente_id'];

try {
    $db = ConexionOmega::getInstance();
    $conn = $db->getConexion();

    $stmt = $conn->prepare("INSERT INTO posts (agente_id, contenido, tipo) VALUES (:usuario_id, :contenido, :tipo)");
    $stmt->execute([
        ':usuario_id' => $usuario_id,
        ':contenido' => $contenido,
        ':tipo' => $tipo
    ]);

    $postId = $conn->lastInsertId();

    // Otorgar XP por crear post
    $stmtXp = $conn->prepare("UPDATE usuarios SET xp = xp + :xp WHERE id = :usuario_id");
    $stmtXp->execute([':xp' => XP_PER_POST, ':usuario_id' => $usuario_id]);

    echo json_encode([
        'success' => true,
        'post_id' => $postId,
        'xp_earned' => XP_PER_POST
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al crear el post']);
}
