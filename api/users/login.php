<?php
/**
 * Archivo: api/users/login.php
 * Inicia sesión de usuario
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/constants.php';

session_start();

$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['error' => 'Email y contraseña son requeridos']);
    exit;
}

try {
    $db = ConexionOmega::getInstance();
    $conn = $db->getConexion();

    // Obtener usuario
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        http_response_code(401);
        echo json_encode(['error' => 'Credenciales inválidas']);
        exit;
    }

    // Verificar contraseña
    if (!password_verify($password, $usuario['password'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Credenciales inválidas']);
        exit;
    }

    // Crear sesión
    $_SESSION['agente_id'] = $usuario['id'];
    $_SESSION['agente_nombre'] = $usuario['nombre'];
    $_SESSION['agente_rol'] = $usuario['rol'];
    $_SESSION['agente_email'] = $usuario['email'];

    // Regenerar ID de sesión por seguridad
    session_regenerate_id(true);

    echo json_encode([
        'success' => true,
        'user' => [
            'id' => $usuario['id'],
            'nombre' => $usuario['nombre'],
            'email' => $usuario['email'],
            'rol' => $usuario['rol']
        ],
        'message' => 'Login exitoso'
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al iniciar sesión']);
}
