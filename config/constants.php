<?php
/**
 * Archivo: config/constants.php
 * Constantes globales para EliteNoxus
 */

// Configuración del sitio
define('SITE_NAME', 'EliteNoxus');
define('SITE_TITLE', 'Noxus Elite - Red Social Premium');
define('SITE_URL', 'https://elit3signal.com');
define('SITE_EMAIL', 'support@elit3signal.com');

// Configuración de tokens premium
define('TOKEN_DURATION_HOURS', 12);
define('TOKEN_REFRESH_MINUTES', 30);

// Roles de usuario
define('ROLE_USER', 'usuario');
define('ROLE_CREATOR', 'creador');
define('ROLE_COMMERCE', 'comercio');
define('ROLE_INFLUENCER', 'influencer');
define('ROLE_MODERATOR', 'moderador');
define('ROLE_ADMIN', 'administrador');
define('ROLE_OWNER', 'owner');

// Niveles de usuario
define('LEVEL_MIN', 1);
define('LEVEL_MAX', 100);

// Configuración de XP
define('XP_PER_POST', 10);
define('XP_PER_COMMENT', 5);
define('XP_PER_LIKE', 2);
define('XP_PER_FOLLOW', 15);

// Configuración de seguridad
define('SESSION_LIFETIME', 86400); // 24 horas
define('MAX_LOGIN_ATTEMPTS', 5);
define('BLOCK_TIME_MINUTES', 15);
define('TOKEN_EXPIRY_HOURS', 1);

// Rutas del sistema
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('ASSETS_DIR', __DIR__ . '/../assets/');
define('LOG_DIR', __DIR__ . '/../logs/');

// Configuración de cookies
define('COOKIE_NAME', 'noxus_token');
define('COOKIE_LIFETIME', 86400); // 24 horas
define('COOKIE_PATH', '/');
define('COOKIE_SECURE', true);
define('COOKIE_HTTPONLY', true);
define('COOKIE_SAMESITE', 'Strict');

// Configuración de API
define('API_VERSION', '1.0.0');
define('API_RATE_LIMIT', 100);

// Configuración de cache
define('CACHE_ENABLED', true);
define('CACHE_LIFETIME', 300); // 5 minutos

// Configuración de correo
define('EMAIL_FROM', 'noreply@elit3signal.com');
define('EMAIL_FROM_NAME', 'EliteNoxus');
define('EMAIL_METHOD', 'smtp');

// Configuración de temas
define('DEFAULT_THEME', 'red');
define('AVAILABLE_THEMES', ['red', 'blue', 'green', 'purple', 'gold', 'dark']);

// Configuración de insignias
define('BADGE_LEVEL_10', 'Novato');
define('BADGE_LEVEL_25', 'Explorador');
define('BADGE_LEVEL_50', 'Veterano');
define('BADGE_LEVEL_75', 'Élite');
define('BADGE_LEVEL_100', 'Leyenda');

// Configuración de logros
define('ACHIEVEMENT_POSTS', 'Creador de Contenido');
define('ACHIEVEMENT_FOLLOWERS', 'Influencer');
define('ACHIEVEMENT_COMMENTS', 'Conversador');
define('ACHIEVEMENT_SHARES', 'Compartidor');

// Timezones
define('DEFAULT_TIMEZONE', 'America/Mexico_City');

// Estados del sistema
define('STATUS_ONLINE', 'SISTEMA ONLINE');
define('STATUS_OFFLINE', 'SISTEMA OFFLINE');
define('STATUS_MAINTENANCE', 'MANTENIMIENTO');
