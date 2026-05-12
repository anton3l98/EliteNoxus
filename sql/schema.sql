-- EliteNoxus Database Schema
-- MySQL/MariaDB

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('usuario', 'creador', 'comercio', 'influencer', 'moderador', 'administrador', 'owner') DEFAULT 'usuario',
    foto_perfil VARCHAR(500) DEFAULT NULL,
    marco_perfil VARCHAR(50) DEFAULT 'default',
    tema_color VARCHAR(50) DEFAULT 'red',
    nivel INT DEFAULT 1,
    xp INT DEFAULT 0,
    coins INT DEFAULT 100,
    insignias TEXT DEFAULT NULL,
    verificado TINYINT(1) DEFAULT 0,
    token_verificacion VARCHAR(255) DEFAULT NULL,
    reset_token VARCHAR(255) DEFAULT NULL,
    reset_expires DATETIME DEFAULT NULL,
    google2fa_secret VARCHAR(50) DEFAULT NULL,
    fecha_revocacion_2fa DATETIME DEFAULT NULL,
    modo_sigilo TINYINT(1) DEFAULT 0,
    agentes_seleccionados TEXT DEFAULT NULL,
    agentes_activos TEXT DEFAULT NULL,
    token_premium VARCHAR(255) DEFAULT NULL,
    token_premium_expira DATETIME DEFAULT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultimo_login DATETIME DEFAULT NULL,
    INDEX idx_email (email),
    INDEX idx_rol (rol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de posts
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agente_id INT NOT NULL,
    contenido TEXT NOT NULL,
    tipo ENUM('texto', 'imagen', 'video', 'audio') DEFAULT 'texto',
    media_url VARCHAR(500) DEFAULT NULL,
    likes_count INT DEFAULT 0,
    comentarios_count INT DEFAULT 0,
    compartidos_count INT DEFAULT 0,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultima_edicion DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (agente_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_agente (agente_id),
    INDEX idx_fecha (fecha_creacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de likes
CREATE TABLE IF NOT EXISTS likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    agente_id INT NOT NULL,
    fecha_like DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (agente_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_like (post_id, agente_id),
    INDEX idx_post (post_id),
    INDEX idx_agente (agente_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de comentarios
CREATE TABLE IF NOT EXISTS comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    agente_id INT NOT NULL,
    contenido TEXT NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (agente_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_post (post_id),
    INDEX idx_agente (agente_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de seguidores
CREATE TABLE IF NOT EXISTS seguidores (
    id INT AUTO_INCREMENT PRIMARY KEY,
   seguidor_id INT NOT NULL,
    seguido_id INT NOT NULL,
    fecha_seguimiento DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seguidor_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (seguido_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_seguimiento (seguidor_id, seguido_id),
    INDEX idx_seguidor (seguidor_id),
    INDEX idx_seguido (seguido_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de tokens premium
CREATE TABLE IF NOT EXISTS tokens_premium (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    fecha_expiracion DATETIME NOT NULL,
    activo TINYINT(1) DEFAULT 1,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_usuario (usuario_id),
    INDEX idx_token (token),
    INDEX idx_expiracion (fecha_expiracion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de encuestas
CREATE TABLE IF NOT EXISTS encuestas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pregunta VARCHAR(500) NOT NULL,
    opcion_1 VARCHAR(255) NOT NULL,
    opcion_2 VARCHAR(255) NOT NULL,
    opcion_3 VARCHAR(255) DEFAULT NULL,
    opcion_4 VARCHAR(255) DEFAULT NULL,
    activa TINYINT(1) DEFAULT 1,
    autor_id INT NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (autor_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_activa (activa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de votos encuestas
CREATE TABLE IF NOT EXISTS votos_encuestas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    encuesta_id INT NOT NULL,
    agente_id INT NOT NULL,
    opcion_seleccionada INT NOT NULL,
    fecha_voto DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (encuesta_id) REFERENCES encuestas(id) ON DELETE CASCADE,
    FOREIGN KEY (agente_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_voto (encuesta_id, agente_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de misiones
CREATE TABLE IF NOT EXISTS misiones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    xp_recompensa INT DEFAULT 0,
    coins_recompensa INT DEFAULT 0,
    autor_id INT NOT NULL,
    completada INT DEFAULT 0,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (autor_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_completada (completada)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de noticias
CREATE TABLE IF NOT EXISTS noticias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agente_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    contenido TEXT NOT NULL,
    visible TINYINT(1) DEFAULT 1,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (agente_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_visible (visible)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de documentos
CREATE TABLE IF NOT EXISTS documentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agente_id INT NOT NULL,
    nombre_original VARCHAR(255) NOT NULL,
    ruta_archivo VARCHAR(500) NOT NULL,
    peso_bytes INT NOT NULL,
    tipo MIME VARCHAR(100) DEFAULT NULL,
    fecha_subida DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (agente_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_agente (agente_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de intentos login (seguridad)
CREATE TABLE IF NOT EXISTS intentos_login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(45) NOT NULL,
    intentos INT DEFAULT 1,
    ultimo_intento DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ip (ip)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de bitácora
CREATE TABLE IF NOT EXISTS bitacora (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agente_id INT DEFAULT NULL,
    accion VARCHAR(100) NOT NULL,
    detalle TEXT DEFAULT NULL,
    ip VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_agente (agente_id),
    INDEX idx_accion (accion),
    INDEX idx_fecha (fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de amigos/solicitudes
CREATE TABLE IF NOT EXISTS amigos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    solicitante_id INT NOT NULL,
    receptores_id INT NOT NULL,
    estado ENUM('pendiente', 'aceptado', 'rechazado') DEFAULT 'pendiente',
    fecha_solicitud DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_respuesta DATETIME DEFAULT NULL,
    FOREIGN KEY (solicitante_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (receptores_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de tienda/productos
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vendedor_id INT NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    precio INT NOT NULL,
    stock INT DEFAULT 0,
    imagen VARCHAR(500) DEFAULT NULL,
    categoria VARCHAR(100) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1,
    FOREIGN KEY (vendedor_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_vendedor (vendedor_id),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuario administrador por defecto (PROPIETARIO)
-- Email: magadanch98@gmail.com
-- Password: Smg2015.
-- NOTA: Esta cuenta se crea automáticamente en el sistema
