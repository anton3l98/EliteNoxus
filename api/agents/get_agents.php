<?php
/**
 * Archivo: api/agents/get_agents.php
 * Obtiene lista de agentes premium disponibles
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../../config/constants.php';

// Lista de agentes premium disponibles
$agents = [
    [
        'id' => 'elite',
        'name' => 'Agente Elite',
        'description' => 'Acceso completo al sistema',
        'icon' => '👑',
        'features' => [
            'Acceso completo a todas las funciones',
            'Soporte prioritario',
            '_badges_ ilimitados',
            'Visibilidad premium'
        ]
    ],
    [
        'id' => 'creador',
        'name' => 'Agente Creador',
        'description' => 'Herramientas avanzadas de contenido',
        'icon' => '✏️',
        'features' => [
            'Crear contenido ilimitado',
            'Herramientas de edición avanzadas',
            'Estadísticas detalladas',
            'Monetización de contenido'
        ]
    ],
    [
        'id' => 'comercio',
        'name' => 'Agente Comercial',
        'description' => 'Funciones de comercio electrónico',
        'icon' => '🛒',
        'features' => [
            'Tienda online integrada',
            'Gestión de productos',
            'Pasarela de pagos',
            'Sistema de inventario'
        ]
    ],
    [
        'id' => 'influencer',
        'name' => 'Agente Influencer',
        'description' => 'Herramientas para influencers',
        'icon' => '📱',
        'features' => [
            'Analíticas avanzadas',
            'Gestión de campañas',
            'Colaboraciones',
            'Gestión de sponsors'
        ]
    ],
    [
        'id' => 'moderador',
        'name' => 'Agente Moderador',
        'description' => 'Control y moderación de comunidades',
        'icon' => '🛡️',
        'features' => [
            'Moderación de contenido',
            'Gestión de usuarios',
            'Herramientas de seguridad',
            'Reportes avanzados'
        ]
    ]
];

echo json_encode([
    'success' => true,
    'agents' => $agents
]);
