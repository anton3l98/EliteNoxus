<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';

if (!isset($_SESSION['agente_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tokens Premium - Noxus Elite</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>NOXUS <span class="accent">ELITE</span></h2>
            </div>
            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-item">🏠 Inicio</a>
                <a href="tokens.php" class="nav-item active">💎 Tokens</a>
                <a href="agents.php" class="nav-item">🎭 Agentes</a>
                <a href="perfil.php" class="nav-item">👤 Perfil</a>
                <a href="tienda.php" class="nav-item">🛒 Tienda</a>
                <a href="../logout.php" class="nav-item">🚪 Salir</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="tokens-container">
                <h1>💎 Tokens Premium</h1>
                <p class="subtitle">Gestiona tus tokens premium de <?php echo TOKEN_DURATION_HOURS; ?> horas de duración.</p>

                <div class="token-actions">
                    <button id="createTokenBtn" class="btn btn-primary">
                        🎫 Generar Nuevo Token
                    </button>
                </div>

                <div id="tokenResult" class="token-result" style="display: none;">
                    <h3>Tu Token Premium</h3>
                    <div class="token-display">
                        <code id="tokenCode"></code>
                    </div>
                    <p>Expira: <span id="tokenExpires"></span></p>
                    <button id="copyTokenBtn" class="btn btn-secondary">📋 Copiar Token</button>
                </div>

                <div class="token-info">
                    <h3>¿Qué es un Token Premium?</h3>
                    <p>Los tokens premium te dan acceso completo a todas las funciones exclusivas de EliteNoxus durante <?php echo TOKEN_DURATION_HOURS; ?> horas.</p>
                    <ul>
                        <li>✨ Acceso a agentes premium seleccionables</li>
                        <li>🎁 Recompensas exclusivas</li>
                        <li>📈 Estadísticas avanzadas</li>
                        <li>🔓 Contenido desbloqueado</li>
                    </ul>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/app.js"></script>
    <script>
        document.getElementById('createTokenBtn').addEventListener('click', async function() {
            try {
                const response = await fetch('../api/tokens/create_token.php', {
                    method: 'POST',
                    credentials: 'include'
                });
                const data = await response.json();

                if (data.success) {
                    document.getElementById('tokenCode').textContent = data.token;
                    document.getElementById('tokenExpires').textContent = data.expires_at;
                    document.getElementById('tokenResult').style.display = 'block';
                } else {
                    alert(data.error || 'Error al crear token');
                }
            } catch (error) {
                alert('Error de conexión');
            }
        });

        document.getElementById('copyTokenBtn').addEventListener('click', function() {
            const token = document.getElementById('tokenCode').textContent;
            navigator.clipboard.writeText(token).then(() => {
                alert('Token copiado al portapapeles');
            });
        });
    </script>
</body>
</html>
