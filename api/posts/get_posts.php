<?php
/**
 * Archivo: api/posts/get_posts.php
 * Obtiene lista de posts
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/constants.php';

$limit = $_GET['limit'] ?? 30;
$offset = $_GET['offset'] ?? 0;

try {
    $db = ConexionOmega::getInstance();
    $conn = $db->getConexion();

    $stmt = $conn->prepare("
        SELECT p.id, p.contenido, p.tipo, p.fecha_creacion,
               u.id as usuario_id, u.nombre, u.foto_perfil, u.marco_perfil
        FROM posts p
        JOIN usuarios u ON p.agente_id = u.id
        ORDER BY p.fecha_creacion DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();

    $posts = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'posts' => $posts,
        'count' => count($posts)
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener los posts']);
}
