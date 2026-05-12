<?php
/**
 * Archivo: api/agents/combine_agents.php
 * Combina múltiples agentes premium
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
$selectedAgents = $input['agents'] ?? [];

// Validar que se seleccionaron entre 1 y 3 agentes
if (count($selectedAgents) < 1 || count($selectedAgents) > 3) {
    http_response_code(400);
    echo json_encode(['error' => 'Debe seleccionar entre 1 y 3 agentes']);
    exit;
}

$validAgents = ['elite', 'creador', 'comercio', 'influencer', 'moderador'];

foreach ($selectedAgents as $agent) {
    if (!in_array($agent, $validAgents)) {
        http_response_code(400);
        echo json_encode(['error' => 'Agente no válido: ' . $agent]);
        exit;
    }
}

$usuario_id = $_SESSION['agente_id'];

try {
    $db = ConexionOmega::getInstance();
    $conn = $db->getConexion();

    $agentsJson = json_encode($selectedAgents);
    $stmt = $conn->prepare("UPDATE usuarios SET agentes_seleccionados = :agents WHERE id = :usuario_id");
    $stmt->execute([
        ':agents' => $agentsJson,
        ':usuario_id' => $usuario_id
    ]);

    echo json_encode([
        'success' => true,
        'selected_agents' => $selectedAgents,
        'count' => count($selectedAgents)
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al guardar los agentes']);
}
