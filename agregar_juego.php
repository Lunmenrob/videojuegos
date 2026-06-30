<?php
<<<<<<< HEAD
/**
 * Página de creación de nuevo juego
 * Permite añadir un juego nuevo al sistema:
 * - Datos básicos (nombre, plataforma, género, fecha, desarrollador, editor)
 * - Imágenes (icono, banner, PEGI, clasificaciones)
 * - Información de trofeos (offline, online, dificultad, duración)
 * - Galería multimedia
 * - Trofeos individuales (crear, editar, eliminar)
 * - DLCs con sus trofeos
 * - Mapas interactivos
 * Requiere autenticación (auth.php) y protección CSRF (csrf.php)
 */
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
require_once 'auth.php';
require_once 'csrf.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Agregar Nuevo Juego</title>
    <link rel="stylesheet" href="estilos/editar_juego.css?v=4">
<<<<<<< HEAD
    <link rel="stylesheet" href="estilos/juego_comun.css?v=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="Javascripts/juego_comun.js"></script>
    <script src="Javascripts/editar_juego.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
=======
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="Javascripts/editar_juego.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <style>
        .trophy-item-info img, .dlc-trophy-form img, .trophy-form-inline img, .trophy-item-edit img, #trophies-list img, #dlc-trophies-list img {
            max-width: 50px !important;
            max-height: 50px !important;
            width: 50px !important;
            height: 50px !important;
            object-fit: cover !important;
        }
        .dlc-item-edit {
            border-bottom: 2px solid #2d2847;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }
        .dlc-item-edit:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .dlc-form-inline > div:first-child > img {
            max-width: 50px !important;
            max-height: 50px !important;
            width: 50px !important;
            height: 50px !important;
            object-fit: cover !important;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function fixTrophyImageSizes() {
                const containers = document.querySelectorAll('.trophy-item-info, .dlc-trophy-form, .trophy-form-inline, .trophy-item-edit, #trophies-list, #dlc-trophies-list, .dlc-form-inline');
                containers.forEach(container => {
                    const images = container.querySelectorAll('img');
                    images.forEach(img => {
                        img.style.setProperty('width', '50px', 'important');
                        img.style.setProperty('height', '50px', 'important');
                        img.style.setProperty('max-width', '50px', 'important');
                        img.style.setProperty('max-height', '50px', 'important');
                    });
                });
                // Also fix images in DLC edit form header
                const dlcEditHeaders = document.querySelectorAll('#dlcs-list > div > div[style*="display: flex"]');
                dlcEditHeaders.forEach(header => {
                    const img = header.querySelector('img');
                    if (img) {
                        img.style.setProperty('width', '50px', 'important');
                        img.style.setProperty('height', '50px', 'important');
                        img.style.setProperty('max-width', '50px', 'important');
                        img.style.setProperty('max-height', '50px', 'important');
                    }
                });
            }
            fixTrophyImageSizes();
            setTimeout(fixTrophyImageSizes, 1000);
        });
    </script>
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
    </head>
<body>
    <?php include 'estilos/header.php'; ?>
    <div class="page-content">
    <form id="game-form" method="POST" action="guardar_juego.php" enctype="multipart/form-data">
    <?php echo csrfField(); ?>
    <div class="edit-header">
        <h1 class="edit-title">Agregar Nuevo Juego</h1>
        <div class="edit-actions">
            <a href="detalles.php?id=<?php echo htmlspecialchars($gameId ?? $_GET['id'] ?? ''); ?>" id="back-link" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>
            <a href="detalles.php?id=<?php echo htmlspecialchars($gameId ?? $_GET['id'] ?? ''); ?>" id="cancel-link" class="cancel-btn">
                <i class="fas fa-times"></i>
            </a>
            <button type="button" id="scroll-to-dlcs-btn" class="scroll-btn" onclick="scrollToDLCs()">
                <i class="fas fa-box"></i>
            </button>
            <button type="button" id="save-btn" class="save-btn">
                <i class="fas fa-save"></i>
            </button>
        </div>
    </div>
    


    <div class="edit-content">
        <input type="hidden" id="game-id" name="id" value="">
        <div class="edit-section">
            <h2 class="section-title">Datos del Juego</h2>
<<<<<<< HEAD
            <div class="section-divider"></div>
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
            
            <div class="form-row">
                <div class="form-group">
                    <label for="titulo">Nombre del juego</label>
                    <input type="text" id="titulo" name="titulo" required maxlength="200">
                </div>
                <div class="form-group">
                    <label for="plataforma">Plataforma</label>
                    <select id="plataforma" name="plataforma">
                        <option value="PS4">PS4</option>
                        <option value="PS5">PS5</option>
                        <option value="AMBOS">AMBOS</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fecha_lanzamiento">Fecha de lanzamiento</label>
                    <input type="date" id="fecha_lanzamiento" name="fecha_lanzamiento">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="genero">Género</label>
                    <input type="text" id="genero" name="genero" placeholder="Ej: Acción, Aventura" maxlength="100">
                </div>
                <div class="form-group">
                    <label for="desarrollador">Desarrollador</label>
                    <input type="text" id="desarrollador" name="desarrollador" placeholder="Ej: Kojima Productions" maxlength="100">
                </div>
                <div class="form-group">
                    <label for="dificultad_platino">Dificultad del platino</label>
                    <select id="dificultad_platino" name="dificultad_platino">
                        <option value="1 sobre 10">1 sobre 10</option>
                        <option value="2 sobre 10">2 sobre 10</option>
                        <option value="3 sobre 10">3 sobre 10</option>
                        <option value="4 sobre 10">4 sobre 10</option>
                        <option value="5 sobre 10">5 sobre 10</option>
                        <option value="6 sobre 10">6 sobre 10</option>
                        <option value="7 sobre 10">7 sobre 10</option>
                        <option value="8 sobre 10">8 sobre 10</option>
                        <option value="9 sobre 10">9 sobre 10</option>
                        <option value="10 sobre 10">10 sobre 10</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="duracion_estimada">Duración estimada</label>
                    <input type="text" id="duracion_estimada" name="duracion_estimada" placeholder="Ej: 100 horas" maxlength="100">
                </div>
            </div>
            
<<<<<<< HEAD
            <div class="section-divider"></div>
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
            <div class="form-group">
                <label>Icono del juego</label>
                <div class="image-inline-editor">
                    <div class="image-inline-preview">
                        <img id="current-icon" alt="Icono actual">
                    </div>
                    <div class="image-inline-controls">
                        <input type="text" id="icono-url" name="icono_url" placeholder="URL de la imagen" maxlength="500">
                        <label class="image-file-btn">
                            <input type="file" id="icono" name="icono_file" accept="image/*">
<<<<<<< HEAD
                            <i class="fas fa-upload"></i>
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                            <span>Subir archivo</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Banner del juego</label>
                <div class="image-inline-editor image-inline-editor--wide">
                    <div class="image-inline-preview image-inline-preview--banner">
                        <img id="current-banner" alt="Banner actual">
                    </div>
                    <div class="image-inline-controls">
                        <input type="text" id="banner-url" name="banner_url" placeholder="URL del banner" maxlength="500">
                        <label class="image-file-btn">
                            <input type="file" id="banner" name="banner_file" accept="image/*">
<<<<<<< HEAD
                            <i class="fas fa-upload"></i>
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                            <span>Subir archivo</span>
                        </label>
                        <button type="button" id="crop-banner-btn" class="crop-btn">Recortar Banner</button>
                    </div>
                </div>
                <!-- Campos ocultos para coordenadas de recorte -->
                <input type="hidden" id="banner-crop-x" name="banner_crop_x" value="50">
                <input type="hidden" id="banner-crop-y" name="banner_crop_y" value="37">
                <input type="hidden" id="banner-crop-width" name="banner_crop_width" value="100">
                <input type="hidden" id="banner-crop-height" name="banner_crop_height" value="100">
            </div>
            <!-- Modal de recorte de banner -->
            <div id="banner-crop-modal" class="crop-modal">
                <div class="crop-modal-content">
                    <div class="crop-modal-header">
                        <h3>Recortar Banner</h3>
                        <button type="button" class="crop-modal-close" id="close-crop-modal">&times;</button>
                    </div>
                    <div class="crop-modal-body">
                        <div class="crop-container">
                            <img id="banner-crop-image" src="" alt="Imagen para recortar">
                        </div>
                    </div>
                    <div class="crop-modal-footer">
                        <button type="button" class="crop-modal-cancel" id="cancel-crop">Cancelar</button>
                        <button type="button" class="crop-modal-apply" id="apply-crop">Aplicar</button>
                    </div>
                </div>
            </div>
<<<<<<< HEAD
            <div class="section-divider"></div>
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
<!-- PEGI -->
<div class="form-group">
    <label>PEGI</label>
    <div class="image-inline-editor">
        <div class="image-inline-preview image-inline-preview--small">
            <img id="current-pegi" alt="PEGI actual">
        </div>
        <div class="image-inline-controls">
            <input type="text" id="pegi-url" name="pegi_url" placeholder="URL del PEGI" maxlength="500">
            <label class="image-file-btn">
                <input type="file" id="pegi" name="pegi_file" accept="image/*">
<<<<<<< HEAD
                <i class="fas fa-upload"></i>
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                <span>Subir archivo</span>
            </label>
        </div>
    </div>
</div>

<!-- CLASIFICACIÓN 1 -->
<div class="form-group" id="clas1-group">
    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
        <label style="margin: 0;">Clasificación 1</label>
        <label style="display: flex; align-items: center; gap: 0.25rem; margin: 0; font-size: 0.85rem; color: #c4c4c4;">
            <input type="checkbox" id="show-clas1" name="show_clas1" checked> Mostrar
        </label>
    </div>
    <div class="image-inline-editor">
        <div class="image-inline-preview image-inline-preview--small">
            <img id="current-clas1" alt="Clasificación 1 actual">
        </div>
        <div class="image-inline-controls">
            <input type="text" id="clasificacion1-url" name="clasificacion_1_url" placeholder="URL de la clasificación" maxlength="500">
            <label class="image-file-btn">
                <input type="file" id="clasificacion1" name="clasificacion_1_file" accept="image/*">
<<<<<<< HEAD
                <i class="fas fa-upload"></i>
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                <span>Subir archivo</span>
            </label>
        </div>
    </div>
</div>

<!-- CLASIFICACIÓN 2 -->
<div class="form-group" id="clas2-group">
    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
        <label style="margin: 0;">Clasificación 2</label>
        <label style="display: flex; align-items: center; gap: 0.25rem; margin: 0; font-size: 0.85rem; color: #c4c4c4;">
            <input type="checkbox" id="show-clas2" name="show_clas2" checked> Mostrar
        </label>
    </div>
    <div class="image-inline-editor">
        <div class="image-inline-preview image-inline-preview--small">
            <img id="current-clas2" alt="Clasificación 2 actual">
        </div>
        <div class="image-inline-controls">
            <input type="text" id="clasificacion2-url" name="clasificacion_2_url" placeholder="URL de la clasificación" maxlength="500">
            <label class="image-file-btn">
                <input type="file" id="clasificacion2" name="clasificacion_2_file" accept="image/*">
<<<<<<< HEAD
                <i class="fas fa-upload"></i>
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                <span>Subir archivo</span>
            </label>
        </div>
    </div>
</div>

<!-- CLASIFICACIÓN 3 -->
<div class="form-group" id="clas3-group">
    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
        <label style="margin: 0;">Clasificación 3</label>
        <label style="display: flex; align-items: center; gap: 0.25rem; margin: 0; font-size: 0.85rem; color: #c4c4c4;">
            <input type="checkbox" id="show-clas3" name="show_clas3" checked> Mostrar
        </label>
    </div>
    <div class="image-inline-editor">
        <div class="image-inline-preview image-inline-preview--small">
            <img id="current-clas3" alt="Clasificación 3 actual">
        </div>
        <div class="image-inline-controls">
            <input type="text" id="clasificacion3-url" name="clasificacion_3_url" placeholder="URL de la clasificación" maxlength="500">
            <label class="image-file-btn">
                <input type="file" id="clasificacion3" name="clasificacion_3_file" accept="image/*">
<<<<<<< HEAD
                <i class="fas fa-upload"></i>
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                <span>Subir archivo</span>
            </label>
        </div>
    </div>
</div>
<<<<<<< HEAD
<div class="section-divider"></div>

<div class="form-group">
    <label>Mapas Interactivos</label>
    <div class="form-row">
        <div class="form-group" style="flex: 2; min-width: 240px;">
            <label for="mapa-url">URL del mapa</label>
            <input type="text" id="mapa-url" placeholder="URL del mapa interactivo" maxlength="500">
        </div>
        <div class="form-group" style="flex: 1; min-width: 180px;">
            <label for="mapa-nombre">Nombre (texto del botón)</label>
            <input type="text" id="mapa-nombre" placeholder="Ej: Mapa Offline" maxlength="255">
        </div>
        <div class="form-group" style="align-self: end; display: flex; gap: 0.5rem;">
            <button type="button" id="add-mapa-btn" class="add-trophy-btn" onclick="addMapaItem()">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div id="mapas-list" class="mapas-list"></div>
    <input type="hidden" id="mapas-json" name="mapas_json" value="[]">
</div>

<div class="gallery-warning">
    <i class="fas fa-info-circle"></i>
    <span>La galería multimedia se guarda por separado. Usa el botón <i class="fas fa-save"></i> de guardar galería para guardar los cambios.</span>
=======

<div class="form-group">
    <label for="mapa-interactivo-url">Mapa interactivo</label>
    <input type="text" id="mapa-interactivo-url" name="mapa_interactivo_url" placeholder="URL del mapa interactivo" maxlength="500">
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
</div>

<div class="form-group">
    <label>Galería multimedia</label>
    <div class="form-row">
        <div class="form-group" style="flex: 2; min-width: 240px;">
            <label for="media-url">URL</label>
            <input type="text" id="media-url" placeholder="URL de imagen o vídeo">
        </div>
        <div class="form-group" style="flex: 1; min-width: 180px;">
            <label for="media-type">Tipo</label>
            <select id="media-type">
                <option value="image">Imagen</option>
                <option value="video">Vídeo</option>
            </select>
        </div>
        <div class="form-group" style="align-self: end; display: flex; gap: 0.5rem;">
            <button type="button" id="add-media-btn" class="add-trophy-btn" onclick="addMediaItem()">
                <i class="fas fa-plus"></i>
            </button>
<<<<<<< HEAD
            <input type="file" id="media-file" accept="image/*,video/*" style="display: none;" onchange="uploadMediaFile()">
            <button type="button" class="add-trophy-btn" onclick="document.getElementById('media-file').click()">
                <i class="fas fa-upload"></i>
            </button>
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
            <button type="button" id="save-media-btn" class="save-btn">
                <i class="fas fa-save"></i>
            </button>
        </div>
    </div>
    <div id="media-gallery-editor" class="media-gallery-editor"></div>
    <input type="hidden" id="media-items-json" name="media_items_json" value="[]">
</div>
<<<<<<< HEAD
<div class="section-divider"></div>

            <div class="form-group">
