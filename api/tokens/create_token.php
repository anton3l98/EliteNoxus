<?php
/**
 * Archivo: api/tokens/create_token.php
 * Crea un token premium para el usuario
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/constants.php';

session_start();

// Verificar que el usuario está autenticado
if (!isset($_SESSION['agente_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

$usuario_id = $_SESSION['agente_id'];

// Generar token único
$token = bin2hex(random_bytes(32));
$fecha_expiracion = date('Y-m-d H:i:s', strtotime('+' . TOKEN_DURATION_HOURS . ' hours'));

try {
    $db = ConexionOmega::getInstance();
    $conn = $db->getConexion();

    // Guardar token en la base de datos
    $stmt = $conn->prepare("REPLACE INTO tokens_premium (usuario_id, token, fecha_expiracion, activo) VALUES (:usuario_id, :token, :expiracion, 1)");
    $stmt->execute([
        ':usuario_id' => $usuario_id,
        ':token' => $token,
        ':expiracion' => $fecha_expiracion
    ]);

    echo json_encode([
        'success' => true,
        'token' => $token,
        'expires_at' => $fecha_expiracion,
        'duration_hours' => TOKEN_DURATION_HOURS
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al crear el token']);
}
