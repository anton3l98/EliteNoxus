<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';

if (!isset($_SESSION['agente_id'])) {
    header('Location: login.php');
    exit;
}

$miId = $_SESSION['agente_id'];
$miNombre = $_SESSION['agente_nombre'];

try {
    $db = ConexionOmega::getInstance();
    $conn = $db->getConexion();

    // Obtener información del usuario
    $stmtAgente = $conn->prepare("SELECT * FROM usuarios WHERE id = :id");
    $stmtAgente->execute([':id' => $miId]);
    $agente = $stmtAgente->fetch();

    // Obtener posts recientes
    $stmtFeed = $conn->query("
        SELECT p.*, u.nombre, u.foto_perfil, u.marco_perfil
        FROM posts p
        JOIN usuarios u ON p.agente_id = u.id
        ORDER BY p.fecha_creacion DESC LIMIT 30
    ");
    $feedItems = $stmtFeed->fetchAll();

    // Obtener stats
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
    <title>Dashboard - Noxus Elite</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>NOXUS <span class="accent">ELITE</span></h2>
            </div>
            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-item active">🏠 Inicio</a>
                <a href="tokens.php" class="nav-item">💎 Tokens</a>
                <a href="agents.php" class="nav-item">🎭 Agentes</a>
                <a href="perfil.php" class="nav-item">👤 Perfil</a>
                <a href="tienda.php" class="nav-item">🛒 Tienda</a>
                <a href="../logout.php" class="nav-item">🚪 Salir</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="feed-container">
                <div class="create-post-box">
                    <textarea id="postContent" placeholder="¿Qué estás pensando, <?php echo htmlspecialchars($miNombre); ?>?"></textarea>
                    <button id="postBtn" class="btn btn-primary">Publicar</button>
                </div>

                <div class="feed">
                    <?php foreach ($feedItems as $post): ?>
                    <div class="post-card">
                        <div class="post-header">
                            <img src="<?php echo $post['foto_perfil'] ?: 'https://via.placeholder.com/40'; ?>" class="post-avatar">
                            <div class="post-info">
                                <span class="post-author"><?php echo htmlspecialchars($post['nombre']); ?></span>
                                <span class="post-time"><?php echo date('d/m/Y H:i', strtotime($post['fecha_creacion'])); ?></span>
                            </div>
                        </div>
                        <div class="post-content">
                            <?php echo nl2br(htmlspecialchars($post['contenido'])); ?>
                        </div>
                        <div class="post-actions">
                            <button class="action-btn">❤️ <?php echo $post['likes_count']; ?></button>
                            <button class="action-btn">💬 <?php echo $post['comentarios_count']; ?></button>
                            <button class="action-btn">🔄 Compartir</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Stats Sidebar -->
            <aside class="stats-sidebar">
                <div class="stats-card">
                    <h3>Mis Stats</h3>
                    <div class="stat-item">
                        <span class="stat-label">Nivel</span>
                        <span class="stat-value"><?php echo $agente['nivel']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">XP</span>
                        <span class="stat-value"><?php echo $agente['xp']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Coins</span>
                        <span class="stat-value"><?php echo $agente['coins']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Posts</span>
                        <span class="stat-value"><?php echo $stats['posts']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Seguidores</span>
                        <span class="stat-value"><?php echo $stats['seguidores']; ?></span>
                    </div>
                </div>
            </aside>
        </main>
    </div>

    <script src="../assets/js/app.js"></script>
</body>
</html>
