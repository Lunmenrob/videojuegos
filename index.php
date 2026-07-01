<?php
require_once 'auth.php'; // Incluye el archivo de autenticación
$loginSuccess = false; // Variable para indicar si el login fue exitoso
if (!empty($_SESSION['login_success'])) { // Si el login fue exitoso
    $loginSuccess = true; // Establece la variable a true
    unset($_SESSION['login_success']); // Elimina la variable de sesión
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Videojuegos PS4/PS5</title>
    <!-- Enlace a la hoja de estilos principal -->
    <link rel="stylesheet" href="estilos/css/index.css">
    <!-- Enlace a Font Awesome para iconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Incluye el header -->
    <?php include 'estilos/header.php'; ?>
    <div class="page-content">

    <!-- Muestra banner de éxito si el login fue exitoso -->
    <?php if ($loginSuccess): ?>
    <div class="login-success-banner">
        <i class="fas fa-check-circle"></i>
        <span>Login correcto. Bienvenido, administrador.</span>
    </div>
    <?php endif; ?>

    <main class="container">
        <!-- Sección de controles (búsqueda y filtros) -->
        <section class="controls">
            <!-- Caja de búsqueda -->
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Buscar videojuegos...">
                <button id="searchBtn"><i class="fas fa-search"></i></button>
            </div>
            
            <!-- Dropdowns de filtro -->
            <div class="filter-dropdowns">
                <!-- Filtro de plataforma -->
                <div class="dropdown-group">
                    <label for="platformFilter">Plataforma:</label>
                    <select id="platformFilter">
                        <option value="todos">Todos</option>
                        <option value="ps4">PS4</option>
                        <option value="ps5">PS5</option>
                    </select>
                </div>
                
                <!-- Filtro de ordenamiento -->
                <div class="dropdown-group">
                    <label for="sortFilter">Ordenar por:</label>
                    <select id="sortFilter">
                        <option value="nombre" selected>Nombre A-Z</option>
                        <option value="completados">Completados</option>
                        <option value="incompletos">Incompletos</option>
                    </select>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="action-buttons">
                <a href="agregar_juego.php" class="add-btn"><i class="fas fa-plus"></i></a>
            </div>
                                </section>

        <!-- Contenedor de juegos (se carga dinámicamente) -->
        <section id="gamesContainer" class="games-list">
            <!-- Los videojuegos se cargarán aquí dinámicamente -->
        </section>

        <!-- Sección de detalles del juego (oculta por defecto) -->
        <section id="gameDetails" class="game-details hidden">
            <div class="details-header">
                <button id="backBtn" class="btn-back"><i class="fas fa-arrow-left"></i> Volver</button>
                <h2 id="gameTitle"></h2>
            </div>
            
            <!-- Información del juego -->
            <div class="game-info">
                <!-- Estadísticas de trofeos -->
                <div class="game-stats">
                    <div class="stat-card">
                        <i class="fas fa-trophy bronze"></i>
                        <span id="bronzeCount">0</span>
                        <p>Bronce</p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-trophy silver"></i>
                        <span id="silverCount">0</span>
                        <p>Plata</p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-trophy gold"></i>
                        <span id="goldCount">0</span>
                        <p>Oro</p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-crown platinum"></i>
                        <span id="platinumStatus">No</span>
                        <p>Platino</p>
                    </div>
                </div>
                
                <!-- Barra de progreso -->
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill"></div>
                    <span class="progress-text" id="progressText">0%</span>
                </div>
            </div>

            <!-- Sección de trofeos -->
            <div class="trophies-section">
                <h3>Trofeos</h3>
                <!-- Controles de trofeos -->
                <div class="trophies-controls">
                    <input type="text" id="trophySearch" placeholder="Buscar trofeos...">
                    <select id="trophyFilter">
                        <option value="todos">Todos</option>
                        <option value="conseguidos">Conseguidos</option>
                        <option value="no-conseguidos">No conseguidos</option>
                        <option value="bronce">Bronce</option>
                        <option value="plata">Plata</option>
                        <option value="oro">Oro</option>
                        <option value="platino">Platino</option>
                    </select>
                </div>
                <!-- Lista de trofeos (se carga dinámicamente) -->
                <div id="trophiesList" class="trophies-list">
                    <!-- Los trofeos se cargarán aquí dinámicamente -->
                </div>
            </div>
        </section>
    </main>
        
    <!-- Script principal de la página -->
    <script src="Javascripts/index.js"></script>
    <!-- Incluye el footer -->
    <?php include 'estilos/footer.php'; ?>
    </div>
</body>
</html>
