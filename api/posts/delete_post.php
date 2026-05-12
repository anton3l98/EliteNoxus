<?php
/**
 * Archivo: api/posts/delete_post.php
 * Elimina un post
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

$postId = $_GET['id'] ?? 0;

if (empty($postId)) {
    http_response_code(400);
    echo json_encode(['error' => 'ID del post requerido']);
    exit;
}

$usuario_id = $_SESSION['agente_id'];
$rol = $_SESSION['agente_rol'] ?? 'usuario';

try {
    $db = ConexionOmega::getInstance();
    $conn = $db->getConexion();

    // Verificar que el usuario es el autor o es administrador
    $stmtCheck = $conn->prepare("SELECT agente_id FROM posts WHERE id = :post_id");
    $stmtCheck->execute([':post_id' => $postId]);
    $post = $stmtCheck->fetch();

    if (!$post) {
        http_response_code(404);
        echo json_encode(['error' => 'Post no encontrado']);
        exit;
    }

    if ($post['agente_id'] != $usuario_id && !in_array($rol, ['administrador', 'moderador', 'owner'])) {
        http_response_code(403);
        echo json_encode(['error' => 'No tienes permiso para eliminar este post']);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM posts WHERE id = :post_id");
    $stmt->execute([':post_id' => $postId]);

    echo json_encode([
        'success' => true,
        'message' => 'Post eliminado correctamente'
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al eliminar el post']);
}
