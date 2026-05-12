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
    <title>Agentes Premium - Noxus Elite</title>
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
                <a href="agents.php" class="nav-item active">🎭 Agentes</a>
                <a href="perfil.php" class="nav-item">👤 Perfil</a>
                <a href="tienda.php" class="nav-item">🛒 Tienda</a>
                <a href="../logout.php" class="nav-item">🚪 Salir</a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="agents-container">
                <h1>🎭 Agentes Premium</h1>
                <p class="subtitle">Selecciona hasta 3 agentes para combinar sus poderes.</p>

                <div class="agents-select" id="agentsList">
                    <!-- Los agentes se cargan dinámicamente -->
                </div>

                <div class="selected-agents">
                    <h3>Agentes Seleccionados: <span id="selectedCount">0</span>/3</h3>
                    <div id="selectedList"></div>
                </div>

                <button id="combineBtn" class="btn btn-primary" disabled>
                    ✅ Combinar Agentes
                </button>
            </div>
        </main>
    </div>

    <script src="../assets/js/app.js"></script>
    <script>
        let selectedAgents = [];

        async function loadAgents() {
            try {
                const response = await fetch('../api/agents/get_agents.php');
                const data = await response.json();

                if (data.success) {
                    const container = document.getElementById('agentsList');
                    container.innerHTML = data.agents.map(agent => `
                        <div class="agent-card" data-id="${agent.id}">
                            <div class="agent-icon">${agent.icon}</div>
                            <div class="agent-info">
                                <h3>${agent.name}</h3>
                                <p>${agent.description}</p>
                                <ul>
                                    ${agent.features.map(f => `<li>${f}</li>`).join('')}
                                </ul>
                            </div>
                            <button class="select-agent-btn" data-id="${agent.id}">Seleccionar</button>
                        </div>
                    `).join('');

                    // Agregar event listeners
                    document.querySelectorAll('.select-agent-btn').forEach(btn => {
                        btn.addEventListener('click', toggleAgent);
                    });
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        function toggleAgent(e) {
            const agentId = e.target.dataset.id;
            const index = selectedAgents.indexOf(agentId);

            if (index > -1) {
                selectedAgents.splice(index, 1);
                e.target.textContent = 'Seleccionar';
                e.target.classList.remove('selected');
            } else if (selectedAgents.length < 3) {
                selectedAgents.push(agentId);
                e.target.textContent = 'Deseleccionar';
                e.target.classList.add('selected');
            } else {
                alert('Máximo 3 agentes permitidos');
            }

            updateSelectedDisplay();
        }

        function updateSelectedDisplay() {
            document.getElementById('selectedCount').textContent = selectedAgents.length;
            document.getElementById('combineBtn').disabled = selectedAgents.length === 0;
        }

        document.getElementById('combineBtn').addEventListener('click', async function() {
            try {
                const response = await fetch('../api/agents/combine_agents.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({agents: selectedAgents}),
                    credentials: 'include'
                });
                const data = await response.json();

                if (data.success) {
                    alert('Agentes combinados correctamente');
                } else {
                    alert(data.error || 'Error al combinar agentes');
                }
            } catch (error) {
                alert('Error de conexión');
            }
        });

        loadAgents();
    </script>
</body>
</html>