=======

            <div class="form-group">
                <label for="comentario">Comentarios y consejos</label>
                <div id="comentario-editor" style="height: 200px; background: #1a1a1a; resize: both; overflow: auto; min-height: 200px;"></div>
                <input type="hidden" id="comentario" name="comentario">
            </div>
            
            <div class="form-group">
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                <label>Trofeos Offline</label>
                <div class="form-row">
                    <div class="form-group">
                        <label>Platino</label>
                        <input type="number" id="trofeos_offline_platino" name="trofeos_offline_platino" min="0" max="99" placeholder="0" onchange="calcularTotalTrofeos()">
                    </div>
                    <div class="form-group">
                        <label>Oro</label>
                        <input type="number" id="trofeos_offline_oro" name="trofeos_offline_oro" min="0" max="99" placeholder="0" onchange="calcularTotalTrofeos()">
                    </div>
                    <div class="form-group">
                        <label>Plata</label>
                        <input type="number" id="trofeos_offline_plata" name="trofeos_offline_plata" min="0" max="99" placeholder="0" onchange="calcularTotalTrofeos()">
                    </div>
                    <div class="form-group">
                        <label>Bronce</label>
                        <input type="number" id="trofeos_offline_bronce" name="trofeos_offline_bronce" min="0" max="99" placeholder="0" onchange="calcularTotalTrofeos()">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Trofeos Online</label>
                <div class="form-row">
                    <div class="form-group">
                        <label>Platino</label>
                        <input type="number" id="trofeos_online_platino" name="trofeos_online_platino" min="0" max="99" placeholder="0" onchange="calcularTotalTrofeos()">
                    </div>
                    <div class="form-group">
                        <label>Oro</label>
                        <input type="number" id="trofeos_online_oro" name="trofeos_online_oro" min="0" max="99" placeholder="0" onchange="calcularTotalTrofeos()">
                    </div>
                    <div class="form-group">
                        <label>Plata</label>
                        <input type="number" id="trofeos_online_plata" name="trofeos_online_plata" min="0" max="99" placeholder="0" onchange="calcularTotalTrofeos()">
                    </div>
                    <div class="form-group">
                        <label>Bronce</label>
                        <input type="number" id="trofeos_online_bronce" name="trofeos_online_bronce" min="0" max="99" placeholder="0" onchange="calcularTotalTrofeos()">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Total de Trofeos: <span id="total-trofeos">0</span></label>
                <button type="button" onclick="calcularTotalTrofeos()" style="margin-left: 10px; padding: 5px 10px;">Calcular</button>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Pase Online</label>
                    <select id="pase_online" name="pase_online">
                        <option value="0">No</option>
                        <option value="1">Sí</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Necesario Platino</label>
                    <select id="necesario_platino" name="necesario_platino">
                        <option value="NO">NO</option>
                        <option value="NO (Pero requiere internet)">NO (Pero requiere internet)</option>
                        <option value="SÍ">SÍ</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Mínimo de Partidas</label>
                    <select id="min_partidas" name="min_partidas">
                        <option value="1 Partida">1 Partida</option>
                        <option value="2 Partidas">2 Partidas</option>
                        <option value="3 Partidas">3 Partidas</option>
                        <option value="4+ Partidas">4+ Partidas</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Trucos afectan</label>
                    <select id="trucos_afectan" name="trucos_afectan">
                        <option value="0">No</option>
                        <option value="1">Sí</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Dificultad afecta</label>
                    <select id="dificultad_afecta" name="dificultad_afecta">
                        <option value="0">No</option>
                        <option value="1">Sí</option>
                    </select>
                </div>
            </div>
<<<<<<< HEAD
            <div class="section-divider"></div>
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a

            <div class="form-group">
                <label for="trofeos-ocultos-editor">Trofeos Ocultos</label>
                <div id="trofeos-ocultos-editor" style="height: 180px; background: #1a1a1a; resize: both; overflow: auto; min-height: 180px;"></div>
                <input type="hidden" id="trofeos_ocultos" name="trofeos_ocultos">
            </div>

            <div class="form-group">
                <label for="trofeos-perdibles-editor">Trofeos Perdibles</label>
                <div id="trofeos-perdibles-editor" style="height: 180px; background: #1a1a1a; resize: both; overflow: auto; min-height: 180px;"></div>
                <input type="hidden" id="trofeos_perdibles" name="trofeos_perdibles">
            </div>
<<<<<<< HEAD
            <div class="section-divider"></div>
            <div class="form-group">
                <label for="comentario">Comentarios y consejos</label>
                <div id="comentario-editor" style="height: 200px; background: #1a1a1a; resize: both; overflow: auto; min-height: 200px;"></div>
                <input type="hidden" id="comentario" name="comentario">
            </div>
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
        </div>
        </div>
        
        <div class="edit-section">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                <h2 class="section-title" style="margin: 0;">Trofeos</h2>
                <div class="trophy-actions" style="margin: 0;">
                    <button type="button" id="add-trophy-btn" class="add-trophy-btn" onclick="showAddTrophyForm()">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div id="trophies-list" class="trophies-list">
                <div class="loading-message">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p>Cargando trofeos...</p>
                </div>
            </div>
        </div>
        
        <div class="edit-section" id="dlcs-section">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <h2 class="section-title" style="margin: 0;">DLCs</h2>
                    <div id="dlc-icons-container" style="display: flex; gap: 0.5rem; flex-wrap: wrap;"></div>
                </div>
                <div class="trophy-actions" style="margin: 0;">
                    <button type="button" id="add-dlc-btn" class="add-trophy-btn" onclick="showAddDLCForm()">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div id="dlcs-list" class="trophies-list">
                <div class="loading-message">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p>Cargando DLCs...</p>
                </div>
            </div>
        </div>
    </div>
    </form>

