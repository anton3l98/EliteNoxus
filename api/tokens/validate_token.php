<?php
/**
 * Archivo: api/tokens/validate_token.php
 * Valida un token premium
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/constants.php';

$token = $_GET['token'] ?? '';

if (empty($token)) {
    http_response_code(400);
    echo json_encode(['error' => 'Token requerido']);
    exit;
}

try {
    $db = ConexionOmega::getInstance();
    $conn = $db->getConexion();

    $stmt = $conn->prepare("SELECT tp.*, u.nombre, u.email FROM tokens_premium tp JOIN usuarios u ON tp.usuario_id = u.id WHERE tp.token = :token AND tp.activo = 1");
    $stmt->execute([':token' => $token]);
    $tokenData = $stmt->fetch();

    if (!$tokenData) {
        http_response_code(404);
        echo json_encode(['valid' => false, 'error' => 'Token no encontrado']);
        exit;
    }

    $now = new DateTime();
    $expires = new DateTime($tokenData['fecha_expiracion']);

    if ($now > $expires) {
        http_response_code(410);
        echo json_encode(['valid' => false, 'error' => 'Token expirado']);
        exit;
    }

    echo json_encode([
        'valid' => true,
        'token' => $tokenData,
        'expires_at' => $tokenData['fecha_expiracion']
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al validar el token']);
}
