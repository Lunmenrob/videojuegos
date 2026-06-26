<?php
require_once 'auth.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Detalles Juego</title>
<link rel="stylesheet" href="estilos/detalles.css?v=8">
<link rel="stylesheet" href="estilos/detalles_dlcs.css?v=8">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'estilos/header.php'; ?>
    <div class="edit-actions">
        <a href="#dlcs-section" class="dlcs-btn" aria-label="Ir a DLCs">
            DLC
        </a>
        <a href="index.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>
        <a href="editar_juego.php?id=" id="edit-link-actions" class="edit-btn">
            <i class="fas fa-edit"></i>
        </a>
    </div>
    <div class="page-content">
    <div class="game-header">
        <div class="game-icon">
            <img id="game-icon-img" src="" alt="Icono del juego">
            <i class="fas fa-gamepad" id="game-icon-fallback"></i>
        </div>
        <div class="game-info">
            <h1 class="game-title" id="titulo">Cargando...</h1>
            <div class="platform-badge" id="plataforma">Cargando...</div>
        </div>
        <div class="header-actions">
            <a href="#dlcs-section" class="dlcs-btn" aria-label="Ir a DLCs">DLCs</a>
            <a href="index.php" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <a href="editar_juego.php?id=" id="edit-link-header" class="edit-btn">
                <i class="fas fa-edit"></i>
            </a>
        </div>
    </div>
    <div class="game-banner">
        <img id="game-banner-img" src="" alt="Banner del juego">
        <div class="banner-info-overlay">
            <div class="banner-info-content">
                <h3 class="banner-info-title" id="banner-titulo">Cargando...</h3>
                <div class="banner-info-platform">
                    <i class="fas fa-gamepad" id="banner-platform-icon"></i>
                    <span id="banner-plataforma">Cargando...</span>
                </div>
                <div class="banner-info-details">
                    <div class="banner-info-item">
                        <span class="banner-info-label">Lanzamiento:</span>
                        <span class="banner-info-value" id="banner-lanzamiento">--</span>
                    </div>
                    <div class="banner-info-item">
                        <span class="banner-info-label">Editor:</span>
                        <span class="banner-info-value" id="banner-editor">--</span>
                    </div>
                    <div class="banner-info-item">
                        <span class="banner-info-label">Géneros:</span>
                        <span class="banner-info-value" id="banner-generos">--</span>
                    </div>
                </div>
                <div class="banner-info-classifications">
                    <div class="classification-item">
                        <img id="banner-pegi" src="" alt="PEGI" class="classification-img">
                    </div>
                    <div class="classification-item" id="banner-1-container">
                        <img id="banner-1" src="" alt="Ban1" class="classification-img">
                    </div>
                    <div class="classification-item" id="banner-2-container">
                        <img id="banner-2" src="" alt="Ban2" class="classification-img">
                    </div>
                    <div class="classification-item" id="banner-3-container">
                        <img id="banner-3" src="" alt="Ban3" class="classification-img">
                    </div>
                </div>
                <a id="mapa-interactivo-link" class="mapa-interactivo-btn" href="#" target="_blank" rel="noopener noreferrer" style="display: none;">
                    IR A MAPA INTERACTIVO
                </a>
            </div>
        </div>
    </div>

    <!-- Galería de medios -->
    <div class="content-card media-gallery-card">
        <div class="media-carousel" id="media-carousel-container">
            <button class="media-carousel-btn media-carousel-btn--prev" type="button" aria-label="Anterior">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="media-carousel-viewport">
                <div class="media-carousel-track"></div>
            </div>
            <button class="media-carousel-btn media-carousel-btn--next" type="button" aria-label="Siguiente">
                <i class="fas fa-chevron-right"></i>
            </button>
            <div class="media-carousel-dots"></div>
        </div>
    </div>

    <div class="content-card">
        <div class="trophies-first-row">
            <div class="trophies-left">
                <div class="trophy-number" id="trofeos-ganados">0</div>
                <div class="trophy-label">ganados</div>
            </div>
            <div class="trophies-center">
                <div class="progress-circle">
                    <div class="progress-text" id="porcentaje">0%</div>
                </div>
            </div>
            <div class="trophies-right">
                <div class="trophy-number" id="trofeos-disponibles">0</div>
                <div class="trophy-label">disponibles</div>
            </div>
        </div>
        <div class="trophies-second-row">
            <div class="trophy-item">
                <img src="interfaz/trofeos/platino.png" alt="Platino" class="trophy-img">
                <div class="trophy-count" id="platino-count">0</div>
            </div>
            <div class="trophy-item">
                <img src="interfaz/trofeos/oro.png" alt="Oro" class="trophy-img">
                <div class="trophy-count" id="oro-count">0</div>
            </div>
            <div class="trophy-item">
                <img src="interfaz/trofeos/plata.png" alt="Plata" class="trophy-img">
                <div class="trophy-count" id="plata-count">0</div>
            </div>
            <div class="trophy-item">
                <img src="interfaz/trofeos/bronce.png" alt="Bronce" class="trophy-img">
                <div class="trophy-count" id="bronce-count">0</div>
            </div>
        </div>
    </div>
    <div class="content-card extra-card">
        <div class="game-info-content">
            <div class="info-row">
                <span class="info-label">Dificultad del Platino:</span>
                <span class="info-value" id="dificultad">01 sobre 10</span>
            </div>
            <div class="info-row">
                <span class="info-label">Duración estimada para el platino:</span>
                <span class="info-value" id="duracion">--</span>
            </div>
            <div class="info-row">
                <span class="info-label">Trofeos Offline:</span>
                <div class="trophy-summary" id="trofeos-offline">
                    <div class="trophy-mini">
                        <img src="interfaz/trofeos/platino.png" alt="Platino">
                        <span id="offline-platino">0</span>
                    </div>
                    <div class="trophy-mini">
                        <img src="interfaz/trofeos/oro.png" alt="Oro">
                        <span id="offline-oro">0</span>
                    </div>
                    <div class="trophy-mini">
                        <img src="interfaz/trofeos/plata.png" alt="Plata">
                        <span id="offline-plata">0</span>
                    </div>
                    <div class="trophy-mini">
                        <img src="interfaz/trofeos/bronce.png" alt="Bronce">
                        <span id="offline-bronce">0</span>
                    </div>
                </div>
            </div>
            <div class="info-row">
                <span class="info-label">Trofeos Online:</span>
                <div class="trophy-summary" id="trofeos-online">
                    <div class="trophy-mini">
                        <img src="interfaz/trofeos/platino.png" alt="Platino">
                        <span id="online-platino">0</span>
                    </div>
                    <div class="trophy-mini">
                        <img src="interfaz/trofeos/oro.png" alt="Oro">
                        <span id="online-oro">0</span>
                    </div>
                    <div class="trophy-mini">
                        <img src="interfaz/trofeos/plata.png" alt="Plata">
                        <span id="online-plata">0</span>
                    </div>
                    <div class="trophy-mini">
                        <img src="interfaz/trofeos/bronce.png" alt="Bronce">
                        <span id="online-bronce">0</span>
                    </div>
                </div>
            </div>
            <div class="info-row">
                <span class="info-label">Pase Online:</span>
                <span class="info-value" id="pase-online">No</span>
            </div>
            <div class="info-row">
                <span class="info-label">Necesario para el Platino:</span>
                <span class="info-value" id="necesario-platino">NO (Pero requiere internet)</span>
            </div>
            <div class="info-row">
                <span class="info-label">Trofeos Ocultos:</span>
                <span class="info-value" id="trofeos-ocultos">--</span>
            </div>
            <div class="info-row">
                <span class="info-label">Mínimo de Partidas para el Platino:</span>
                <span class="info-value" id="min-partidas">1 Partida</span>
            </div>
            <div class="info-row">
                <span class="info-label">Trofeos Perdibles:</span>
                <span class="info-value" id="trofeos-perdibles">--</span>
            </div>
            <div class="info-row">
                <span class="info-label">¿Activar los trucos afecta a los trofeos?</span>
                <span class="info-value" id="trucos-afectan">No</span>
            </div>
            <div class="info-row">
                <span class="info-label">¿Influye la dificultad en los trofeos?</span>
                <span class="info-value" id="dificultad-afecta">No</span>
            </div>
            <div class="info-row">
                <span class="info-label">Total de Trofeos:</span>
                <div class="trophy-summary" id="total-trofeos">
                    <div class="trophy-mini">
                        <img src="interfaz/trofeos/platino.png" alt="Platino">
                        <span id="total-platino">0</span>
                    </div>
                    <div class="trophy-mini">
                        <img src="interfaz/trofeos/oro.png" alt="Oro">
                        <span id="total-oro">0</span>
                    </div>
                    <div class="trophy-mini">
                        <img src="interfaz/trofeos/plata.png" alt="Plata">
                        <span id="total-plata">0</span>
                    </div>
                    <div class="trophy-mini">
                        <img src="interfaz/trofeos/bronce.png" alt="Bronce">
                        <span id="total-bronce">0</span>
                    </div>
                </div>
            </div>
            <div class="info-row full-width">
                <span class="info-label">Comentario:</span>
                <p class="info-comment" id="comentario">--</p>
            </div>
        </div>
    </div>
    
    <!-- Lightbox para imágenes -->
    <div id="lightbox" class="lightbox" onclick="closeLightbox()">
        <img id="lightbox-img" src="" alt="Imagen ampliada">
    </div>
    
    <div class="trophies-section">
        <div class="trophies-header">
            <h2 class="trophies-title">Todos los trofeos</h2>
            <div class="filter-dropdown">
                <select id="trophy-filter">
                    <option value="todos">Todos</option>
                    <option value="ganados">Ganados</option>
                    <option value="no-ganados">No ganados</option>
                </select>
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </div>
        </div>
        <div class="trophies-list" id="trophies-list">
            <!-- Los trofeos se cargarán aquí dinámicamente -->
        </div>
    </div>
    <div class="dlcs-section" id="dlcs-section">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <h2 class="trophies-title" style="margin: 0;">DLCs</h2>
                <div id="dlc-icons-container" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    <!-- Los iconos de DLCs se cargarán aquí dinámicamente -->
                </div>
            </div>
        </div>
        <div class="dlcs-list" id="dlcs-list">
            <!-- Los DLCs se cargarán aquí dinámicamente -->
        </div>
    </div>
    </div>
    </div>
<?php include 'carousel.php'; ?>
<script src="Javascripts/detalles.js?v=8"></script>
<?php include 'estilos/footer.php'; ?>
</body>
</html>