<script>
// Función para calcular el total de trofeos
function calcularTotalTrofeos() {
    console.log('Calculando total de trofeos...');
    
    // Verificar que los campos existen
    const campos = [
        'trofeos_offline_platino', 'trofeos_offline_oro', 'trofeos_offline_plata', 'trofeos_offline_bronce',
        'trofeos_online_platino', 'trofeos_online_oro', 'trofeos_online_plata', 'trofeos_online_bronce'
    ];
    
    let valores = [];
    for (let campo of campos) {
        const elemento = document.getElementById(campo);
        if (elemento) {
            const valor = parseInt(elemento.value) || 0;
            valores.push(valor);
            console.log(`📋 ${campo}: ${elemento.value} -> ${valor}`);
        } else {
            console.error(`❌ No se encontró el campo: ${campo}`);
            valores.push(0);
        }
    }
    
    const total = valores.reduce((a, b) => a + b, 0);
    console.log('🔢 Total calculado:', total);
    
    const totalElement = document.getElementById('total-trofeos');
    if (totalElement) {
        totalElement.textContent = total;
        console.log('✅ Total actualizado en el DOM');
        alert('Total calculado: ' + total);
    } else {
        console.error('❌ No se encontró el elemento total-trofeos');
        alert('Error: No se encontró el elemento total-trofeos');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Agregar Juego - DOM cargado');
    
    // No requerir ID para agregar nuevo juego
    document.getElementById('game-id').value = '';
    document.getElementById('cancel-link').href = 'index.php';
    document.getElementById('back-link').href = 'index.php';

    // Función para hacer scroll hasta la sección de DLCs
    window.scrollToDLCs = function() {
        const dlcsSection = document.getElementById('dlcs-section');
        if (dlcsSection) {
            dlcsSection.scrollIntoView({ behavior: 'smooth' });
        }
    };
    
    // Función para hacer scroll hasta un DLC específico
    window.scrollToDLC = function(dlcId) {
        const dlcElement = document.querySelector(`[data-dlc-id="${dlcId}"]`);
        if (dlcElement) {
            dlcElement.scrollIntoView({ behavior: 'smooth' });
        }
    };

    const form = document.getElementById('game-form');
    const saveBtn = document.getElementById('save-btn');
    
<<<<<<< HEAD
    console.log('Formulario encontrado:', form);
    console.log('Botón guardar encontrado:', saveBtn);
    
    if (form && saveBtn) {
        console.log('Registrando event listener en botón guardar');
        saveBtn.addEventListener('click', function(e) {
            e.preventDefault();
=======
    if (form && saveBtn) {
        saveBtn.addEventListener('click', function(e) {
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
            console.log('Botón guardar clickeado, mediaItems:', mediaItems);
            const mediaItemsJson = JSON.stringify(mediaItems);
            document.getElementById('media-items-json').value = mediaItemsJson;
            console.log('media-items-json value:', mediaItemsJson);
            console.log('media-items-json element value:', document.getElementById('media-items-json').value);
            
<<<<<<< HEAD
            // Actualizar mapas interactivos
            console.log('Llamando updateMapasJson()...');
            if (typeof updateMapasJson === 'function') {
                updateMapasJson();
                console.log('mapas-json value después de updateMapasJson():', document.getElementById('mapas-json').value);
            } else {
                console.error('updateMapasJson no es una función');
            }
            
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
            // Verificar que el campo tiene el valor correcto antes de enviar
            if (document.getElementById('media-items-json').value !== mediaItemsJson) {
                console.error('Error: El campo media-items-json no tiene el valor correcto');
            }
            
<<<<<<< HEAD
            console.log('Enviando formulario...');
            form.submit();
        });
    } else {
        console.error('No se encontró el formulario o el botón de guardar');
=======
            form.submit();
        });
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
    }

    const saveMediaBtn = document.getElementById('save-media-btn');
    if (saveMediaBtn) {
        saveMediaBtn.addEventListener('click', function() {
            document.getElementById('media-items-json').value = JSON.stringify(mediaItems);
            // No guardar galería para juegos nuevos
            alert('Guarda el juego primero para poder gestionar la galería');
        });
    }
    
    // No cargar datos para juegos nuevos - formulario vacío
    document.getElementById('trophies-list').innerHTML = `
        <div class="loading-message">
            <i class="fas fa-trophy fa-3x"></i>
            <p>Añade trofeos después de guardar el juego</p>
        </div>
    `;
    
    document.getElementById('dlcs-list').innerHTML = `
        <div class="loading-message">
            <i class="fas fa-box fa-3x"></i>
            <p>Añade DLCs después de guardar el juego</p>
        </div>
    `;
});

let mediaItems = [];

function addMediaItem() {
    console.log('addMediaItem called');
    const url = document.getElementById('media-url').value.trim();
    const type = document.getElementById('media-type').value;
    
    console.log('URL:', url);
    console.log('Type:', type);
    console.log('mediaItems antes de añadir:', mediaItems);

    if (!url) {
        alert('Introduce una URL para añadir un elemento a la galería.');
        return;
    }

    mediaItems.push({
        tipo: type,
        url,
        orden: mediaItems.length
    });

    console.log('mediaItems después de añadir:', mediaItems);

    document.getElementById('media-url').value = '';
    document.getElementById('media-type').value = 'image';
    document.getElementById('media-items-json').value = JSON.stringify(mediaItems);
    renderMediaGalleryEditor();
}

<<<<<<< HEAD
// Función para subir archivo multimedia del juego
async function uploadMediaFile() {
    const fileInput = document.getElementById('media-file');
    const file = fileInput.files[0];

    if (!file) {
        alert('Selecciona un archivo para subir.');
        return;
    }

    // Determinar el tipo según el archivo
    const type = file.type.startsWith('video/') ? 'video' : 'image';

    const formData = new FormData();
    formData.append('file', file);
    formData.append('tipo', type);

    try {
        const response = await fetch('/Prácticas/videojuegos/api/game_media.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (response.ok) {
            mediaItems.push({
                tipo: type,
                url: result.url,
                orden: mediaItems.length
            });

            document.getElementById('media-items-json').value = JSON.stringify(mediaItems);
            renderMediaGalleryEditor();
            fileInput.value = '';
            alert('Archivo subido correctamente.');
        } else {
            alert('Error al subir el archivo: ' + (result.error || 'Error desconocido'));
        }
    } catch (error) {
        console.error('Error subiendo archivo:', error);
        alert('Error al subir el archivo.');
    }
}

=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
function removeMediaItem(index) {
    mediaItems.splice(index, 1);
    mediaItems = mediaItems.map((item, idx) => ({ ...item, orden: idx }));
    document.getElementById('media-items-json').value = JSON.stringify(mediaItems);
    renderMediaGalleryEditor();
}

function renderMediaGalleryEditor() {
    const container = document.getElementById('media-gallery-editor');
    if (!container) return;

    if (!mediaItems.length) {
        container.innerHTML = '<p style="color:#9ca3af; margin:0;">No hay elementos en la galería todavía.</p>';
        return;
    }

    container.innerHTML = `
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem;">
            ${mediaItems.map((item, index) => `
                <div style="position: relative; background:#1f1b35; border-radius:10px; overflow:hidden; aspect-ratio: 16/9;">
                    ${item.tipo === 'video'
                        ? `<div style="width:100%; height:100%; background:#0f0d1d; display:flex; align-items:center; justify-content:center;">
                            <span style="color:#fff; font-size:0.8rem;">Vídeo</span>
                           </div>`
                        : `<img src="${item.url}" alt="Media" style="width:100%; height:100%; object-fit:cover;">`
                    }
                    <button type="button" onclick="removeMediaItem(${index})" style="position: absolute; top: 5px; right: 5px; background: rgba(0,0,0,0.7); color: white; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `).join('')}
        </div>
    `;
}

async function loadMediaItems(gameId) {
    console.log('loadMediaItems called for gameId:', gameId);
    try {
        const response = await fetch(`api/game_media.php?game_id=${gameId}`);
        const data = await response.json();
        console.log('Media items loaded from API:', data);
        mediaItems = Array.isArray(data) ? data : [];
        console.log('mediaItems after loading:', mediaItems);
        document.getElementById('media-items-json').value = JSON.stringify(mediaItems);
        renderMediaGalleryEditor();
    } catch (error) {
        console.error('Error cargando galería multimedia:', error);
    }
}

async function saveMediaGallery(gameId) {
    try {
        if (!gameId) return;

        console.log('Guardando galería multimedia, mediaItems:', mediaItems);
        console.log('Game ID:', gameId);
        
        const mediaItemsJson = JSON.stringify(mediaItems);
        console.log('Media items JSON:', mediaItemsJson);
        
        const response = await fetch('guardar_juego.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                id: gameId,
                media_items_json: mediaItemsJson,
                save_gallery_only: 'true'
            })
        });

        console.log('Response status:', response.status);
        console.log('Response ok:', response.ok);

        if (!response.ok) {
            throw new Error('No se pudo guardar la galería');
        }

        const result = await response.json();
        console.log('Resultado de guardar galería:', result);

        await loadMediaItems(gameId);
        alert('Galería guardada correctamente.');
    } catch (error) {
        console.error('Error guardando galería multimedia:', error);
        alert('No se pudo guardar la galería.');
    }
}

// Función para cargar datos del juego
function loadGameData(gameId) {
    console.log('🎮 Cargando datos del juego...');
    
    fetch(`/Prácticas/videojuegos/api/game.php?id=${gameId}`)
        .then(res => {
            console.log('📡 Respuesta juegos:', res.status);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            return res.json();
        })
        .then(data => {
            console.log('✅ Datos del juego cargados:', data);
            
            if (data.error) {
                throw new Error(data.error);
            }
            
            // Cargar datos en el formulario
            document.getElementById('titulo').value = data.titulo || '';
            document.getElementById('plataforma').value = data.plataforma || 'PS4';
            document.getElementById('fecha_lanzamiento').value = data.fecha_lanzamiento || '';
            document.getElementById('genero').value = data.genero || '';
            document.getElementById('desarrollador').value = data.desarrollador || '';
            document.getElementById('dificultad_platino').value = data.dificultad_platino || '5 sobre 10';
            document.getElementById('duracion_estimada').value = data.duracion_estimada || '';
            document.getElementById('comentario').value = data.comentario || '';
            
            // Cargar campos de trofeos
            document.getElementById('trofeos_offline_platino').value = data.trofeos_offline_platino || '';
            document.getElementById('trofeos_offline_oro').value = data.trofeos_offline_oro || '';
            document.getElementById('trofeos_offline_plata').value = data.trofeos_offline_plata || '';
            document.getElementById('trofeos_offline_bronce').value = data.trofeos_offline_bronce || '';
            
            document.getElementById('trofeos_online_platino').value = data.trofeos_online_platino || '';
            document.getElementById('trofeos_online_oro').value = data.trofeos_online_oro || '';
            document.getElementById('trofeos_online_plata').value = data.trofeos_online_plata || '';
            document.getElementById('trofeos_online_bronce').value = data.trofeos_online_bronce || '';
            
            document.getElementById('pase_online').value = data.pase_online || '0';
            document.getElementById('necesario_platino').value = data.necesario_platino || 'NO';
            document.getElementById('trofeos_ocultos').value = data.trofeos_ocultos || '';
            document.getElementById('min_partidas').value = data.min_partidas || '1 Partida';
            document.getElementById('trofeos_perdibles').value = data.trofeos_perdibles || '';
            document.getElementById('trucos_afectan').value = data.trucos_afectan || '0';
            document.getElementById('dificultad_afecta').value = data.dificultad_afecta || '0';
            
            // Cargar el total de trofeos desde la base de datos
            if (data.total_trofeos) {
                document.getElementById('total-trofeos').textContent = data.total_trofeos;
            } else {
                // Si no hay total guardado, calcularlo
                calcularTotalTrofeos();
            }
            
            if (data.imagen_url) {
                document.getElementById('current-icon').src = data.imagen_url;
            }
            
            if (data.banner_url) {
                document.getElementById('current-banner').src = data.banner_url;
            }
            
            console.log('✅ Formulario de juego actualizado');
        })
        .catch(err => {
            console.error('❌ Error cargando juego:', err);
        });
}

// Función para cargar trofeos
function loadTrophies(gameId) {
    console.log('🏆 Cargando trofeos...');
    
    fetch(`/Prácticas/videojuegos/api/trophies.php?game_id=${gameId}`)
        .then(res => {
            console.log('📡 Respuesta trofeos:', res.status);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            return res.json();
        })
        .then(trophies => {
            console.log('✅ Trofeos cargados:', trophies.length, 'trofeos');
            
            if (!Array.isArray(trophies)) {
                throw new Error('Los datos de trofeos no son válidos');
            }
            
            renderTrophies(trophies);
        })
        .catch(err => {
            console.error('❌ Error cargando trofeos:', err);
            document.getElementById('trophies-list').innerHTML = 
                `<div class="error-message">❌ Error cargando trofeos: ${err.message}</div>`;
        });
}

// Función para renderizar trofeos
function renderTrophies(trophies) {
    console.log('Renderizando', trophies.length, 'trofeos');
    
    const container = document.getElementById('trophies-list');
    
    if (!trophies || trophies.length === 0) {
        container.innerHTML = `
            <div class="loading-message">
                <i class="fas fa-trophy fa-3x"></i>
                <p>No hay trofeos para este juego</p>
            </div>
        `;
        return;
    }
    
    const html = `
        ${trophies.map(trophy => `
            <div class="trophy-item-edit" data-trophy-id="${trophy.id}">
                <div class="trophy-item-icon">
                    <img src="${trophy.icono_url || 'interfaz/trofeos/default.png'}" 
                        style="width: 50px !important; height: 50px !important; object-fit: cover !important; border-radius: 8px !important;" 
                        alt="${trophy.nombre_trofeo}">
                </div>
                <div class="trophy-item-info" style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.3rem;">
                        <h4 class="trophy-item-name" style="margin: 0; font-weight: bold;">${trophy.nombre_trofeo}</h4>
                        <span class="trophy-item-type" style="background: ${trophy.tipo === 'BRONCE' ? '#cd7f32' : trophy.tipo === 'PLATA' ? '#c0c0c0' : trophy.tipo === 'ORO' ? '#ffd700' : '#4a90d9'}; color: ${trophy.tipo === 'BRONCE' ? '#fff' : trophy.tipo === 'PLATA' ? '#333' : trophy.tipo === 'ORO' ? '#333' : '#fff'}; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: bold;">${trophy.tipo}</span>
                    </div>
                    <p style="margin: 0 0 0.3rem 0; font-size: 0.85rem; color: #999;">${trophy.descripcion || 'Sin descripción'}</p>
                    ${trophy.instrucciones ? `<p style="margin: 0 0 0.3rem 0; font-size: 0.8rem; color: #888;"><i class="fas fa-info-circle"></i> ${trophy.instrucciones}</p>` : ''}
                    ${trophy.video_url ? `<div style="margin-top: 0.3rem;"><iframe width="200" height="120" src="${trophy.video_url.replace('watch?v=', 'embed/')}" frameborder="0" allowfullscreen style="border-radius: 8px;"></iframe></div>` : ''}
                    <small style="color: #666;">
                        ${trophy.conseguido ? '<i class="fas fa-trophy" style="color: #ffd700;"></i> Conseguido' : '<i class="fas fa-trophy" style="color: #999;"></i> No conseguido'}
                        ${trophy.perdible ? ' | <i class="fas fa-exclamation-triangle" style="color: #e53e3e;"></i> Perdible' : ''}
                    </small>
                </div>
                <div class="trophy-item-actions">
                    <button type="button" class="action-btn edit" onclick="editTrophyInline(${trophy.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="action-btn delete" onclick="deleteTrophy(${trophy.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `).join('')}
    `;
    
    container.innerHTML = html;
    console.log('✅ Trofeos renderizados correctamente');
}

// Función para mostrar formulario inline de añadir nuevo trofeo
function showAddTrophyForm() {
    console.log('➕ Mostrando formulario para añadir trofeo');
    
    const container = document.getElementById('trophies-list');
    const gameId = new URLSearchParams(window.location.search).get('id');
    
    container.innerHTML = `
        <div class="trophy-form-inline" style="background: #231f36; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <i class="fas fa-plus-circle" style="color: #ffffff; font-size: 1.5rem;"></i>
                <h4 style="color: #ffffff; margin: 0; font-weight: 300;">Nuevo Trofeo</h4>
            </div>
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" id="new-trophy-name" placeholder="Ej: Maestro de las armas">
            </div>
            <div class="form-group">
                <label>Tipo:</label>
                <div class="trophy-type-selector" id="new-trophy-type-container">
                    <div class="trophy-type-option" data-value="ORO" onclick="selectNewTrophyType('ORO')">
                        <img src="interfaz/trofeos/oro.png" alt="Oro" class="trophy-type-img">
                        <span>Oro</span>
                    </div>
                    <div class="trophy-type-option" data-value="PLATA" onclick="selectNewTrophyType('PLATA')">
                        <img src="interfaz/trofeos/plata.png" alt="Plata" class="trophy-type-img">
                        <span>Plata</span>
                    </div>
                    <div class="trophy-type-option selected" data-value="BRONCE" onclick="selectNewTrophyType('BRONCE')">
                        <img src="interfaz/trofeos/bronce.png" alt="Bronce" class="trophy-type-img">
                        <span>Bronce</span>
                    </div>
                    <div class="trophy-type-option" data-value="PLATINO" onclick="selectNewTrophyType('PLATINO')">
                        <img src="interfaz/trofeos/platino.png" alt="Platino" class="trophy-type-img">
                        <span>Platino</span>
                    </div>
                </div>
                <input type="hidden" id="new-trophy-type" value="BRONCE">
            </div>
            <div class="form-group">
                <label>Descripción:</label>
                <input type="text" id="new-trophy-desc" placeholder="Descripción del trofeo">
            </div>
            <div class="form-group">
                <label>Icono del trofeo</label>
                <div class="image-inline-editor">
                    <div class="image-inline-preview image-inline-preview--small">
                        <img id="new-trophy-icon-preview" src="interfaz/trofeos/default.png" alt="Preview del icono">
                    </div>
                    <div class="image-inline-controls">
                        <input type="text" id="new-trophy-icon" placeholder="URL de la imagen" maxlength="500">
                        <label class="image-file-btn">
                            <input type="file" id="new-trophy-icon-file" accept="image/*">
<<<<<<< HEAD
                            <i class="fas fa-upload"></i>
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                            <span>Subir archivo</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Cómo conseguirlo:</label>
                <div id="new-trophy-instrucciones-editor" style="height: 120px; background: #1a1a1a; resize: both; overflow: auto; min-height: 120px;"></div>
                <input type="hidden" id="new-trophy-instrucciones">
            </div>
            <div class="form-group">
                <label>Video URL (YouTube):</label>
                <input type="text" id="new-trophy-video" placeholder="https://youtube.com/watch?v=...">
            </div>
            <div class="form-group" style="display: flex; gap: 2rem; align-items: center;">
                <label style="display: flex; align-items: center; gap: 0.5rem; margin: 0;">
                    <input type="checkbox" id="new-trophy-conseguido"> Conseguido
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; margin: 0;">
                    <input type="checkbox" id="new-trophy-perdible"> Perdible
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem; margin: 0;">
                    <input type="checkbox" id="new-trophy-online"> Online
                </label>
            </div>
            <div style="text-align: center; margin-top: 15px; display: flex; gap: 10px; justify-content: center;">
                <button type="button" class="add-trophy-btn" onclick="saveNewTrophy(${gameId})">
                    <i class="fas fa-save"></i>
                </button>
                <button class="cancel-btn" onclick="cancelAddTrophy()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
    
    // Inicializar editor Quill para instrucciones de nuevo trofeo
    setTimeout(() => {
        quillNewTrophy = new Quill('#new-trophy-instrucciones-editor', {
            theme: 'snow',
            placeholder: 'Instrucciones para obtener el trofeo...',
            modules: {
                toolbar: {
                    container: RICH_TEXT_TOOLBAR,
                    handlers: {}
                }
            }
        });

        quillNewTrophy.on('text-change', function() {
            document.getElementById('new-trophy-instrucciones').value = quillNewTrophy.root.innerHTML;
        });
    }, 100);
}

// Función para seleccionar tipo en el formulario de nuevo trofeo
function selectNewTrophyType(type) {
    document.getElementById('new-trophy-type').value = type;
    const options = document.querySelectorAll('#new-trophy-type-container .trophy-type-option');
    options.forEach(opt => {
        opt.classList.remove('selected');
        if (opt.dataset.value === type) opt.classList.add('selected');
    });
}

// Función para cancelar añadir trofeo
function cancelAddTrophy() {
    const gameId = new URLSearchParams(window.location.search).get('id');
    loadTrophies(gameId);
}

// Función para guardar nuevo trofeo
function saveNewTrophy(gameId) {
    console.log('💾 Guardando nuevo trofeo...');

    // Forzar actualización del input de instrucciones antes de enviar
    const instruccionesInput = document.getElementById('new-trophy-instrucciones');
    const instruccionesEditor = document.querySelector('#new-trophy-instrucciones-editor .ql-editor');
    if (instruccionesEditor && instruccionesInput) {
        instruccionesInput.value = instruccionesEditor.innerHTML;
        console.log('📝 Instrucciones forzadas desde editor:', instruccionesInput.value);
    }

    const trophyData = {
        videojuegos_id: parseInt(gameId),
        nombre_trofeo: document.getElementById('new-trophy-name').value,
        descripcion: document.getElementById('new-trophy-desc').value,
        instrucciones: instruccionesInput ? instruccionesInput.value : '',
        tipo: document.getElementById('new-trophy-type').value,
        icono_url: document.getElementById('new-trophy-icon').value,
        video_url: document.getElementById('new-trophy-video').value,
        conseguido: document.getElementById('new-trophy-conseguido').checked ? 1 : 0,
        perdible: document.getElementById('new-trophy-perdible').checked ? 1 : 0,
        online: document.getElementById('new-trophy-online').checked ? 1 : 0
    };

    if (!trophyData.nombre_trofeo) {
        alert('❌ Debes ingresar el nombre del trofeo');
        return;
    }
    
    fetch(`/Prácticas/videojuegos/api/trophies.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(trophyData)
    })
    .then(res => res.json())
    .then(data => {
        console.log('✅ Nuevo trofeo añadido:', data);
        if (data.success || data.id) {
            alert('✅ Trofeo añadido correctamente');
            loadTrophies(gameId);
        } else {
            throw new Error(data.error || 'Error desconocido');
        }
    })
    .catch(err => {
        console.error('❌ Error añadiendo trofeo:', err);
        alert('Error añadiendo trofeo: ' + err.message);
    });
}

// Función para editar trofeo inline
function editTrophyInline(trophyId) {
    console.log('✏️ Editando trofeo inline:', trophyId);
    
    // Encontrar el elemento del trofeo
    const trophyElement = document.querySelector(`[data-trophy-id="${trophyId}"]`);
    if (!trophyElement) {
        console.error('❌ No se encontró el elemento del trofeo');
        return;
    }
    
    // Obtener datos actuales del trofeo
    const gameId = new URLSearchParams(window.location.search).get('id');
    
    fetch(`/Prácticas/videojuegos/api/trophies.php?game_id=${gameId}`)
        .then(res => res.json())
        .then(trophies => {
            const trophy = trophies.find(t => t.id === trophyId);
            if (!trophy) {
                console.error('❌ No se encontró el trofeo');
                return;
            }
            
            // Convertir a formulario de edición inline
            console.log('🎯 Tipo del trofeo cargado:', trophy.tipo, 'para trofeo:', trophy.nombre_trofeo);
            trophyElement.innerHTML = `
                <div class="trophy-form-inline">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <img src="${trophy.icono_url || 'interfaz/trofeos/default.png'}" 
                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;" 
                            alt="${trophy.nombre_trofeo}">
                        <h4 style="color: #ffffff; margin: 0; font-weight: 300;">Editando: ${trophy.nombre_trofeo}</h4>
                    </div>
                    <div class="form-group">
                        <label>Nombre:</label>
                        <input type="text" id="edit-name-${trophyId}" value="${trophy.nombre_trofeo}">
                    </div>
                    <div class="form-group">
                        <label>Tipo:</label>
                        <div class="trophy-type-selector" id="trophy-type-container-${trophyId}">
                            <div class="trophy-type-option ${(trophy.tipo || '').toString().trim().toUpperCase() === 'PLATINO' ? 'selected' : ''}" data-value="PLATINO" onclick="selectTrophyType(${trophyId}, 'PLATINO')">
                                <img src="interfaz/trofeos/platino.png" alt="Platino" class="trophy-type-img">
                                <span>Platino</span>
                            </div>
                            <div class="trophy-type-option ${(trophy.tipo || '').toString().trim().toUpperCase() === 'ORO' ? 'selected' : ''}" data-value="ORO" onclick="selectTrophyType(${trophyId}, 'ORO')">
                                <img src="interfaz/trofeos/oro.png" alt="Oro" class="trophy-type-img">
                                <span>Oro</span>
                            </div>
                            <div class="trophy-type-option ${(trophy.tipo || '').toString().trim().toUpperCase() === 'PLATA' ? 'selected' : ''}" data-value="PLATA" onclick="selectTrophyType(${trophyId}, 'PLATA')">
                                <img src="interfaz/trofeos/plata.png" alt="Plata" class="trophy-type-img">
                                <span>Plata</span>
                            </div>
                            <div class="trophy-type-option ${(trophy.tipo || '').toString().trim().toUpperCase() === 'BRONCE' ? 'selected' : ''}" data-value="BRONCE" onclick="selectTrophyType(${trophyId}, 'BRONCE')">
                                <img src="interfaz/trofeos/bronce.png" alt="Bronce" class="trophy-type-img">
                                <span>Bronce</span>
                            </div>
                        </div>
                        <input type="hidden" id="edit-type-${trophyId}" value="${(trophy.tipo || '').toString().trim().toUpperCase()}">
                    </div>
                    <div class="form-group">
                        <label>Descripción:</label>
                        <input type="text" id="edit-desc-${trophyId}" value="${trophy.descripcion || ''}">
                    </div>
                    <div class="form-group">
                        <label>Icono del trofeo</label>
                        <div class="image-inline-editor">
                            <div class="image-inline-preview image-inline-preview--small">
                                <img id="edit-icon-preview-${trophyId}" src="${trophy.icono_url || 'interfaz/trofeos/default.png'}" alt="Preview del icono">
                            </div>
                            <div class="image-inline-controls">
                                <input type="text" id="edit-icon-${trophyId}" value="${trophy.icono_url || ''}" placeholder="URL de la imagen" maxlength="500">
                                <label class="image-file-btn">
                                    <input type="file" id="edit-icon-file-${trophyId}" accept="image/*">
<<<<<<< HEAD
                                    <i class="fas fa-upload"></i>
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                                    <span>Subir archivo</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Cómo conseguirlo:</label>
                        <div id="edit-instrucciones-editor-${trophyId}" style="height: 120px; background: #1a1a1a; resize: both; overflow: auto; min-height: 120px;"></div>
                        <input type="hidden" id="edit-instrucciones-${trophyId}">
                    </div>
                    <div class="form-group">
                        <label>Video URL:</label>
                        <input type="text" id="edit-video-${trophyId}" value="${trophy.video_url || ''}" onchange="updateVideoPreview(${trophyId}, this.value)">
                        <div id="video-preview-${trophyId}" style="margin-top: 0.5rem;">
                            ${trophy.video_url ? `<iframe width="280" height="157" src="${trophy.video_url.replace('watch?v=', 'embed/')}" frameborder="0" allowfullscreen style="border-radius: 8px;"></iframe>` : ''}
                        </div>
                    </div>
                    <div class="form-group" style="display: flex; gap: 2rem; align-items: center;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; margin: 0;">
                            <input type="checkbox" id="edit-conseguido-${trophyId}" ${trophy.conseguido ? 'checked' : ''}> Conseguido
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; margin: 0;">
                            <input type="checkbox" id="edit-perdible-${trophyId}" ${trophy.perdible ? 'checked' : ''}> Perdible
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; margin: 0;">
                            <input type="checkbox" id="edit-online-${trophyId}" ${trophy.online ? 'checked' : ''}> Online
                        </label>
                    </div>
                    <div style="text-align: center; margin-top: 15px; display: flex; gap: 10px; justify-content: center;">
                        <button type="button" style="display: flex; align-items: center; gap: 0.5rem; background: #252525; border: 1px solid #3a3a3a; border-radius: 8px; color: #ffffff; padding: 0.75rem 1.5rem; cursor: pointer;" onclick="saveTrophyEdit(${trophyId})">
                            <i class="fas fa-save"></i>
                        </button>
                        <button type="button" style="display: flex; align-items: center; gap: 0.5rem; background: #252525; border: 1px solid #3a3a3a; border-radius: 8px; color: #ffffff; padding: 0.75rem 1.5rem; cursor: pointer;" onclick="cancelTrophyEdit(${trophyId})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            
            // Inicializar editor Quill para instrucciones de edición de trofeo
            setTimeout(() => {
                quillEditTrophy = new Quill(`#edit-instrucciones-editor-${trophyId}`, {
                    theme: 'snow',
                    placeholder: 'Instrucciones para obtener el trofeo...',
                    modules: {
                        toolbar: {
                            container: RICH_TEXT_TOOLBAR,
                            handlers: {}
                        }
                    }
                });
                setupRichTextHandlers(quillEditTrophy);

                // Cargar contenido HTML en el editor
                if (trophy.instrucciones) {
                    quillEditTrophy.root.innerHTML = trophy.instrucciones;
                    document.getElementById(`edit-instrucciones-${trophyId}`).value = trophy.instrucciones;
                }

                quillEditTrophy.on('text-change', function() {
                    document.getElementById(`edit-instrucciones-${trophyId}`).value = quillEditTrophy.root.innerHTML;
                });
            }, 100);
            
            console.log('✅ Formulario de edición creado');
        })
        .catch(err => {
            console.error('❌ Error obteniendo datos del trofeo:', err);
            alert('Error obteniendo datos del trofeo');
        });
}

