<?php
/**
 * Archivo: api/users/register.php
 * Registra un nuevo usuario
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/constants.php';

$input = json_decode(file_get_contents('php://input'), true);
$nombre = $input['nombre'] ?? '';
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';

// Validar campos requeridos
if (empty($nombre) || empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['error' => 'Todos los campos son requeridos']);
    exit;
}

// Validar email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Email inválido']);
    exit;
}

// Validar contraseña (mínimo 8 caracteres)
if (strlen($password) < 8) {
    http_response_code(400);
    echo json_encode(['error' => 'La contraseña debe tener al menos 8 caracteres']);
    exit;
}

try {
    $db = ConexionOmega::getInstance();
    $conn = $db->getConexion();

    // Verificar si el email ya existe
    $stmtCheck = $conn->prepare("SELECT id FROM usuarios WHERE email = :email");
    $stmtCheck->execute([':email' => $email]);

    if ($stmtCheck->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'El email ya está registrado']);
        exit;
    }

    // Hash de la contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $tokenVerificacion = bin2hex(random_bytes(32));

    // Insertar usuario
    $stmt = $conn->prepare("
        INSERT INTO usuarios (nombre, email, password, token_verificacion, verificado, tema_color, nivel, xp, coins)
        VALUES (:nombre, :email, :password, :token, 0, :tema, 1, 0, 100)
    ");
    $stmt->execute([
        ':nombre' => $nombre,
        ':email' => $email,
        ':password' => $passwordHash,
        ':token' => $tokenVerificacion,
        ':tema' => DEFAULT_THEME
    ]);

    $usuarioId = $conn->lastInsertId();

    echo json_encode([
        'success' => true,
        'user_id' => $usuarioId,
        'message' => 'Usuario registrado correctamente',
        'verification_token' => $tokenVerificacion
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al registrar el usuario']);
}
