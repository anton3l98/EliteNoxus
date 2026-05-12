<?php
/**
 * Archivo: api/users/profile.php
 * Obtiene el perfil del usuario
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

$usuario_id = $_SESSION['agente_id'];

try {
    $db = ConexionOmega::getInstance();
    $conn = $db->getConexion();

    $stmt = $conn->prepare("
        SELECT id, nombre, email, rol, foto_perfil, marco_perfil,
               tema_color, nivel, xp, coins, insignias, verificado,
               agentes_seleccionados, fecha_registro
        FROM usuarios
        WHERE id = :usuario_id
    ");
    $stmt->execute([':usuario_id' => $usuario_id]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        http_response_code(404);
        echo json_encode(['error' => 'Usuario no encontrado']);
        exit;
    }

    echo json_encode([
        'success' => true,
        'profile' => $usuario
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener el perfil']);
}