// Función para previsualizar video de YouTube
function updateVideoPreview(trophyId, url) {
    const preview = document.getElementById('video-preview-' + trophyId);
    if (url && url.includes('youtube.com')) {
        const embedUrl = url.replace('watch?v=', 'embed/');
        preview.innerHTML = '<iframe width="280" height="157" src="' + embedUrl + '" frameborder="0" allowfullscreen style="border-radius: 8px;"></iframe>';
    } else {
        preview.innerHTML = '';
    }
}

// Función para seleccionar tipo de trofeo visualmente
function selectTrophyType(trophyId, type) {
    console.log('🏆 Seleccionando tipo:', type, 'para trofeo:', trophyId);
    
    // Actualizar el input hidden
    document.getElementById(`edit-type-${trophyId}`).value = type;
    
    // Actualizar la clase visual
    const container = document.getElementById(`trophy-type-container-${trophyId}`);
    const options = container.querySelectorAll('.trophy-type-option');
    options.forEach(option => {
        option.classList.remove('selected');
        if (option.dataset.value === type) {
            option.classList.add('selected');
        }
    });
}

function saveTrophyEdit(trophyId) {
    console.log('💾 Guardando cambios del trofeo:', trophyId);

    const selectedType = document.getElementById(`edit-type-${trophyId}`);
    console.log('📋 Tipo seleccionado:', selectedType ? selectedType.value : 'No encontrado');

    // Forzar actualización del input de instrucciones antes de enviar
    const instruccionesInput = document.getElementById(`edit-instrucciones-${trophyId}`);
    const instruccionesEditor = document.querySelector(`#edit-instrucciones-editor-${trophyId} .ql-editor`);
    if (instruccionesEditor && instruccionesInput) {
        instruccionesInput.value = instruccionesEditor.innerHTML;
        console.log('📝 Instrucciones forzadas desde editor:', instruccionesInput.value);
    }

    const trophyData = {
        nombre_trofeo: document.getElementById(`edit-name-${trophyId}`).value,
        descripcion: document.getElementById(`edit-desc-${trophyId}`).value,
        instrucciones: instruccionesInput ? instruccionesInput.value : '',
        tipo: selectedType ? selectedType.value : 'BRONCE',
        icono_url: document.getElementById(`edit-icon-${trophyId}`).value,
        video_url: document.getElementById(`edit-video-${trophyId}`).value,
        conseguido: document.getElementById(`edit-conseguido-${trophyId}`).checked,
        perdible: document.getElementById(`edit-perdible-${trophyId}`).checked,
        online: document.getElementById(`edit-online-${trophyId}`).checked
    };

    console.log('📦 Datos a enviar:', trophyData);
    
    // Agregar el ID al cuerpo para que el API lo identifique
    trophyData.id = trophyId;
    
    fetch(`/Prácticas/videojuegos/api/trophies.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(trophyData)
    })
    .then(res => {
        console.log('📡 Respuesta API:', res.status);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return res.json();
    })
    .then(data => {
        console.log('✅ Respuesta del servidor:', data);
        
        // Verificar si la respuesta indica éxito
        if (data.success || (data.data && data.data.id)) {
            alert('✅ Trofeo actualizado correctamente');
            // Cerrar el formulario de edición y recargar trofeos
            const gameId = new URLSearchParams(window.location.search).get('id');
            loadTrophies(gameId);
        } else if (data.error) {
            throw new Error(data.error);
        } else {
            // Si no hay indicador claro, asumir éxito si no hay error
            alert('✅ Trofeo actualizado correctamente');
            const gameId = new URLSearchParams(window.location.search).get('id');
            loadTrophies(gameId);
        }
    })
    .catch(err => {
        console.error('❌ Error actualizando trofeo:', err);
        alert('Error actualizando trofeo: ' + err.message);
    });
}

// Función para cancelar edición
function cancelTrophyEdit(trophyId) {
    console.log('❌ Cancelando edición del trofeo:', trophyId);
    location.reload();
}

// Función para eliminar trofeo
function deleteTrophy(trophyId) {
    console.log('🗑️ Eliminando trofeo:', trophyId);
    
    if (!confirm('¿Estás seguro de eliminar este trofeo? Esta acción no se puede deshacer.')) {
        return;
    }
    
    fetch(`/Prácticas/videojuegos/api/trophies.php`, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: trophyId })
    })
    .then(res => res.json())
    .then(data => {
        console.log('✅ Trofeo eliminado:', data);
        alert('✅ Trofeo eliminado correctamente');
        // Recargar la página para mostrar los cambios
        location.reload();
    })
    .catch(err => {
        console.error('❌ Error eliminando trofeo:', err);
        alert('❌ Error eliminando trofeo: ' + err.message);
    });
}

// ==================== FUNCIONES PARA DLCs ====================

// Función para cargar DLCs
function loadDLCs(gameId) {
    console.log('📦 Cargando DLCs...');
    
    fetch(`/Prácticas/videojuegos/api/dlcs.php?game_id=${gameId}`)
        .then(res => {
            console.log('📡 Respuesta DLCs:', res.status);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            return res.json();
        })
        .then(dlcs => {
            console.log('✅ DLCs cargados:', dlcs.length, 'DLCs');
            
            if (!Array.isArray(dlcs)) {
                throw new Error('Los datos de DLCs no son válidos');
            }
            
            renderDLCs(dlcs);
        })
        .catch(err => {
            console.error('❌ Error cargando DLCs:', err);
            document.getElementById('dlcs-list').innerHTML = 
                `<div class="error-message">❌ Error cargando DLCs: ${err.message}</div>`;
        });
}

// Función para renderizar DLCs
function renderDLCs(dlcs) {
    console.log('Renderizando', dlcs.length, 'DLCs');
    
    const container = document.getElementById('dlcs-list');
    const iconsContainer = document.getElementById('dlc-icons-container');
    
    if (!dlcs || dlcs.length === 0) {
        container.innerHTML = `
            <div class="loading-message">
                <i class="fas fa-box fa-3x"></i>
                <p>No hay DLCs para este juego</p>
            </div>
        `;
        if (iconsContainer) {
            iconsContainer.innerHTML = '';
        }
        return;
    }
    
    // A1adir iconos de DLCs al contenedor del título
    if (iconsContainer) {
        iconsContainer.innerHTML = dlcs.map(dlc => `
            <button type="button" onclick="scrollToDLC('${dlc.id}')" style="background: #1f1b35; border: 2px solid #2d2847; border-radius: 8px; width: 100px; height: 100px; padding: 0; cursor: pointer; transition: all 0.3s ease; overflow: hidden; display: flex; align-items: center; justify-content: center;" title="${dlc.nombre}">
                <img src="${dlc.imagen_url || 'interfaz/trofeos/default.png'}" alt="${dlc.nombre}" style="max-width: 100%; max-height: 100%; width: auto; height: auto; object-fit: contain;">
            </button>
        `).join('');
    }
    
    const html = `
        ${dlcs.map(dlc => `
            <div class="dlc-item-edit" data-dlc-id="${dlc.id}">
                <div class="dlc-item-main">
                    <div class="dlc-item-icon">
                        <img src="${dlc.imagen_url || 'interfaz/trofeos/default.png'}" alt="${dlc.nombre}">
                    </div>
                    <div class="dlc-item-info">
                        <h4 class="dlc-item-name" style="margin: 0 0 0.3rem 0; font-size: 1.2rem;">${dlc.nombre}</h4>
                        <p style="margin: 0 0 0.3rem 0; font-size: 0.85rem; color: #999;">
                            ${dlc.precio ? `Precio: ${dlc.precio}€` : ''} 
                            ${dlc.fecha_lanzamiento ? ` | Lanzamiento: ${dlc.fecha_lanzamiento}` : ''}
                        </p>
                        <p style="margin: 0 0 0.3rem 0; font-size: 0.8rem; color: #888;">${dlc.descripcion || 'Sin descripción'}</p>
                        <div class="dlc-trophies-section" id="dlc-trophies-${dlc.id}">
                            <div class="dlc-trophies-header">
                                <span style="font-size: 0.85rem; color: #aaa;">Trofeos del DLC:</span>
                                <button type="button" class="action-btn add" onclick="showAddDLCTrophyForm(${dlc.id})">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="dlc-trophies-list" id="dlc-trophies-list-${dlc.id}">
                                <div class="loading-message" style="font-size: 0.8rem;">
                                    <i class="fas fa-spinner fa-spin"></i> Cargando trofeos...
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="dlc-item-actions">
                        <button type="button" class="action-btn edit" onclick="editDLCInline(${dlc.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="action-btn delete" onclick="deleteDLC(${dlc.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `).join('')}
    `;
    
    container.innerHTML = html;
    console.log('✅ DLCs renderizados correctamente');
    
    // Cargar trofeos de cada DLC
    dlcs.forEach(dlc => loadDLCTrophies(dlc.id));
}

// Función para mostrar formulario inline de añadir nuevo DLC
function showAddDLCForm() {
    console.log('➕ Mostrando formulario para añadir DLC');
    
    const container = document.getElementById('dlcs-list');
    const gameId = new URLSearchParams(window.location.search).get('id');
    
    container.innerHTML = `
        <div class="dlc-form-inline" style="background: #231f36; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <i class="fas fa-plus-circle" style="color: #ffffff; font-size: 1.5rem;"></i>
                <h4 style="color: #ffffff; margin: 0; font-weight: 300;">Nuevo DLC</h4>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Nombre del DLC:</label>
                    <input type="text" id="new-dlc-name" placeholder="Ej: The Frozen Wilds">
                </div>
                <div class="form-group">
                    <label>Fecha de lanzamiento:</label>
                    <input type="date" id="new-dlc-date">
                </div>
            </div>
            <div class="form-group">
                <label>Descripción:</label>
                <div id="new-dlc-desc-editor" style="height: 150px; background: #1a1a1a; resize: both; overflow: auto; min-height: 150px;"></div>
                <input type="hidden" id="new-dlc-desc">
            </div>
            <div class="form-group">
                <label>Icono del DLC</label>
                <div class="image-inline-editor">
                    <div class="image-inline-preview image-inline-preview--small">
                        <img id="new-dlc-preview" src="interfaz/trofeos/default.png" alt="Preview del DLC">
                    </div>
                    <div class="image-inline-controls">
                        <input type="text" id="new-dlc-image" placeholder="URL del icono" maxlength="500" oninput="document.getElementById('new-dlc-preview').src = this.value || 'interfaz/trofeos/default.png'">
                        <label class="image-file-btn">
                            <input type="file" id="new-dlc-file" accept="image/*" onchange="const file=this.files[0]; if(file){const reader=new FileReader(); reader.onload=function(e){document.getElementById('new-dlc-image').value=e.target.result; document.getElementById('new-dlc-preview').src=e.target.result;}; reader.readAsDataURL(file);}">
<<<<<<< HEAD
                            <i class="fas fa-upload"></i>
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                            <span>Subir archivo</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Banner del DLC</label>
                <div class="image-inline-editor">
                    <div class="image-inline-preview image-inline-preview--small">
                        <img id="new-dlc-banner-preview" src="interfaz/trofeos/default.png" alt="Preview del banner del DLC">
                    </div>
                    <div class="image-inline-controls">
                        <input type="text" id="new-dlc-banner-url" placeholder="URL del banner" maxlength="500" oninput="document.getElementById('new-dlc-banner-preview').src = this.value || 'interfaz/trofeos/default.png'">
                        <label class="image-file-btn">
                            <input type="file" id="new-dlc-banner-file" accept="image/*" onchange="const file=this.files[0]; if(file){const reader=new FileReader(); reader.onload=function(e){document.getElementById('new-dlc-banner-url').value=e.target.result; document.getElementById('new-dlc-banner-preview').src=e.target.result;}; reader.readAsDataURL(file);}">
<<<<<<< HEAD
                            <i class="fas fa-upload"></i>
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                            <span>Subir archivo</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Dificultad del platino:</label>
                    <select id="new-dlc-difficulty">
                        <option value="1 sobre 10">1 sobre 10</option>
                        <option value="2 sobre 10">2 sobre 10</option>
                        <option value="3 sobre 10">3 sobre 10</option>
                        <option value="4 sobre 10">4 sobre 10</option>
                        <option value="5 sobre 10">5 sobre 10</option>
                        <option value="6 sobre 10">6 sobre 10</option>
                        <option value="7 sobre 10">7 sobre 10</option>
                        <option value="8 sobre 10">8 sobre 10</option>
                        <option value="9 sobre 10">9 sobre 10</option>
                        <option value="10 sobre 10">10 sobre 10</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Duración estimada:</label>
                    <input type="text" id="new-dlc-duration" placeholder="Ej: 20 horas">
                </div>
            </div>
            
            <div class="form-group">
                <label>Trofeos Offline</label>
                <div class="form-row">
                    <div class="form-group">
                        <label>Oro</label>
                        <input type="number" id="new-dlc-offline-oro" min="0" max="99" placeholder="0">
                    </div>
                    <div class="form-group">
                        <label>Plata</label>
                        <input type="number" id="new-dlc-offline-plata" min="0" max="99" placeholder="0">
                    </div>
                    <div class="form-group">
                        <label>Bronce</label>
                        <input type="number" id="new-dlc-offline-bronce" min="0" max="99" placeholder="0">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Trofeos Online</label>
                <div class="form-row">
                    <div class="form-group">
                        <label>Oro</label>
                        <input type="number" id="new-dlc-online-oro" min="0" max="99" placeholder="0">
                    </div>
                    <div class="form-group">
                        <label>Plata</label>
                        <input type="number" id="new-dlc-online-plata" min="0" max="99" placeholder="0">
                    </div>
                    <div class="form-group">
                        <label>Bronce</label>
                        <input type="number" id="new-dlc-online-bronce" min="0" max="99" placeholder="0">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Trofeos Perdibles</label>
                <div id="new-dlc-perdibles-editor" style="height: 140px; background: #1a1a1a; resize: both; overflow: auto; min-height: 140px;"></div>
                <input type="hidden" id="new-dlc-perdibles">
            </div>
            
            <div class="form-group">
                <label>Galería de archivos</label>
                <div class="image-inline-editor">
                    <div class="image-inline-controls">
                        <input type="file" id="new-dlc-gallery" accept="image/*" multiple onchange="const files=this.files; const galleryContainer=document.getElementById('new-dlc-gallery-preview'); galleryContainer.innerHTML=''; for(let i=0; i<files.length; i++){const reader=new FileReader(); reader.onload=function(e){const img=document.createElement('img'); img.src=e.target.result; img.style.width='80px'; img.style.height='80px'; img.style.objectFit='cover'; img.style.margin='5px'; img.style.borderRadius='8px'; galleryContainer.appendChild(img);}; reader.readAsDataURL(files[i]);}">
                        <label class="image-file-btn">
                            <span>Añadir archivos</span>
                        </label>
                    </div>
                    <div id="new-dlc-gallery-preview" style="display: flex; flex-wrap: wrap; gap: 5px; margin-top: 10px;"></div>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 15px; display: flex; gap: 10px; justify-content: center;">
                <button type="button" class="add-trophy-btn" onclick="saveNewDLC()">
                    <i class="fas fa-save"></i>
                </button>
                <button class="cancel-btn" onclick="cancelAddDLC()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
    
    // Inicializar editor Quill para descripción de nuevo DLC
    setTimeout(() => {
        const quillNewDLCDesc = new Quill('#new-dlc-desc-editor', {
            theme: 'snow',
            placeholder: 'Descripción del DLC...',
            modules: {
                toolbar: {
                    container: RICH_TEXT_TOOLBAR,
                    handlers: {}
                }
            }
        });
        setupRichTextHandlers(quillNewDLCDesc);

        quillNewDLCDesc.on('text-change', function() {
            document.getElementById('new-dlc-desc').value = quillNewDLCDesc.root.innerHTML;
        });

        const quillNewDLCPerdibles = new Quill('#new-dlc-perdibles-editor', {
            theme: 'snow',
            placeholder: 'Descripción de trofeos perdibles',
            modules: {
                toolbar: {
                    container: RICH_TEXT_TOOLBAR,
                    handlers: {}
                }
            }
        });
        setupRichTextHandlers(quillNewDLCPerdibles);
        quillNewDLCPerdibles.on('text-change', function() {
            document.getElementById('new-dlc-perdibles').value = quillNewDLCPerdibles.root.innerHTML;
        });
    }, 100);
}

// Función para cancelar añadir DLC
function cancelAddDLC() {
    const gameId = new URLSearchParams(window.location.search).get('id');
    loadDLCs(gameId);
}

// Función para guardar nuevo DLC
function saveNewDLC() {
    console.log('💾 Guardando nuevo DLC...');

    const gameId = new URLSearchParams(window.location.search).get('id');
    console.log('Game ID:', gameId);

    // Obtener valores con comprobación de null
    const nombreEl = document.getElementById('new-dlc-name');
    const fechaEl = document.getElementById('new-dlc-date');
    const descEl = document.getElementById('new-dlc-desc');
    const imagenEl = document.getElementById('new-dlc-image');
    const bannerEl = document.getElementById('new-dlc-banner-url');
    const diffEl = document.getElementById('new-dlc-difficulty');
    const duracionEl = document.getElementById('new-dlc-duration');
    const offlineOroEl = document.getElementById('new-dlc-offline-oro');
    const offlinePlataEl = document.getElementById('new-dlc-offline-plata');
    const offlineBronceEl = document.getElementById('new-dlc-offline-bronce');
    const onlineOroEl = document.getElementById('new-dlc-online-oro');
    const onlinePlataEl = document.getElementById('new-dlc-online-plata');
    const onlineBronceEl = document.getElementById('new-dlc-online-bronce');
    const perdiblesEl = document.getElementById('new-dlc-perdibles');

    console.log('Elementos encontrados:', {
        nombre: !!nombreEl,
        fecha: !!fechaEl,
        desc: !!descEl
    });

    const dlcData = {
        videojuego_id: parseInt(gameId),
        nombre: nombreEl ? nombreEl.value : '',
        fecha_lanzamiento: fechaEl ? fechaEl.value : '',
        descripcion: descEl ? descEl.value : '',
        imagen_url: imagenEl ? imagenEl.value : '',
        banner_url: bannerEl ? bannerEl.value : '',
        dificultad_platino: diffEl ? diffEl.value : '',
        duracion_estimada: duracionEl ? duracionEl.value : '',
        trofeos_offline_oro: offlineOroEl ? offlineOroEl.value : '',
        trofeos_offline_plata: offlinePlataEl ? offlinePlataEl.value : '',
        trofeos_offline_bronce: offlineBronceEl ? offlineBronceEl.value : '',
        trofeos_online_oro: onlineOroEl ? onlineOroEl.value : '',
        trofeos_online_plata: onlinePlataEl ? onlinePlataEl.value : '',
        trofeos_online_bronce: onlineBronceEl ? onlineBronceEl.value : '',
        trofeos_perdibles: perdiblesEl ? perdiblesEl.value : ''
    };

    if (!dlcData.nombre) {
        alert('❌ Debes ingresar el nombre del DLC');
        return;
    }

    fetch(`/Prácticas/videojuegos/api/dlcs.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(dlcData)
    })
    .then(res => res.json())
    .then(data => {
        console.log('✅ Nuevo DLC añadido:', data);
        if (data.success || data.id) {
            alert('✅ DLC añadido correctamente');
            loadDLCs(gameId);
        } else {
            throw new Error(data.error || 'Error desconocido');
        }
    })
    .catch(err => {
        console.error('❌ Error añadiendo DLC:', err);
        alert('Error añadiendo DLC: ' + err.message);
    });
}

// Función para editar DLC inline
function editDLCInline(dlcId) {
    console.log('Editando DLC inline:', dlcId);
    
    const dlcElement = document.querySelector(`[data-dlc-id="${dlcId}"]`);
    if (!dlcElement) {
        console.error('❌ No se encontró el elemento del DLC');
        return;
    }
    
    const gameId = new URLSearchParams(window.location.search).get('id');
    
    fetch(`/Prácticas/videojuegos/api/dlcs.php?game_id=${gameId}`)
        .then(res => res.json())
        .then(dlcs => {
            const dlc = dlcs.find(d => d.id === dlcId);
            if (!dlc) {
                console.error('❌ No se encontró el DLC');
                return;
            }
            
            dlcElement.innerHTML = `
                <div class="dlc-form-inline">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <img src="${dlc.imagen_url || 'interfaz/trofeos/default.png'}"
                            style="width: 50px !important; height: 50px !important; max-width: 50px !important; max-height: 50px !important; object-fit: cover !important; border-radius: 8px !important;"
                            alt="${dlc.nombre}">
                        <h4 style="color: #ffffff; margin: 0; font-weight: 300;">Editando: ${dlc.nombre}</h4>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nombre:</label>
                            <input type="text" id="edit-dlc-name-${dlcId}" value="${dlc.nombre}">
                        </div>
                        <div class="form-group">
                            <label>Fecha de lanzamiento:</label>
                            <input type="date" id="edit-dlc-date-${dlcId}" value="${dlc.fecha_lanzamiento || ''}">
                        </div>
                    </div>
                    <div class="form-group">
<<<<<<< HEAD
=======
                        <label>Descripción:</label>
                        <div id="edit-dlc-desc-editor-${dlcId}" style="height: 150px; background: #1a1a1a; resize: both; overflow: auto; min-height: 150px;"></div>
                        <input type="hidden" id="edit-dlc-desc-${dlcId}">
                    </div>
                    <div class="form-group">
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                        <label>Icono del DLC</label>
                        <div class="image-inline-editor">
                            <div class="image-inline-preview image-inline-preview--small">
                                <img id="edit-dlc-preview-${dlcId}" src="${dlc.imagen_url || 'interfaz/trofeos/default.png'}" alt="Preview del DLC">
                            </div>
                            <div class="image-inline-controls">
                                <input type="text" id="edit-dlc-image-${dlcId}" value="${dlc.imagen_url || ''}" maxlength="500" oninput="document.getElementById('edit-dlc-preview-${dlcId}').src = this.value || 'interfaz/trofeos/default.png'">
                                <label class="image-file-btn">
                                    <input type="file" id="edit-dlc-file-${dlcId}" accept="image/*" onchange="const file=this.files[0]; if(file){const reader=new FileReader(); reader.onload=function(e){document.getElementById('edit-dlc-image-${dlcId}').value=e.target.result; document.getElementById('edit-dlc-preview-${dlcId}').src=e.target.result;}; reader.readAsDataURL(file);}">
<<<<<<< HEAD
                                    <i class="fas fa-upload"></i>
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                                    <span>Subir archivo</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Banner del DLC</label>
                        <div class="image-inline-editor">
                            <div class="image-inline-preview image-inline-preview--small">
                                <img id="edit-dlc-banner-preview-${dlcId}" src="${dlc.banner_url || dlc.imagen_url || 'interfaz/trofeos/default.png'}" alt="Preview del banner del DLC">
                            </div>
                            <div class="image-inline-controls">
                                <input type="text" id="edit-dlc-banner-url-${dlcId}" value="${dlc.banner_url || ''}" maxlength="500" oninput="document.getElementById('edit-dlc-banner-preview-${dlcId}').src = this.value || 'interfaz/trofeos/default.png'">
                                <label class="image-file-btn">
                                    <input type="file" id="edit-dlc-banner-file-${dlcId}" accept="image/*" onchange="const file=this.files[0]; if(file){const reader=new FileReader(); reader.onload=function(e){document.getElementById('edit-dlc-banner-url-${dlcId}').value=e.target.result; document.getElementById('edit-dlc-banner-preview-${dlcId}').src=e.target.result;}; reader.readAsDataURL(file);}">
<<<<<<< HEAD
                                    <i class="fas fa-upload"></i>
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                                    <span>Subir archivo</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Dificultad del platino:</label>
                            <select id="edit-dlc-difficulty-${dlcId}">
                                <option value="1 sobre 10" ${dlc.dificultad_platino === '1 sobre 10' ? 'selected' : ''}>1 sobre 10</option>
                                <option value="2 sobre 10" ${dlc.dificultad_platino === '2 sobre 10' ? 'selected' : ''}>2 sobre 10</option>
                                <option value="3 sobre 10" ${dlc.dificultad_platino === '3 sobre 10' ? 'selected' : ''}>3 sobre 10</option>
                                <option value="4 sobre 10" ${dlc.dificultad_platino === '4 sobre 10' ? 'selected' : ''}>4 sobre 10</option>
                                <option value="5 sobre 10" ${dlc.dificultad_platino === '5 sobre 10' ? 'selected' : ''}>5 sobre 10</option>
                                <option value="6 sobre 10" ${dlc.dificultad_platino === '6 sobre 10' ? 'selected' : ''}>6 sobre 10</option>
                                <option value="7 sobre 10" ${dlc.dificultad_platino === '7 sobre 10' ? 'selected' : ''}>7 sobre 10</option>
                                <option value="8 sobre 10" ${dlc.dificultad_platino === '8 sobre 10' ? 'selected' : ''}>8 sobre 10</option>
                                <option value="9 sobre 10" ${dlc.dificultad_platino === '9 sobre 10' ? 'selected' : ''}>9 sobre 10</option>
                                <option value="10 sobre 10" ${dlc.dificultad_platino === '10 sobre 10' ? 'selected' : ''}>10 sobre 10</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Duración estimada:</label>
                            <input type="text" id="edit-dlc-duration-${dlcId}" value="${dlc.duracion_estimada || ''}">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Trofeos Offline</label>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Oro</label>
                                <input type="number" id="edit-dlc-offline-oro-${dlcId}" value="${dlc.trofeos_offline_oro || 0}" min="0" max="99">
                            </div>
                            <div class="form-group">
                                <label>Plata</label>
                                <input type="number" id="edit-dlc-offline-plata-${dlcId}" value="${dlc.trofeos_offline_plata || 0}" min="0" max="99">
                            </div>
                            <div class="form-group">
                                <label>Bronce</label>
                                <input type="number" id="edit-dlc-offline-bronce-${dlcId}" value="${dlc.trofeos_offline_bronce || 0}" min="0" max="99">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Trofeos Online</label>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Oro</label>
                                <input type="number" id="edit-dlc-online-oro-${dlcId}" value="${dlc.trofeos_online_oro || 0}" min="0" max="99">
                            </div>
                            <div class="form-group">
                                <label>Plata</label>
                                <input type="number" id="edit-dlc-online-plata-${dlcId}" value="${dlc.trofeos_online_plata || 0}" min="0" max="99">
                            </div>
                            <div class="form-group">
                                <label>Bronce</label>
                                <input type="number" id="edit-dlc-online-bronce-${dlcId}" value="${dlc.trofeos_online_bronce || 0}" min="0" max="99">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
<<<<<<< HEAD
                        <label>Descripción:</label>
                        <div id="edit-dlc-desc-editor-${dlcId}" style="height: 150px; background: #1a1a1a; resize: both; overflow: auto; min-height: 150px;"></div>
                        <input type="hidden" id="edit-dlc-desc-${dlcId}">
                    </div>
                    
                    <div class="form-group">
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                        <label>Trofeos Perdibles</label>
                        <div id="edit-dlc-perdibles-editor-${dlcId}" style="height: 140px; background: #1a1a1a; resize: both; overflow: auto; min-height: 140px;"></div>
                        <input type="hidden" id="edit-dlc-perdibles-${dlcId}">
                    </div>
                    
                    <div class="form-group">
                        <label>Galería multimedia</label>
                        <div class="form-row">
                            <div class="form-group" style="flex: 2; min-width: 240px;">
                                <label for="dlc-media-url-${dlcId}">URL</label>
                                <input type="text" id="dlc-media-url-${dlcId}" placeholder="URL de imagen o vídeo">
                            </div>
                            <div class="form-group" style="flex: 1; min-width: 180px;">
                                <label for="dlc-media-type-${dlcId}">Tipo</label>
                                <select id="dlc-media-type-${dlcId}">
                                    <option value="image">Imagen</option>
                                    <option value="video">Vídeo</option>
                                </select>
                            </div>
<<<<<<< HEAD
                            <div class="form-group" style="align-self: end; display: flex; gap: 0.5rem;">
                                <button type="button" class="add-trophy-btn" onclick="addDLCMediaItem(${dlcId})">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <input type="file" id="dlc-media-file-${dlcId}" accept="image/*,video/*" style="display: none;" onchange="uploadDLCMediaFile(${dlcId})">
                                <button type="button" class="add-trophy-btn" onclick="document.getElementById('dlc-media-file-${dlcId}').click()">
                                    <i class="fas fa-upload"></i>
                                </button>
                                <button type="button" id="save-dlc-media-btn-${dlcId}" class="save-btn" onclick="saveDLCMedia(${dlcId})">
                                    <i class="fas fa-save"></i>
                                </button>
=======
                            <div class="form-group" style="align-self: end;">
                                <button type="button" class="add-trophy-btn" onclick="addDLCMediaItem(${dlcId})">
                                    <i class="fas fa-plus"></i>
                                </button>
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                            </div>
                        </div>
                        <div id="dlc-media-gallery-editor-${dlcId}" class="media-gallery-editor"></div>
                        <input type="hidden" id="dlc-media-items-json-${dlcId}" name="dlc_media_items_json_${dlcId}" value="[]">
<<<<<<< HEAD
=======
                        <button type="button" id="save-dlc-media-btn-${dlcId}" class="save-btn" style="margin-top: 0.75rem;" onclick="saveDLCMedia(${dlcId})">
                            <i class="fas fa-save"></i>
                        </button>
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                    </div>
                    
                    <div style="text-align: center; margin-top: 15px; display: flex; gap: 10px; justify-content: center;">
                        <button type="button" style="display: flex; align-items: center; gap: 0.5rem; background: #252525; border: 1px solid #3a3a3a; border-radius: 8px; color: #ffffff; padding: 0.75rem 1.5rem; cursor: pointer;" onclick="saveDLCEdit(${dlcId})">
                            <i class="fas fa-save"></i>
                        </button>
                        <button type="button" style="display: flex; align-items: center; gap: 0.5rem; background: #252525; border: 1px solid #3a3a3a; border-radius: 8px; color: #ffffff; padding: 0.75rem 1.5rem; cursor: pointer;" onclick="cancelDLCEdit(${dlcId})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            
            // Inicializar editor Quill para descripción de edición de DLC
            setTimeout(() => {
                const quillEditDLCDesc = new Quill(`#edit-dlc-desc-editor-${dlcId}`, {
                    theme: 'snow',
                    placeholder: 'Descripción del DLC...',
                    modules: {
                        toolbar: {
                            container: RICH_TEXT_TOOLBAR,
                            handlers: {}
                        }
                    }
                });
                setupRichTextHandlers(quillEditDLCDesc);

                // Cargar contenido HTML en el editor
                if (dlc.descripcion) {
                    quillEditDLCDesc.root.innerHTML = dlc.descripcion;
                    document.getElementById(`edit-dlc-desc-${dlcId}`).value = dlc.descripcion;
                }

                quillEditDLCDesc.on('text-change', function() {
                    document.getElementById(`edit-dlc-desc-${dlcId}`).value = quillEditDLCDesc.root.innerHTML;
                });

                const quillEditDLCPerdibles = new Quill(`#edit-dlc-perdibles-editor-${dlcId}`, {
                    theme: 'snow',
                    placeholder: 'Descripción de trofeos perdibles',
                    modules: {
                        toolbar: {
                            container: RICH_TEXT_TOOLBAR,
                            handlers: {}
                        }
                    }
                });
                setupRichTextHandlers(quillEditDLCPerdibles);

                if (dlc.trofeos_perdibles) {
                    quillEditDLCPerdibles.root.innerHTML = dlc.trofeos_perdibles;
                    document.getElementById(`edit-dlc-perdibles-${dlcId}`).value = dlc.trofeos_perdibles;
                }

                quillEditDLCPerdibles.on('text-change', function() {
                    document.getElementById(`edit-dlc-perdibles-${dlcId}`).value = quillEditDLCPerdibles.root.innerHTML;
                });
            }, 100);
            
            // Cargar elementos multimedia del DLC
            loadDLCMediaItems(dlcId);
            
            console.log('✅ Formulario de edición DLC creado');
        })
        .catch(err => {
            console.error('❌ Error obteniendo datos del DLC:', err);
            alert('Error obteniendo datos del DLC');
        });
}

