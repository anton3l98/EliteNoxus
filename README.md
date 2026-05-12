# EliteNoxus - Premium Social Red

<p align="center">
  <img src="https://am-a.akamaihd.net/image?f=https://universe-meeps.leagueoflegends.com/v1/assets/images/faction/noxus_crest.png" alt="Noxus Elite Logo" width="150">
</p>

<p align="center">
  <strong>EliteNoxus</strong> - Premium Social Red<br>
  Una red social moderna con sistema de tokens premium de 12 horas y agentes seleccionables.
</p>

---

## 🚀 Características principales

### Sistema de Tokens Premium
- ✅ Tokens premium con duración de 12 horas
- ✅ Acceso completo a funciones premium
- ✅ Renovación y gestión sencilla

### Sistema de Agentes
- ✅ Selección de 1-3 agentes premium libremente combinables
- ✅ 5 tipos de agentes únicos:
  - **Agente Elite** - Acceso completo al sistema
  - **Agente Creador** - Herramientas avanzadas de contenido
  - **Agente Comercial** - Funciones de comercio electrónico
  - **Agente Influencer** - Herramientas para influencers
  - **Agente Moderador** - Control y moderación de comunidades

### Red Social Completa
- ✅ Publicaciones (posts) con soporte multimedia
- ✅ Sistema de comentarios
- ✅ Sistema de seguidores
- ✅ Likes y compartidos
- ✅ Feed en tiempo real

### Funcionalidades Adicionales
- ✅ Panel administrativo integrado
- ✅ Diseño responsivo moderno
- ✅ Sistema de XP y niveles
- ✅ Tienda virtual
- ✅ Encuestas y misiones
- ✅ Insignias y recompensas

---

## 🛠️ Stack Técnico

| Tecnología | Descripción |
|------------|-------------|
| Backend | PHP 7.4+ |
| Base de Datos | MySQL 5.7+ / MariaDB |
| Frontend | HTML5, CSS3, JavaScript (puro) |
| Servidor | cPanel / HostGator |
| Dominio | elit3signal.com |
| DNS | Cloudflare |

---

## 📁 Estructura del Proyecto

```
EliteNoxus/
├── config/
│   ├── database.php          # Configuración de conexión MySQL
│   └── constants.php         # Constantes globales
├── api/
│   ├── tokens/
│   │   ├── create_token.php
│   │   ├── validate_token.php
│   │   └── get_token_info.php
│   ├── agents/
│   │   ├── get_agents.php
│   │   └── combine_agents.php
│   ├── posts/
│   │   ├── create_post.php
│   │   ├── get_posts.php
│   │   └── delete_post.php
│   └── users/
│       ├── register.php
│       ├── login.php
│       └── profile.php
├── pages/
│   ├── index.php             # Página principal
│   ├── dashboard.php         # Panel de usuario
│   ├── tokens.php            # Gestión de tokens
│   ├── agents.php            # Selección de agentes
│   ├── perfil.php           # Perfil del usuario
│   └── tienda.php           # Tienda virtual
├── assets/
│   ├── css/
│   │   ├── style.css         # Estilos globales
│   │   └── responsive.css    # Diseño responsive
│   └── js/
│       ├── app.js            # JavaScript principal
│       └── api.js            # Funciones API
├── sql/
│   └── schema.sql            # Script de base de datos
└── .htaccess                 # Configuración Apache
```

---

## 🔧 Instalación Rápida

### 1. Requisitos Previos
- PHP 7.4 o superior
- MySQL 5.7+ o MariaDB
- Servidor web (Apache/Nginx)
- Extensiones PHP requeridas:
  - pdo_mysql
  - json
  - session
  - openssl

### 2. Pasos de Instalación

```bash
# 1. Clonar el repositorio
git clone https://github.com/NOXUSELITE/EliteNoxus.git

# 2. Importar la base de datos
mysql -u usuario -p nombre_bd < sql/schema.sql

# 3. Configurar credenciales
# Editar config/database.php con tus credenciales

# 4. Configurar permisos (Linux)
chmod 755 -R EliteNoxus/
chmod 775 assets/uploads/
```

### 3. Configuración de Base de Datos

Editar `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'tu_base_de_datos');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');
```

### 4. Acceso

Visitar: `https://elit3signal.com`

---

## 📡 Documentación de la API

### Autenticación

#### Registrar Usuario
```http
POST /api/users/register.php
Content-Type: application/json

{
  "nombre": "Usuario",
  "email": "usuario@email.com",
  "password": "contraseña123"
}
```

#### Iniciar Sesión
```http
POST /api/users/login.php
Content-Type: application/json

{
  "email": "usuario@email.com",
  "password": "contraseña123"
}
```

### Posts

#### Crear Post
```http
POST /api/posts/create_post.php
Content-Type: application/json

{
  "contenido": "Mi primer post",
  "tipo": "texto"
}
```

#### Obtener Posts
```http
GET /api/posts/get_posts.php?limit=30&offset=0
```

### Tokens

#### Crear Token Premium
```http
POST /api/tokens/create_token.php
```

#### Validar Token
```http
GET /api/tokens/validate_token.php?token=TOKEN_AQUI
```

---

## 🤝 Soporte

Para soporte técnico contacta a: **support@elit3signal.com**

---

## 📄 Licencia

Copyright © 2026 NOXUS ELITE CORPORATION.
Todos los derechos reservados.

---

<p align="center">
  <sub>Construido con ❤️ por el Equipo Noxus Elite</sub>
</p>
