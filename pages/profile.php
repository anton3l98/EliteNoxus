<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';

if (!isset($_SESSION['agente_id'])) {
    header('Location: login.php');
    exit;
}

$miId = $_SESSION['agente_id'];

try {
    $db = ConexionOmega::getInstance();
    $conn = $db->getConexion();

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = :id");
    $stmt->execute([':id' => $miId]);
    $perfil = $stmt->fetch();

    $stats = [
        'posts' => $conn->query("SELECT COUNT(*) FROM posts WHERE agente_id = $miId")->fetchColumn(),
        'seguidores' => $conn->query("SELECT COUNT(*) FROM seguidores WHERE seguido_id = $miId")->fetchColumn(),
        'siguiendo' => $conn->query("SELECT COUNT(*) FROM seguidores WHERE seguidor_id = $miId")->fetchColumn()
    ];

} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Noxus Elite</title>
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
                <a href="tokens.php" class="nav-item">💎 Tokens</a>
                <a href="agents.php" class="nav-item">🎭 Agentes</a>
                <a href="perfil.php" class="nav-item active">👤 Perfil</a>
                <a href="tienda.php" class="nav-item">🛒 Tienda</a>
                <a href="../logout.php" class="nav-item">🚪 Salir</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="profile-container">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <img src="<?php echo $perfil['foto_perfil'] ?: 'https://via.placeholder.com/150'; ?>" alt="Avatar">
                    </div>
                    <div class="profile-info">
                        <h1><?php echo htmlspecialchars($perfil['nombre']); ?></h1>
                        <p class="email"><?php echo htmlspecialchars($perfil['email']); ?></p>
                        <p class="rol"><?php echo htmlspecialchars($perfil['rol']); ?></p>
                    </div>
                </div>

                <div class="profile-stats">
                    <div class="stat-box">
                        <span class="stat-number"><?php echo $perfil['nivel']; ?></span>
                        <span class="stat-label">Nivel</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number"><?php echo $perfil['xp']; ?></span>
                        <span class="stat-label">XP</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number"><?php echo $perfil['coins']; ?></span>
                        <span class="stat-label">Coins</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number"><?php echo $stats['posts']; ?></span>
                        <span class="stat-label">Posts</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number"><?php echo $stats['seguidores']; ?></span>
                        <span class="stat-label">Seguidores</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number"><?php echo $stats['siguiendo']; ?></span>
                        <span class="stat-label">Siguiendo</span>
                    </div>
                </div>

                <?php if (!empty($perfil['insignias'])): ?>
                <div class="profile-badges">
                    <h3>Insignias</h3>
                    <div class="badges-list">
                        <?php
                        $insignias = json_decode($perfil['insignias'], true);
                        foreach ($insignias as $badge):
                        ?>
                        <span class="badge"><?php echo htmlspecialchars($badge); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="profile-actions">
                    <button class="btn btn-primary">✏️ Editar Perfil</button>
                    <button class="btn btn-secondary">⚙️ Configuración</button>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