// Función para guardar edición de DLC
function saveDLCEdit(dlcId) {
    console.log('💾 Guardando cambios del DLC:', dlcId);
    
    const dlcData = {
        nombre: document.getElementById(`edit-dlc-name-${dlcId}`).value,
        fecha_lanzamiento: document.getElementById(`edit-dlc-date-${dlcId}`).value,
        descripcion: document.getElementById(`edit-dlc-desc-${dlcId}`).value,
        imagen_url: document.getElementById(`edit-dlc-image-${dlcId}`).value,
        banner_url: document.getElementById(`edit-dlc-banner-url-${dlcId}`).value,
        dificultad_platino: document.getElementById(`edit-dlc-difficulty-${dlcId}`).value,
        duracion_estimada: document.getElementById(`edit-dlc-duration-${dlcId}`).value,
        trofeos_offline_oro: document.getElementById(`edit-dlc-offline-oro-${dlcId}`).value,
        trofeos_offline_plata: document.getElementById(`edit-dlc-offline-plata-${dlcId}`).value,
        trofeos_offline_bronce: document.getElementById(`edit-dlc-offline-bronce-${dlcId}`).value,
        trofeos_online_oro: document.getElementById(`edit-dlc-online-oro-${dlcId}`).value,
        trofeos_online_plata: document.getElementById(`edit-dlc-online-plata-${dlcId}`).value,
        trofeos_online_bronce: document.getElementById(`edit-dlc-online-bronce-${dlcId}`).value,
        trofeos_perdibles: document.getElementById(`edit-dlc-perdibles-${dlcId}`).value
    };
    
    dlcData.id = dlcId;
    
    fetch(`/Prácticas/videojuegos/api/dlcs.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(dlcData)
    })
    .then(res => res.json())
    .then(data => {
        console.log('✅ DLC actualizado:', data);
        alert('✅ DLC actualizado correctamente');
        const gameId = new URLSearchParams(window.location.search).get('id');
        loadDLCs(gameId);
    })
    .catch(err => {
        console.error('❌ Error actualizando DLC:', err);
        alert('Error actualizando DLC: ' + err.message);
    });
}

// Función para cancelar edición de DLC
function cancelDLCEdit(dlcId) {
    console.log('❌ Cancelando edición del DLC:', dlcId);
    const gameId = new URLSearchParams(window.location.search).get('id');
    loadDLCs(gameId);
}

// Función para añadir media a DLC por URL
function addDLCMediaByUrl(dlcId) {
    const urlInput = document.getElementById(`edit-dlc-gallery-url-${dlcId}`);
    const url = urlInput.value.trim();
    
    if (!url) {
        alert('Por favor, introduce una URL válida');
        return;
    }
    
    const galleryContainer = document.getElementById(`edit-dlc-gallery-preview-${dlcId}`);
    
    // Determinar si es imagen o vídeo
    const isVideo = url.match(/\.(mp4|webm|ogg|mov|avi|mkv)$/i) || url.includes('youtube.com') || url.includes('youtu.be');
    
    if (isVideo) {
        // Crear elemento de vídeo
        const video = document.createElement('video');
        video.src = url;
        video.style.width = '80px';
        video.style.height = '80px';
        video.style.objectFit = 'cover';
        video.style.margin = '5px';
        video.style.borderRadius = '8px';
        video.controls = true;
        galleryContainer.appendChild(video);
    } else {
        // Crear elemento de imagen
        const img = document.createElement('img');
        img.src = url;
        img.style.width = '80px';
        img.style.height = '80px';
        img.style.objectFit = 'cover';
        img.style.margin = '5px';
        img.style.borderRadius = '8px';
        galleryContainer.appendChild(img);
    }
    
    // Limpiar el input
    urlInput.value = '';
}

// ==================== FUNCIONES PARA GALERÍA MULTIMEDIA DE DLCs ====================

// Almacenamiento para elementos multimedia de DLCs
let dlcMediaItems = {};

// Función para añadir elemento multimedia a DLC
function addDLCMediaItem(dlcId) {
    console.log('addDLCMediaItem called for dlcId:', dlcId);
    const url = document.getElementById(`dlc-media-url-${dlcId}`).value.trim();
    const type = document.getElementById(`dlc-media-type-${dlcId}`).value;
<<<<<<< HEAD

    console.log('URL:', url);
    console.log('Type:', type);

    if (!dlcMediaItems[dlcId]) {
        dlcMediaItems[dlcId] = [];
    }

=======
    
    console.log('URL:', url);
    console.log('Type:', type);
    
    if (!dlcMediaItems[dlcId]) {
        dlcMediaItems[dlcId] = [];
    }
    
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
    if (!url) {
        alert('Introduce una URL para añadir un elemento a la galería.');
        return;
    }

    dlcMediaItems[dlcId].push({
        tipo: type,
        url,
        orden: dlcMediaItems[dlcId].length
    });

    document.getElementById(`dlc-media-url-${dlcId}`).value = '';
    document.getElementById(`dlc-media-type-${dlcId}`).value = 'image';
    document.getElementById(`dlc-media-items-json-${dlcId}`).value = JSON.stringify(dlcMediaItems[dlcId]);
    renderDLCMediaGalleryEditor(dlcId);
}

<<<<<<< HEAD
// Función para subir archivo multimedia de DLC
async function uploadDLCMediaFile(dlcId) {
    const fileInput = document.getElementById(`dlc-media-file-${dlcId}`);
    const file = fileInput.files[0];

    if (!file) {
        alert('Selecciona un archivo para subir.');
        return;
    }

    // Determinar el tipo según el archivo
    const type = file.type.startsWith('video/') ? 'video' : 'image';

    const formData = new FormData();
    formData.append('file', file);
    formData.append('dlc_id', dlcId);
    formData.append('tipo', type);

    try {
        const response = await fetch('/Prácticas/videojuegos/api/game_media.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (response.ok) {
            if (!dlcMediaItems[dlcId]) {
                dlcMediaItems[dlcId] = [];
            }

            dlcMediaItems[dlcId].push({
                tipo: type,
                url: result.url,
                orden: dlcMediaItems[dlcId].length
            });

            document.getElementById(`dlc-media-items-json-${dlcId}`).value = JSON.stringify(dlcMediaItems[dlcId]);
            renderDLCMediaGalleryEditor(dlcId);
            fileInput.value = '';
            alert('Archivo subido correctamente.');
        } else {
            alert('Error al subir el archivo: ' + (result.error || 'Error desconocido'));
        }
    } catch (error) {
        console.error('Error subiendo archivo:', error);
        alert('Error al subir el archivo.');
    }
}

=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
// Función para eliminar elemento multimedia de DLC
function removeDLCMediaItem(dlcId, index) {
    if (!dlcMediaItems[dlcId]) return;
    
    dlcMediaItems[dlcId].splice(index, 1);
    dlcMediaItems[dlcId] = dlcMediaItems[dlcId].map((item, idx) => ({ ...item, orden: idx }));
    document.getElementById(`dlc-media-items-json-${dlcId}`).value = JSON.stringify(dlcMediaItems[dlcId]);
    renderDLCMediaGalleryEditor(dlcId);
}

// Función para renderizar la galería multimedia de DLC
function renderDLCMediaGalleryEditor(dlcId) {
    const container = document.getElementById(`dlc-media-gallery-editor-${dlcId}`);
    if (!container) return;

    if (!dlcMediaItems[dlcId] || !dlcMediaItems[dlcId].length) {
        container.innerHTML = '<p style="color:#9ca3af; margin:0;">No hay elementos en la galería todavía.</p>';
        return;
    }

    container.innerHTML = `
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem;">
            ${dlcMediaItems[dlcId].map((item, index) => `
                <div style="position: relative; background:#1f1b35; border-radius:10px; overflow:hidden; aspect-ratio: 16/9;">
                    ${item.tipo === 'video'
                        ? `<div style="width:100%; height:100%; background:#0f0d1d; display:flex; align-items:center; justify-content:center;">
                            <span style="color:#fff; font-size:0.8rem;">Vídeo</span>
<<<<<<< HEAD
                            </div>`
                        : `<img src="${item.url}" alt="Media" style="width:100% !important; height:100% !important; object-fit:cover !important; max-width:none !important; display:block !important;">`
=======
                           </div>`
                        : `<img src="${item.url}" alt="Media" style="width:100%; height:100%; object-fit:cover;">`
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                    }
                    <button type="button" onclick="removeDLCMediaItem(${dlcId}, ${index})" style="position: absolute; top: 5px; right: 5px; background: rgba(0,0,0,0.7); color: white; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `).join('')}
        </div>
    `;
}

// Función para cargar elementos multimedia de DLC
async function loadDLCMediaItems(dlcId) {
    console.log('loadDLCMediaItems called for dlcId:', dlcId);
    try {
        const response = await fetch(`/Prácticas/videojuegos/api/game_media.php?dlc_id=${dlcId}`);
        const media = await response.json();
        dlcMediaItems[dlcId] = media;
        document.getElementById(`dlc-media-items-json-${dlcId}`).value = JSON.stringify(media);
        renderDLCMediaGalleryEditor(dlcId);
    } catch (error) {
        console.error('Error cargando media del DLC:', error);
        dlcMediaItems[dlcId] = [];
        renderDLCMediaGalleryEditor(dlcId);
    }
}

// Función para guardar galería multimedia de DLC
async function saveDLCMedia(dlcId) {
    console.log('saveDLCMedia called for dlcId:', dlcId);
    
    if (!dlcMediaItems[dlcId]) {
        dlcMediaItems[dlcId] = [];
    }
    
    try {
        // Primero eliminar todos los elementos multimedia existentes del DLC
        const existingMedia = await fetch(`/Prácticas/videojuegos/api/game_media.php?dlc_id=${dlcId}`);
        const existingItems = await existingMedia.json();
        
        for (const item of existingItems) {
            await fetch(`/Prácticas/videojuegos/api/game_media.php?id=${item.id}&table=dlc`, {
                method: 'DELETE'
            });
        }
        
        // Luego añadir los nuevos elementos
        for (const item of dlcMediaItems[dlcId]) {
            await fetch('/Prácticas/videojuegos/api/game_media.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    dlc_id: dlcId,
                    tipo: item.tipo,
                    url: item.url,
                    orden: item.orden
                })
            });
        }
        
        alert('Galería multimedia del DLC guardada correctamente');
    } catch (error) {
        console.error('Error guardando media del DLC:', error);
        alert('Error guardando la galería multimedia del DLC');
    }
}

// Función para eliminar DLC
function deleteDLC(dlcId) {
    console.log('🗑️ Eliminando DLC:', dlcId);
    
    if (!confirm('¿Estás seguro de eliminar este DLC y todos sus trofeos? Esta acción no se puede deshacer.')) {
        return;
    }
    
    fetch(`/Prácticas/videojuegos/api/dlcs.php`, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: dlcId })
    })
    .then(res => res.json())
    .then(data => {
        console.log('✅ DLC eliminado:', data);
        alert('✅ DLC eliminado correctamente');
        const gameId = new URLSearchParams(window.location.search).get('id');
        loadDLCs(gameId);
    })
    .catch(err => {
        console.error('❌ Error eliminando DLC:', err);
        alert('❌ Error eliminando DLC: ' + err.message);
    });
}

// ==================== FUNCIONES PARA TROFEOS DE DLC ====================

// Función para cargar trofeos de un DLC específico
function loadDLCTrophies(dlcId) {
    console.log('🏆 Cargando trofeos del DLC:', dlcId);
    
    fetch(`/Prácticas/videojuegos/api/dlc_trophies.php?dlc_id=${dlcId}`)
        .then(res => {
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            return res.json();
        })
        .then(trophies => {
            console.log('✅ Trofeos del DLC cargados:', trophies.length, 'trofeos');
            renderDLCTrophies(dlcId, trophies);
        })
        .catch(err => {
            console.error('❌ Error cargando trofeos del DLC:', err);
            document.getElementById(`dlc-trophies-list-${dlcId}`).innerHTML = 
                `<div class="error-message" style="font-size: 0.8rem;">❌ Error: ${err.message}</div>`;
        });
}

// Función para renderizar trofeos de un DLC
function renderDLCTrophies(dlcId, trophies) {
    const container = document.getElementById(`dlc-trophies-list-${dlcId}`);
    
    if (!trophies || trophies.length === 0) {
        container.innerHTML = `
            <div style="font-size: 0.8rem; color: #666; padding: 0.5rem;">
                No hay trofeos en este DLC
            </div>
        `;
        return;
    }
    
    const html = `
        ${trophies.map(trophy => `
            <div class="trophy-item-edit" data-trophy-id="${trophy.id}">
                <div class="trophy-item-icon">
                    <img src="${trophy.icono_url || 'interfaz/trofeos/default.png'}" style="width: 50px !important; height: 50px !important; object-fit: cover !important; border-radius: 8px !important;" alt="${trophy.nombre_trofeo}">
                </div>
                <div class="trophy-item-info" style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.3rem;">
                        <h4 class="trophy-item-name" style="margin: 0; font-weight: bold;">${trophy.nombre_trofeo}</h4>
                        <span class="trophy-item-type" style="background: ${getTrophyTypeColor(trophy.tipo)}; color: #fff; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: bold;">${trophy.tipo}</span>
                    </div>
                    <p style="margin: 0 0 0.3rem 0; font-size: 0.85rem; color: #999;">${trophy.descripcion || ''}</p>
                    <p style="margin: 0 0 0.3rem 0; font-size: 0.8rem; color: #888;">${trophy.instrucciones || ''}</p>
                    
                    <small style="color: #666;">
<<<<<<< HEAD
                        ${trophy.conseguido ? '<i class="fas fa-trophy" style="color: #ffd700;"></i> Conseguido' : '<i class="fas fa-trophy" style="color: #999;"></i> No conseguido'}
=======
                        <i class="fas fa-trophy" style="color: #ffd700;"></i> Conseguido
                        ${trophy.conseguido ? '<i class="fas fa-check-circle" style="color: #48bb78;"></i>' : '<i class="fas fa-times-circle" style="color: #e53e3e;"></i>'}
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                        ${trophy.online ? '<i class="fas fa-globe" style="color: #4299e1; margin-left: 0.5rem;" title="Online"></i>' : ''}
                        ${trophy.perdible ? '<i class="fas fa-exclamation-triangle" style="color: #ed8936; margin-left: 0.5rem;" title="Perdible"></i>' : ''}
                    </small>
                </div>
                <div class="trophy-item-actions">
                    <button type="button" class="action-btn edit" onclick="editDLCTrophyInline(${trophy.id}, ${dlcId})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="action-btn delete" onclick="deleteDLCTrophy(${trophy.id}, ${dlcId})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `).join('')}
    `;
    
    container.innerHTML = html;
}

// Función para obtener el color del tipo de trofeo
function getTrophyTypeColor(tipo) {
    const colors = {
        'PLATINO': '#e5e4e2',
        'ORO': '#ffd700',
        'PLATA': '#c0c0c0',
        'BRONCE': '#cd7f32'
    };
    return colors[tipo?.toUpperCase()] || '#999';
}

// Función para mostrar formulario para añadir trofeo a DLC
function showAddDLCTrophyForm(dlcId) {
    console.log('➕ Mostrando formulario para añadir trofeo al DLC:', dlcId);
    
    const container = document.getElementById(`dlc-trophies-list-${dlcId}`);
    
    container.innerHTML = `
        <div class="dlc-trophy-form" style="background: #231f36; padding: 1rem; border-radius: 8px; margin-bottom: 0.5rem;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <i class="fas fa-plus-circle" style="color: #ffffff; font-size: 1.5rem;"></i>
                <h4 style="color: #ffffff; margin: 0; font-weight: 300;">Nuevo Trofeo de DLC</h4>
            </div>
            <div class="form-group">
                <label>Nombre del trofeo:</label>
                <input type="text" id="new-dlc-trophy-name-${dlcId}" placeholder="Nombre del trofeo">
            </div>
            <div class="form-group">
                <label>Descripción:</label>
<<<<<<< HEAD
                <input type="text" id="new-dlc-trophy-desc-${dlcId}" placeholder="Descripción del trofeo">
=======
                <textarea id="new-dlc-trophy-desc-${dlcId}" rows="2" placeholder="Descripción del trofeo"></textarea>
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
            </div>
            <div class="form-group">
                <label>Tipo:</label>
                <div class="trophy-type-selector" id="new-dlc-trophy-type-container-${dlcId}">
                    <div class="trophy-type-option selected" data-value="BRONCE" onclick="selectNewDLCTrophyType(${dlcId}, 'BRONCE')">
                        <img src="interfaz/trofeos/bronce.png" alt="Bronce" class="trophy-type-img">
                        <span>Bronce</span>
                    </div>
                    <div class="trophy-type-option" data-value="PLATA" onclick="selectNewDLCTrophyType(${dlcId}, 'PLATA')">
                        <img src="interfaz/trofeos/plata.png" alt="Plata" class="trophy-type-img">
                        <span>Plata</span>
                    </div>
                    <div class="trophy-type-option" data-value="ORO" onclick="selectNewDLCTrophyType(${dlcId}, 'ORO')">
                        <img src="interfaz/trofeos/oro.png" alt="Oro" class="trophy-type-img">
                        <span>Oro</span>
                    </div>
                </div>
                <input type="hidden" id="new-dlc-trophy-type-${dlcId}" value="BRONCE">
            </div>
            <div class="form-group">
                <label>Cómo conseguirlo:</label>
                <div id="new-dlc-trophy-instrucciones-editor-${dlcId}" style="height: 120px; background: #1a1a1a; resize: both; overflow: auto; min-height: 120px;"></div>
                <input type="hidden" id="new-dlc-trophy-instrucciones-${dlcId}">
            </div>
            <div class="form-group">
                <label>Icono del trofeo</label>
                <div class="image-inline-editor">
                    <div class="image-inline-preview image-inline-preview--small">
                        <img id="new-dlc-trophy-icon-preview-${dlcId}" src="interfaz/trofeos/default.png" alt="Preview del icono">
                    </div>
                    <div class="image-inline-controls">
                        <input type="text" id="new-dlc-trophy-icon-${dlcId}" placeholder="URL de la imagen" maxlength="500">
                        <label class="image-file-btn">
                            <input type="file" id="new-dlc-trophy-icon-file-${dlcId}" accept="image/*">
<<<<<<< HEAD
                            <i class="fas fa-upload"></i>
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                            <span>Subir archivo</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Video URL:</label>
                <input type="text" id="new-dlc-trophy-video-${dlcId}" placeholder="https://youtube.com/watch?v=...">
            </div>
<<<<<<< HEAD
            <div class="form-group" style="display: flex; gap: 1rem; align-items: center;">
                <label style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" id="new-dlc-trophy-conseguido-${dlcId}"> Conseguido
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" id="new-dlc-trophy-perdible-${dlcId}"> Perdible
                </label>
                <label style="display: flex; align-items: center; gap: 0.5rem;">
=======
            <div class="form-group">
                <label>
                    <input type="checkbox" id="new-dlc-trophy-conseguido-${dlcId}"> Conseguido
                </label>
                <label>
                    <input type="checkbox" id="new-dlc-trophy-perdible-${dlcId}"> Perdible
                </label>
                <label>
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                    <input type="checkbox" id="new-dlc-trophy-online-${dlcId}"> Online
                </label>
            </div>
            <div style="text-align: center; margin-top: 15px; display: flex; gap: 10px; justify-content: center;">
                <button type="button" class="add-trophy-btn" onclick="saveNewDLCTrophy(${dlcId})">
                    <i class="fas fa-save"></i>
                </button>
                <button class="cancel-btn" onclick="cancelAddDLCTrophy(${dlcId})">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
    
    // Inicializar editor Quill para instrucciones de nuevo trofeo DLC
    setTimeout(() => {
        const quillNewDLCTrophy = new Quill(`#new-dlc-trophy-instrucciones-editor-${dlcId}`, {
            theme: 'snow',
            placeholder: 'Instrucciones para obtener el trofeo...',
            modules: {
                toolbar: {
                    container: RICH_TEXT_TOOLBAR,
                    handlers: {}
                }
            }
        });
        setupRichTextHandlers(quillNewDLCTrophy);

        quillNewDLCTrophy.on('text-change', function() {
            document.getElementById(`new-dlc-trophy-instrucciones-${dlcId}`).value = quillNewDLCTrophy.root.innerHTML;
        });
    }, 100);
}

// Función para seleccionar tipo en el formulario de nuevo trofeo de DLC
function selectNewDLCTrophyType(dlcId, type) {
    document.getElementById(`new-dlc-trophy-type-${dlcId}`).value = type;
    const options = document.querySelectorAll(`#new-dlc-trophy-type-container-${dlcId} .trophy-type-option`);
    options.forEach(opt => {
        opt.classList.remove('selected');
        if (opt.dataset.value === type) opt.classList.add('selected');
    });
}

// Función para cancelar añadir trofeo de DLC
function cancelAddDLCTrophy(dlcId) {
    loadDLCTrophies(dlcId);
}

// Función para guardar nuevo trofeo de DLC
function saveNewDLCTrophy(dlcId) {
    console.log('💾 Guardando nuevo trofeo del DLC:', dlcId);
    
    const trophyData = {
        dlc_id: dlcId,
        nombre_trofeo: document.getElementById(`new-dlc-trophy-name-${dlcId}`).value,
        descripcion: document.getElementById(`new-dlc-trophy-desc-${dlcId}`).value,
        tipo: document.getElementById(`new-dlc-trophy-type-${dlcId}`).value,
        instrucciones: document.getElementById(`new-dlc-trophy-instrucciones-${dlcId}`).value,
        icono_url: document.getElementById(`new-dlc-trophy-icon-${dlcId}`).value,
        video_url: document.getElementById(`new-dlc-trophy-video-${dlcId}`).value,
        conseguido: document.getElementById(`new-dlc-trophy-conseguido-${dlcId}`).checked ? 1 : 0,
        perdible: document.getElementById(`new-dlc-trophy-perdible-${dlcId}`).checked ? 1 : 0,
        online: document.getElementById(`new-dlc-trophy-online-${dlcId}`).checked ? 1 : 0
    };
    
    if (!trophyData.nombre_trofeo) {
        alert('❌ Debes ingresar el nombre del trofeo');
        return;
    }
    
    fetch(`/Prácticas/videojuegos/api/dlc_trophies.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(trophyData)
    })
    .then(res => res.json())
    .then(data => {
        console.log('✅ Trofeo del DLC añadido:', data);
        if (data.success || data.id) {
            alert('✅ Trofeo añadido correctamente');
            loadDLCTrophies(dlcId);
        } else {
            throw new Error(data.error || 'Error desconocido');
        }
    })
    .catch(err => {
        console.error('❌ Error añadiendo trofeo del DLC:', err);
        alert('Error añadiendo trofeo: ' + err.message);
    });
}

// Función para editar trofeo de DLC inline
function editDLCTrophyInline(trophyId, dlcId) {
    console.log('✏️ Editando trofeo del DLC:', trophyId);
    
    fetch(`/Prácticas/videojuegos/api/dlc_trophies.php?dlc_id=${dlcId}`)
        .then(res => res.json())
        .then(trophies => {
            const trophy = trophies.find(t => t.id === trophyId);
            if (!trophy) {
                throw new Error('Trofeo no encontrado');
            }
            
            const container = document.getElementById(`dlc-trophies-list-${dlcId}`);
            
            container.innerHTML = `
                <div class="dlc-trophy-form" style="background: #231f36; padding: 1rem; border-radius: 8px; margin-bottom: 0.5rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <img src="${trophy.icono_url || 'interfaz/trofeos/default.png'}"
                            style="width: auto !important; height: auto !important; object-fit: cover; border-radius: 8px; max-width: 800px !important; display: inline-block !important;"
                            alt="${trophy.nombre_trofeo}">
                        <h4 style="color: #ffffff; margin: 0; font-weight: 300;">Editando: ${trophy.nombre_trofeo}</h4>
                    </div>
                    <div class="form-group">
                        <label>Nombre:</label>
                        <input type="text" id="edit-dlc-trophy-name-${trophyId}" value="${trophy.nombre_trofeo}">
                    </div>
                    <div class="form-group">
                        <label>Tipo:</label>
                        <div class="trophy-type-selector" id="edit-dlc-trophy-type-container-${trophyId}">
                            <div class="trophy-type-option ${(trophy.tipo || '').toString().trim().toUpperCase() === 'PLATINO' ? 'selected' : ''}" data-value="PLATINO" onclick="selectDLCTrophyType(${trophyId}, 'PLATINO')">
                                <img src="interfaz/trofeos/platino.png" alt="Platino" class="trophy-type-img">
                                <span>Platino</span>
                            </div>
                            <div class="trophy-type-option ${(trophy.tipo || '').toString().trim().toUpperCase() === 'ORO' ? 'selected' : ''}" data-value="ORO" onclick="selectDLCTrophyType(${trophyId}, 'ORO')">
                                <img src="interfaz/trofeos/oro.png" alt="Oro" class="trophy-type-img">
                                <span>Oro</span>
                            </div>
                            <div class="trophy-type-option ${(trophy.tipo || '').toString().trim().toUpperCase() === 'PLATA' ? 'selected' : ''}" data-value="PLATA" onclick="selectDLCTrophyType(${trophyId}, 'PLATA')">
                                <img src="interfaz/trofeos/plata.png" alt="Plata" class="trophy-type-img">
                                <span>Plata</span>
                            </div>
                            <div class="trophy-type-option ${(trophy.tipo || '').toString().trim().toUpperCase() === 'BRONCE' ? 'selected' : ''}" data-value="BRONCE" onclick="selectDLCTrophyType(${trophyId}, 'BRONCE')">
                                <img src="interfaz/trofeos/bronce.png" alt="Bronce" class="trophy-type-img">
                                <span>Bronce</span>
                            </div>
                        </div>
                        <input type="hidden" id="edit-dlc-trophy-type-${trophyId}" value="${trophy.tipo || 'BRONCE'}">
                    </div>
                    <div class="form-group">
                        <label>Descripción:</label>
                        <input type="text" id="edit-dlc-trophy-desc-${trophyId}" value="${trophy.descripcion || ''}">
                    </div>
                    <div class="form-group">
                        <label>Icono del trofeo</label>
                        <div class="image-inline-editor">
                            <div class="image-inline-preview image-inline-preview--small">
                                <img id="edit-dlc-trophy-icon-preview-${trophyId}" src="${trophy.icono_url || 'interfaz/trofeos/default.png'}" alt="Preview del icono">
                            </div>
                            <div class="image-inline-controls">
                                <input type="text" id="edit-dlc-trophy-icon-${trophyId}" value="${trophy.icono_url || ''}" placeholder="URL de la imagen" maxlength="500">
                                <label class="image-file-btn">
                                    <input type="file" id="edit-dlc-trophy-icon-file-${trophyId}" accept="image/*">
<<<<<<< HEAD
                                    <i class="fas fa-upload"></i>
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                                    <span>Subir archivo</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Cómo conseguirlo:</label>
                        <div id="edit-dlc-trophy-instrucciones-editor-${trophyId}" style="height: 120px; background: #1a1a1a; resize: both; overflow: auto; min-height: 120px;"></div>
                        <input type="hidden" id="edit-dlc-trophy-instrucciones-${trophyId}">
                    </div>
                    <div class="form-group">
                        <label>Video URL:</label>
                        <input type="text" id="edit-dlc-trophy-video-${trophyId}" value="${trophy.video_url || ''}" onchange="updateVideoPreview(${trophyId}, this.value)">
                        <div id="video-preview-${trophyId}" style="margin-top: 0.5rem;">
                            
                        </div>
                    </div>
                    <div class="form-group" style="display: flex; gap: 2rem; align-items: center;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; margin: 0;">
                            <input type="checkbox" id="edit-dlc-trophy-conseguido-${trophyId}" ${trophy.conseguido ? 'checked' : ''}> Conseguido
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; margin: 0;">
                            <input type="checkbox" id="edit-dlc-trophy-perdible-${trophyId}" ${trophy.perdible ? 'checked' : ''}> Perdible
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; margin: 0;">
                            <input type="checkbox" id="edit-dlc-trophy-online-${trophyId}" ${trophy.online ? 'checked' : ''}> Online
                        </label>
                    </div>
                    <div style="text-align: center; margin-top: 15px; display: flex; gap: 10px; justify-content: center;">
                        <button type="button" style="display: flex; align-items: center; gap: 0.5rem; background: #252525; border: 1px solid #3a3a3a; border-radius: 8px; color: #ffffff; padding: 0.75rem 1.5rem; cursor: pointer;" onclick="saveDLCTrophyEdit(${trophyId}, ${dlcId})">
                            <i class="fas fa-save"></i>
                        </button>
                        <button type="button" style="display: flex; align-items: center; gap: 0.5rem; background: #252525; border: 1px solid #3a3a3a; border-radius: 8px; color: #ffffff; padding: 0.75rem 1.5rem; cursor: pointer;" onclick="cancelDLCTrophyEdit(${dlcId})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            
            // Inicializar editor Quill para instrucciones de edición de trofeo DLC
            setTimeout(() => {
                const quillEditDLCTrophy = new Quill(`#edit-dlc-trophy-instrucciones-editor-${trophyId}`, {
                    theme: 'snow',
                    placeholder: 'Instrucciones para obtener el trofeo...',
                    modules: {
                        toolbar: {
                            container: RICH_TEXT_TOOLBAR,
                            handlers: {}
                        }
                    }
                });
                setupRichTextHandlers(quillEditDLCTrophy);

                // Cargar contenido HTML en el editor
                if (trophy.instrucciones) {
                    quillEditDLCTrophy.root.innerHTML = trophy.instrucciones;
                    document.getElementById(`edit-dlc-trophy-instrucciones-${trophyId}`).value = trophy.instrucciones;
                }

                quillEditDLCTrophy.on('text-change', function() {
                    document.getElementById(`edit-dlc-trophy-instrucciones-${trophyId}`).value = quillEditDLCTrophy.root.innerHTML;
                });
            }, 100);
        })
        .catch(err => {
            console.error('❌ Error obteniendo datos del trofeo:', err);
            alert('Error obteniendo datos del trofeo');
        });
}

// Función para guardar edición de trofeo de DLC
function saveDLCTrophyEdit(trophyId, dlcId) {
    console.log('💾 Guardando cambios del trofeo del DLC:', trophyId);
    
    const trophyData = {
        nombre_trofeo: document.getElementById(`edit-dlc-trophy-name-${trophyId}`).value,
        descripcion: document.getElementById(`edit-dlc-trophy-desc-${trophyId}`).value,
        tipo: document.getElementById(`edit-dlc-trophy-type-${trophyId}`).value,
        instrucciones: document.getElementById(`edit-dlc-trophy-instrucciones-${trophyId}`).value,
        icono_url: document.getElementById(`edit-dlc-trophy-icon-${trophyId}`).value,
        video_url: document.getElementById(`edit-dlc-trophy-video-${trophyId}`).value,
        conseguido: document.getElementById(`edit-dlc-trophy-conseguido-${trophyId}`).checked ? 1 : 0,
        perdible: document.getElementById(`edit-dlc-trophy-perdible-${trophyId}`).checked ? 1 : 0,
        online: document.getElementById(`edit-dlc-trophy-online-${trophyId}`).checked ? 1 : 0
    };
    
    trophyData.id = trophyId;
    
    fetch(`/Prácticas/videojuegos/api/dlc_trophies.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(trophyData)
    })
    .then(res => res.json())
    .then(data => {
        console.log('✅ Trofeo del DLC actualizado:', data);
        alert('✅ Trofeo actualizado correctamente');
        loadDLCTrophies(dlcId);
    })
    .catch(err => {
        console.error('❌ Error actualizando trofeo del DLC:', err);
        alert('Error actualizando trofeo: ' + err.message);
    });
}

// Función para seleccionar tipo en el formulario de edición de trofeo de DLC
function selectDLCTrophyType(trophyId, type) {
    document.getElementById(`edit-dlc-trophy-type-${trophyId}`).value = type;
    const container = document.getElementById(`edit-dlc-trophy-type-container-${trophyId}`);
    const options = container.querySelectorAll('.trophy-type-option');
    options.forEach(option => {
        option.classList.remove('selected');
        if (option.dataset.value === type) {
            option.classList.add('selected');
        }
    });
}

// Función para cancelar edición de trofeo de DLC
function cancelDLCTrophyEdit(dlcId) {
    loadDLCTrophies(dlcId);
}

// Función para eliminar trofeo de DLC
function deleteDLCTrophy(trophyId, dlcId) {
    console.log('🗑️ Eliminando trofeo del DLC:', trophyId);
    
    if (!confirm('¿Estás seguro de eliminar este trofeo?')) {
        return;
    }
    
    fetch(`/Prácticas/videojuegos/api/dlc_trophies.php`, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: trophyId })
    })
    .then(res => res.json())
    .then(data => {
        console.log('✅ Trofeo del DLC eliminado:', data);
        loadDLCTrophies(dlcId);
    })
    .catch(err => {
        console.error('❌ Error eliminando trofeo del DLC:', err);
        alert('❌ Error eliminando trofeo: ' + err.message);
    });
}
</script>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill-indent@1.0.0/dist/quill-indent.min.js"></script>
<script>
// Definir blot personalizado para spoiler
const InlineBlot = Quill.import('blots/inline');

class SpoilerBlot extends InlineBlot {
    static blotName = 'spoiler';
    static tagName = 'span';
    static className = 'ql-spoiler';
}

Quill.register(SpoilerBlot, true);

// También registrar el formato en Quill
Quill.register({
    'formats/spoiler': SpoilerBlot
}, true);

const EXTENDED_COLOR_PALETTE = [
    '#000000', '#ffffff', '#e60000', '#ff9900', '#ffff00', '#008000', '#00bfff', '#0000ff', '#9900ff', '#ff00ff',
    '#333333', '#666666', '#999999', '#cccccc', '#f5f5f5', '#9c5b3d', '#7f5af0', '#2cb67d', '#f97316', '#38bdf8',
    '#1f2937', '#7c2d12', '#0f766e', '#1d4ed8', '#7e22ce', '#be185d'
];

const RICH_TEXT_TOOLBAR = [
    [{ 'header': [1, 2, 3, false] }],
    ['bold', 'italic', 'underline', 'strike'],
    [{ 'align': '' }, { 'align': 'center' }, { 'align': 'right' }, { 'align': 'justify' }],
    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
    [{ 'indent': '-1'}, { 'indent': '+1' }],
    [{ 'color': EXTENDED_COLOR_PALETTE }, { 'background': EXTENDED_COLOR_PALETTE }],
    ['link', 'image', 'video'],
    ['clean'],
    ['spoiler']
];

function setupRichTextHandlers(editor) {
    const toolbar = editor.getModule('toolbar');
    if (toolbar && toolbar.handlers) {
        toolbar.handlers.spoiler = function() {
            const range = editor.getSelection();
            if (range && range.length > 0) {
                editor.formatText(range.index, range.length, 'spoiler', true);
            } else {
                // Si no hay texto seleccionado, insertar un placeholder
                editor.insertText(range.index, 'Spoiler', 'user');
                editor.formatText(range.index, 7, 'spoiler', true);
            }
        };

        toolbar.handlers.video = function() {
            const url = prompt('Introduce la URL del vídeo (YouTube o enlace directo):', 'https://www.youtube.com/watch?v=');
            if (!url) return;

            let embedUrl = url;
            const youtubeMatch = url.match(/(?:youtube\.com\/watch\?v=|youtube\.com\/embed\/|youtu\.be\/)([a-zA-Z0-9_-]{11})/i);
            if (youtubeMatch) {
                embedUrl = `https://www.youtube.com/embed/${youtubeMatch[1]}`;
            }

            const range = editor.getSelection(true);
            if (range) {
                editor.insertEmbed(range.index, 'video', embedUrl);
                editor.setSelection(range.index + 1, 0);
            }
        };

        toolbar.handlers.image = function() {
            const choice = confirm('¿Quieres agregar una imagen desde una URL?\n\nAceptar = URL\nCancelar = Subir archivo del PC');
            
            if (choice) {
                // Usar URL
                const url = prompt('Introduce la URL de la imagen:', 'https://');
                if (url) {
                    const range = editor.getSelection(true);
                    if (range) {
                        editor.insertEmbed(range.index, 'image', url);
                        editor.setSelection(range.index + 1, 0);
                    }
                }
            } else {
                // Usar el comportamiento por defecto (subir archivo)
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.click();
                
                input.onchange = () => {
                    const file = input.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const range = editor.getSelection(true);
                            if (range) {
                                editor.insertEmbed(range.index, 'image', e.target.result);
                                editor.setSelection(range.index + 1, 0);
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                };
            }
        };
    }
}

