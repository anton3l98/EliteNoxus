<?php
/**
 * Archivo: api/tokens/get_token_info.php
 * Obtiene información del token premium del usuario
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

    $stmt = $conn->prepare("SELECT token, fecha_expiracion, activo FROM tokens_premium WHERE usuario_id = :usuario_id AND activo = 1 ORDER BY fecha_expiracion DESC LIMIT 1");
    $stmt->execute([':usuario_id' => $usuario_id]);
    $tokenData = $stmt->fetch();

    if (!$tokenData) {
        echo json_encode(['has_token' => false]);
        exit;
    }

    $now = new DateTime();
    $expires = new DateTime($tokenData['fecha_expiracion']);
    $isExpired = $now > $expires;

    echo json_encode([
        'has_token' => true,
        'token' => $tokenData['token'],
        'expires_at' => $tokenData['fecha_expiracion'],
        'is_expired' => $isExpired,
        'active' => $tokenData['activo']
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener información del token']);
}
