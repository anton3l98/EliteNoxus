<?php
require_once __DIR__ . '/../config/database.php';

$estadoSistema = "ERROR DE SISTEMA";
$claseEstado = "offline";

try {
    $db = ConexionOmega::getInstance();
    $conexion = $db->getConexion();

    if ($conexion) {
        $estadoSistema = "SISTEMA ONLINE";
        $claseEstado = "online";
    }
} catch (Exception $e) {
    $estadoSistema = "SISTEMA OFFLINE";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noxus Elite - Acceso Seguro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="bg-effects">
        <div class="bg-gradient"></div>
        <div class="bg-grid"></div>
    </div>

    <div class="container">
        <div class="panel">
            <div class="logo-container">
                <img src="https://am-a.akamaihd.net/image?f=https://universe-meeps.leagueoflegends.com/v1/assets/images/faction/noxus_crest.png" alt="Noxus Elite" class="logo-img">
            </div>

            <h1 class="title">NOXUS <span class="title-accent">ELITE</span></h1>
            <p class="subtitle">Fuerza y Honor</p>

            <div class="status <?php echo $claseEstado; ?>">
                <span class="status-dot"></span>
                <?php echo $estadoSistema; ?>
            </div>

            <div class="action-buttons">
                <a href="login.php" class="btn btn-primary">
                    <span class="btn-icon">→</span>
                    Conectarse
                </a>
                <a href="register.php" class="btn btn-secondary">
                    <span class="btn-icon">+</span>
                    Unirse a la Elite
                </a>
            </div>

            <div class="footer" style="margin-top: 25px; color: #7a8aad;">
                © 2026 NOXUS ELITE CORPORATION.<br>
                Portal premium de acceso seguro.
            </div>
        </div>
    </div>
</body>
</html>