// Inicializar editor Quill para comentarios
let quill, quillTrofeosPerdibles, quillTrofeosOcultos, quillNewTrophy, quillEditTrophy;
document.addEventListener('DOMContentLoaded', function() {
    // Editor para comentarios
    quill = new Quill('#comentario-editor', {
        theme: 'snow',
        placeholder: 'Consejos para conseguir el platino, estrategias, etc.',
        modules: {
            toolbar: {
                container: RICH_TEXT_TOOLBAR,
                handlers: {}
            }
        }
    });
    setupRichTextHandlers(quill);

    // Editor para trofeos perdibles del juego base
    console.log('Verificando editor de trofeos perdibles:', document.getElementById('trofeos-perdibles-editor'));
    if (document.getElementById('trofeos-perdibles-editor')) {
        console.log('Inicializando editor de trofeos perdibles...');
        quillTrofeosPerdibles = new Quill('#trofeos-perdibles-editor', {
            theme: 'snow',
            placeholder: 'Descripción de trofeos perdibles',
            modules: {
                toolbar: {
                    container: RICH_TEXT_TOOLBAR,
                    handlers: {}
                }
            }
        });
        setupRichTextHandlers(quillTrofeosPerdibles);
        quillTrofeosPerdibles.on('text-change', function() {
            document.getElementById('trofeos_perdibles').value = quillTrofeosPerdibles.root.innerHTML;
        });
        console.log('Editor de trofeos perdibles inicializado correctamente');
    }

    // Editor para trofeos ocultos del juego base
    console.log('Verificando editor de trofeos ocultos:', document.getElementById('trofeos-ocultos-editor'));
    if (document.getElementById('trofeos-ocultos-editor')) {
        console.log('Inicializando editor de trofeos ocultos...');
        quillTrofeosOcultos = new Quill('#trofeos-ocultos-editor', {
            theme: 'snow',
            placeholder: 'Descripción de trofeos ocultos',
            modules: {
                toolbar: {
                    container: RICH_TEXT_TOOLBAR,
                    handlers: {}
                }
            }
        });
        setupRichTextHandlers(quillTrofeosOcultos);
        quillTrofeosOcultos.on('text-change', function() {
            document.getElementById('trofeos_ocultos').value = quillTrofeosOcultos.root.innerHTML;
        });
        console.log('Editor de trofeos ocultos inicializado correctamente');
    }

    // Sincronizar contenido con el input hidden
    quill.on('text-change', function() {
        document.getElementById('comentario').value = quill.root.innerHTML;
    });

});

// Sobrescribir loadGameData para cargar contenido HTML en Quill
const originalLoadGameData = loadGameData;
loadGameData = function(gameId) {
    console.log('🎮 Cargando datos del juego...');
    
    fetch(`/Prácticas/videojuegos/api/game.php?id=${gameId}`)
        .then(res => {
            console.log('📡 Respuesta juegos:', res.status);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            return res.json();
        })
        .then(data => {
            console.log('✅ Datos del juego cargados:', data);
            
            if (data.error) {
                throw new Error(data.error);
            }
            
            // Cargar datos en el formulario
            document.getElementById('titulo').value = data.titulo || '';
            document.getElementById('plataforma').value = data.plataforma || 'PS4';
            document.getElementById('fecha_lanzamiento').value = data.fecha_lanzamiento || '';
            document.getElementById('genero').value = data.genero || '';
            document.getElementById('desarrollador').value = data.desarrollador || '';
            document.getElementById('dificultad_platino').value = data.dificultad_platino || '5 sobre 10';
            document.getElementById('duracion_estimada').value = data.duracion_estimada || '';
            
            // Cargar comentario en Quill
            if (data.comentario && quill) {
                quill.root.innerHTML = data.comentario;
                document.getElementById('comentario').value = data.comentario;
            }
            
            // Cargar trofeos perdibles en el editor enriquecido
            if (data.trofeos_perdibles && quillTrofeosPerdibles) {
                quillTrofeosPerdibles.root.innerHTML = data.trofeos_perdibles;
                document.getElementById('trofeos_perdibles').value = data.trofeos_perdibles;
            } else if (data.trofeos_perdibles) {
                document.getElementById('trofeos_perdibles').value = data.trofeos_perdibles;
            }

            // Cargar trofeos ocultos en el editor enriquecido
            if (data.trofeos_ocultos && quillTrofeosOcultos) {
                quillTrofeosOcultos.root.innerHTML = data.trofeos_ocultos;
                document.getElementById('trofeos_ocultos').value = data.trofeos_ocultos;
            } else if (data.trofeos_ocultos) {
                document.getElementById('trofeos_ocultos').value = data.trofeos_ocultos;
            }
            
            // Cargar campos de trofeos
            document.getElementById('trofeos_offline_platino').value = data.trofeos_offline_platino || '';
            document.getElementById('trofeos_offline_oro').value = data.trofeos_offline_oro || '';
            document.getElementById('trofeos_offline_plata').value = data.trofeos_offline_plata || '';
            document.getElementById('trofeos_offline_bronce').value = data.trofeos_offline_bronce || '';
            
            document.getElementById('trofeos_online_platino').value = data.trofeos_online_platino || '';
            document.getElementById('trofeos_online_oro').value = data.trofeos_online_oro || '';
            document.getElementById('trofeos_online_plata').value = data.trofeos_online_plata || '';
            document.getElementById('trofeos_online_bronce').value = data.trofeos_online_bronce || '';
            
            document.getElementById('pase_online').value = data.pase_online || '0';
            document.getElementById('necesario_platino').value = data.necesario_platino || 'NO';
            document.getElementById('trofeos_ocultos').value = data.trofeos_ocultos || '';
            document.getElementById('min_partidas').value = data.min_partidas || '1 Partida';
            document.getElementById('trofeos_perdibles').value = data.trofeos_perdibles || '';
            document.getElementById('trucos_afectan').value = data.trucos_afectan || '0';
            document.getElementById('dificultad_afecta').value = data.dificultad_afecta || '0';
            
            // Cargar el total de trofeos desde la base de datos
            if (data.total_trofeos) {
                document.getElementById('total-trofeos').textContent = data.total_trofeos;
            } else {
                // Si no hay total guardado, calcularlo
                calcularTotalTrofeos();
            }
            
            if (data.imagen_url) {
                document.getElementById('current-icon').src = data.imagen_url;
                document.getElementById('icono-url').value = data.imagen_url;
            }
            
            if (data.banner_url) {
                document.getElementById('current-banner').src = data.banner_url;
                document.getElementById('banner-url').value = data.banner_url;
            }

            // Cargar coordenadas de recorte del banner
            if (data.banner_crop_x) {
                document.getElementById('banner-crop-x').value = data.banner_crop_x;
            }
            if (data.banner_crop_y) {
                document.getElementById('banner-crop-y').value = data.banner_crop_y;
            }
            if (data.banner_crop_width) {
                document.getElementById('banner-crop-width').value = data.banner_crop_width;
            }
            if (data.banner_crop_height) {
                document.getElementById('banner-crop-height').value = data.banner_crop_height;
            }

            if (data.pegi_url) {
                document.getElementById('current-pegi').src = data.pegi_url;
                document.getElementById('pegi-url').value = data.pegi_url;
            }

            if (data.clasificacion_1_url) {
                document.getElementById('current-clas1').src = data.clasificacion_1_url;
                document.getElementById('clasificacion1-url').value = data.clasificacion_1_url;
            }
            document.getElementById('show-clas1').checked = data.show_clas1 == 1;

            if (data.clasificacion_2_url) {
                document.getElementById('current-clas2').src = data.clasificacion_2_url;
                document.getElementById('clasificacion2-url').value = data.clasificacion_2_url;
            }
            document.getElementById('show-clas2').checked = data.show_clas2 == 1;

            if (data.clasificacion_3_url) {
                document.getElementById('current-clas3').src = data.clasificacion_3_url;
                document.getElementById('clasificacion3-url').value = data.clasificacion_3_url;
            }
            document.getElementById('show-clas3').checked = data.show_clas3 == 1;

            if (data.mapa_interactivo_url) {
                document.getElementById('mapa-interactivo-url').value = data.mapa_interactivo_url;
            }
            
            console.log('✅ Formulario de juego actualizado');
        })
        .catch(err => {
            console.error('❌ Error cargando juego:', err);
        });
};
</script>
    </div>
<?php include 'estilos/footer.php'; ?>
</body>
</html>