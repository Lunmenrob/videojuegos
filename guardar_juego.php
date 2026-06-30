<?php
require_once 'config.php';
require_once 'csrf.php';

try {
    $conn = getConnection();
} catch (Exception $e) {
    die('Error de conexión: ' . $e->getMessage());
}

    // Validar token CSRF para POST requests
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!validateCsrfToken($csrfToken)) {
            die('Error de seguridad: Token CSRF inválido. Por favor, recargue la página e intente nuevamente.');
        }
    }

function uploadFileIfPresent($fieldName, $existingValue = null) {
    if (!isset($_FILES[$fieldName]) || !is_uploaded_file($_FILES[$fieldName]['tmp_name'])) {
        return $existingValue;
    }

    if ($_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        return $existingValue;
    }

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $extension = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, $allowedExtensions, true)) {
        return $existingValue;
    }

    // Validar tamaño máximo (5MB)
    $maxSize = 5 * 1024 * 1024;
    if ($_FILES[$fieldName]['size'] > $maxSize) {
        return $existingValue;
    }

    // Validar tipo MIME real del archivo
    $allowedMimeTypes = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
        'image/webp'
    ];
    
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $_FILES[$fieldName]['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedMimeTypes)) {
        return $existingValue;
    }

    // Validar que el archivo sea realmente una imagen usando getimagesize
    $imageInfo = @getimagesize($_FILES[$fieldName]['tmp_name']);
    if ($imageInfo === false) {
        return $existingValue;
    }

    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileName = uniqid('img_', true) . '.' . $extension;
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $targetPath)) {
        return 'uploads/' . $fileName;
    }

    return $existingValue;
}

try {
    $conn = getConnection();
    
    // Si solo se está guardando la galería, no validar los campos requeridos del formulario
    $saveGalleryOnly = isset($_POST['save_gallery_only']) && $_POST['save_gallery_only'] === 'true';
    
    if (!$saveGalleryOnly) {
        $id = $_POST['id'] ?? null;
        $titulo = $_POST['titulo'] ?? '';
        $plataforma = $_POST['plataforma'] ?? 'PS4';
        $fecha_lanzamiento = !empty($_POST['fecha_lanzamiento']) ? $_POST['fecha_lanzamiento'] : null;
        $icono_url = !empty($_POST['icono_url']) ? $_POST['icono_url'] : null;
        $banner_url = !empty($_POST['banner_url']) ? $_POST['banner_url'] : null;
        $pegi_url = !empty($_POST['pegi_url']) ? $_POST['pegi_url'] : null;
        $clasificacion_1_url = !empty($_POST['clasificacion_1_url']) ? $_POST['clasificacion_1_url'] : null;
        $clasificacion_2_url = !empty($_POST['clasificacion_2_url']) ? $_POST['clasificacion_2_url'] : null;
        $clasificacion_3_url = !empty($_POST['clasificacion_3_url']) ? $_POST['clasificacion_3_url'] : null;
        $show_clas1 = isset($_POST['show_clas1']) ? 1 : 0;
        $show_clas2 = isset($_POST['show_clas2']) ? 1 : 0;
        $show_clas3 = isset($_POST['show_clas3']) ? 1 : 0;
        $genero = !empty($_POST['genero']) ? $_POST['genero'] : null;
        $desarrollador = !empty($_POST['desarrollador']) ? $_POST['desarrollador'] : null;
        $dificultad_platino = $_POST['dificultad_platino'] ?? '1 sobre 10';
        $duracion_estimada = !empty($_POST['duracion_estimada']) ? $_POST['duracion_estimada'] : null;
        $comentario = !empty($_POST['comentario']) ? $_POST['comentario'] : null;
        
        if (!$titulo) {
            die('Faltan datos requeridos');
        }
        
        // Validar longitud de campos
        if (strlen($titulo) > 200) {
            die('El título no puede superar los 200 caracteres');
        }
        
        if (!empty($icono_url) && strlen($icono_url) > 500) {
            die('La URL del icono no puede superar los 500 caracteres');
        }
        
        if (!empty($genero) && strlen($genero) > 100) {
            die('El género no puede superar los 100 caracteres');
        }
        
        if (!empty($desarrollador) && strlen($desarrollador) > 100) {
            die('El desarrollador no puede superar los 100 caracteres');
        }
        
        if (!empty($duracion_estimada) && strlen($duracion_estimada) > 50) {
            die('La duración estimada no puede superar los 50 caracteres');
        }
        
        if (!empty($comentario) && strlen($comentario) > 5000) {
            die('Los comentarios no pueden superar los 5000 caracteres');
        }
    } else {
        // Solo guardar galería, obtener solo el ID y los media items
        $id = $_POST['id'] ?? null;
        $titulo = '';
        $plataforma = 'PS4';
        $fecha_lanzamiento = null;
        $icono_url = null;
        $banner_url = null;
        $pegi_url = null;
        $clasificacion_1_url = null;
        $clasificacion_2_url = null;
        $clasificacion_3_url = null;
        $genero = null;
        $desarrollador = null;
        $dificultad_platino = '1 sobre 10';
        $duracion_estimada = null;
        $comentario = null;
    }
    
    $mediaItemsJson = $_POST['media_items_json'] ?? '[]';
    $mediaItems = json_decode($mediaItemsJson, true);
    if (!is_array($mediaItems)) {
        $mediaItems = [];
    }
    
    $mapasJson = $_POST['mapas_json'] ?? '[]';
    $mapas = json_decode($mapasJson, true);
    if (!is_array($mapas)) {
        $mapas = [];
    }
    
    error_log('Media items JSON: ' . $mediaItemsJson);
    error_log('Media items decoded: ' . print_r($mediaItems, true));
    error_log('Mapas JSON: ' . $mapasJson);
    error_log('Mapas decoded: ' . print_r($mapas, true));
    
    // Campos de trofeos - convertir a enteros
    $trofeos_offline_platino = (int)($_POST['trofeos_offline_platino'] ?? 0);
    $trofeos_offline_oro = (int)($_POST['trofeos_offline_oro'] ?? 0);
    $trofeos_offline_plata = (int)($_POST['trofeos_offline_plata'] ?? 0);
    $trofeos_offline_bronce = (int)($_POST['trofeos_offline_bronce'] ?? 0);
    
    $trofeos_online_platino = (int)($_POST['trofeos_online_platino'] ?? 0);
    $trofeos_online_oro = (int)($_POST['trofeos_online_oro'] ?? 0);
    $trofeos_online_plata = (int)($_POST['trofeos_online_plata'] ?? 0);
    $trofeos_online_bronce = (int)($_POST['trofeos_online_bronce'] ?? 0);
    
    // Calcular total de trofeos para guardar
    $total_trofeos = $trofeos_offline_platino + $trofeos_offline_oro + $trofeos_offline_plata + $trofeos_offline_bronce +
                $trofeos_online_platino + $trofeos_online_oro + $trofeos_online_plata + $trofeos_online_bronce;
    
    $pase_online = $_POST['pase_online'] ?? 0;
    $necesario_platino = $_POST['necesario_platino'] ?? 'NO';
    $trofeos_ocultos = !empty($_POST['trofeos_ocultos']) ? $_POST['trofeos_ocultos'] : null;
    $min_partidas = $_POST['min_partidas'] ?? '1 Partida';
    $trofeos_perdibles = !empty($_POST['trofeos_perdibles']) ? $_POST['trofeos_perdibles'] : null;
    $trucos_afectan = $_POST['trucos_afectan'] ?? 0;
    $dificultad_afecta = $_POST['dificultad_afecta'] ?? 0;

    $icono_url = uploadFileIfPresent('icono_file', $icono_url);
    $banner_url = uploadFileIfPresent('banner_file', $banner_url);
    $pegi_url = uploadFileIfPresent('pegi_file', $pegi_url);
    $clasificacion_1_url = uploadFileIfPresent('clasificacion_1_file', $clasificacion_1_url);
    $clasificacion_2_url = uploadFileIfPresent('clasificacion_2_file', $clasificacion_2_url);
    $clasificacion_3_url = uploadFileIfPresent('clasificacion_3_file', $clasificacion_3_url);
    
    // Si solo se está guardando la galería, no guardar los demás campos del juego
    if (!$saveGalleryOnly) {
        // Coordenadas de recorte del banner
        $banner_crop_x = !empty($_POST['banner_crop_x']) ? (float)$_POST['banner_crop_x'] : 50;
        $banner_crop_y = !empty($_POST['banner_crop_y']) ? (float)$_POST['banner_crop_y'] : 37;
        $banner_crop_width = !empty($_POST['banner_crop_width']) ? (float)$_POST['banner_crop_width'] : 100;
        $banner_crop_height = !empty($_POST['banner_crop_height']) ? (float)$_POST['banner_crop_height'] : 100;
        
        $icono_url = uploadFileIfPresent('icono_file', $icono_url);
        $banner_url = uploadFileIfPresent('banner_file', $banner_url);
        $pegi_url = uploadFileIfPresent('pegi_file', $pegi_url);
        $clasificacion_1_url = uploadFileIfPresent('clasificacion_1_file', $clasificacion_1_url);
        $clasificacion_2_url = uploadFileIfPresent('clasificacion_2_file', $clasificacion_2_url);
        $clasificacion_3_url = uploadFileIfPresent('clasificacion_3_file', $clasificacion_3_url);
        
        // Si no hay ID, es un nuevo juego (INSERT)
        $esNuevo = empty($id);
        
        if ($esNuevo) {
            // INSERT para nuevo juego
            $stmt = $conn->prepare("
                INSERT INTO juegos (
                    titulo, plataforma, fecha_lanzamiento, imagen_url, banner_url, banner_crop_x, banner_crop_y, banner_crop_width, banner_crop_height,
                    pegi_url, clasificacion_1_url, clasificacion_2_url, clasificacion_3_url, show_clas1, show_clas2, show_clas3,
                    genero, desarrollador, dificultad_platino, duracion_estimada, trofeos_offline_platino, trofeos_offline_oro,
                    trofeos_offline_plata, trofeos_offline_bronce, trofeos_online_platino, trofeos_online_oro,
                    trofeos_online_plata, trofeos_online_bronce, total_trofeos, pase_online, necesario_platino,
                    trofeos_ocultos, min_partidas, trofeos_perdibles, trucos_afectan, dificultad_afecta, comentario
                ) VALUES (
                    :titulo, :plataforma, :fecha_lanzamiento, :imagen_url, :banner_url, :banner_crop_x, :banner_crop_y, :banner_crop_width, :banner_crop_height,
                    :pegi_url, :clasificacion_1_url, :clasificacion_2_url, :clasificacion_3_url, :show_clas1, :show_clas2, :show_clas3,
                    :genero, :desarrollador, :dificultad_platino, :duracion_estimada, :trofeos_offline_platino, :trofeos_offline_oro,
                    :trofeos_offline_plata, :trofeos_offline_bronce, :trofeos_online_platino, :trofeos_online_oro,
                    :trofeos_online_plata, :trofeos_online_bronce, :total_trofeos, :pase_online, :necesario_platino,
                    :trofeos_ocultos, :min_partidas, :trofeos_perdibles, :trucos_afectan, :dificultad_afecta, :comentario
                )
            ");
            
            $stmt->execute([
                ':titulo' => $titulo,
                ':plataforma' => $plataforma,
                ':fecha_lanzamiento' => $fecha_lanzamiento,
                ':imagen_url' => $icono_url,
                ':banner_url' => $banner_url,
                ':banner_crop_x' => $banner_crop_x,
                ':banner_crop_y' => $banner_crop_y,
                ':banner_crop_width' => $banner_crop_width,
                ':banner_crop_height' => $banner_crop_height,
                ':pegi_url' => $pegi_url,
                ':clasificacion_1_url' => $clasificacion_1_url,
                ':clasificacion_2_url' => $clasificacion_2_url,
                ':clasificacion_3_url' => $clasificacion_3_url,
                ':show_clas1' => $show_clas1,
                ':show_clas2' => $show_clas2,
                ':show_clas3' => $show_clas3,
                ':genero' => $genero,
                ':desarrollador' => $desarrollador,
                ':dificultad_platino' => $dificultad_platino,
                ':duracion_estimada' => $duracion_estimada,
                ':trofeos_offline_platino' => $trofeos_offline_platino,
                ':trofeos_offline_oro' => $trofeos_offline_oro,
                ':trofeos_offline_plata' => $trofeos_offline_plata,
                ':trofeos_offline_bronce' => $trofeos_offline_bronce,
                ':trofeos_online_platino' => $trofeos_online_platino,
                ':trofeos_online_oro' => $trofeos_online_oro,
                ':trofeos_online_plata' => $trofeos_online_plata,
                ':trofeos_online_bronce' => $trofeos_online_bronce,
                ':total_trofeos' => $total_trofeos,
                ':pase_online' => $pase_online,
                ':necesario_platino' => $necesario_platino,
                ':trofeos_ocultos' => $trofeos_ocultos,
                ':min_partidas' => $min_partidas,
                ':trofeos_perdibles' => $trofeos_perdibles,
                ':trucos_afectan' => $trucos_afectan,
                ':dificultad_afecta' => $dificultad_afecta,
                ':comentario' => $comentario
            ]);
            
            $id = $conn->lastInsertId();
        } else {
            // UPDATE para juego existente
            $stmtGet = $conn->prepare("SELECT imagen_url, banner_url, pegi_url, clasificacion_1_url, clasificacion_2_url, clasificacion_3_url FROM juegos WHERE id = :id");
            $stmtGet->execute([':id' => $id]);
            $currentGame = $stmtGet->fetch(PDO::FETCH_ASSOC);
            if (empty($icono_url)) {
                $icono_url = $currentGame['imagen_url'] ?? null;
            }
            if (empty($banner_url)) {
                $banner_url = $currentGame['banner_url'] ?? null;
            }
            if (empty($pegi_url)) {
                $pegi_url = $currentGame['pegi_url'] ?? null;
            }
            if (empty($clasificacion_1_url)) {
                $clasificacion_1_url = $currentGame['clasificacion_1_url'] ?? null;
            }
            if (empty($clasificacion_2_url)) {
                $clasificacion_2_url = $currentGame['clasificacion_2_url'] ?? null;
            }
            if (empty($clasificacion_3_url)) {
                $clasificacion_3_url = $currentGame['clasificacion_3_url'] ?? null;
            }
            
            $stmt = $conn->prepare("
                UPDATE juegos 
                SET titulo = :titulo,
                    plataforma = :plataforma,
                    fecha_lanzamiento = :fecha_lanzamiento,
                    imagen_url = :imagen_url,
                    banner_url = :banner_url,
                    banner_crop_x = :banner_crop_x,
                    banner_crop_y = :banner_crop_y,
                    banner_crop_width = :banner_crop_width,
                    banner_crop_height = :banner_crop_height,
                    pegi_url = :pegi_url,
                    clasificacion_1_url = :clasificacion_1_url,
                    clasificacion_2_url = :clasificacion_2_url,
                    clasificacion_3_url = :clasificacion_3_url,
                    show_clas1 = :show_clas1,
                    show_clas2 = :show_clas2,
                    show_clas3 = :show_clas3,
                    genero = :genero,
                    desarrollador = :desarrollador,
                    dificultad_platino = :dificultad_platino,
                    duracion_estimada = :duracion_estimada,
                    trofeos_offline_platino = :trofeos_offline_platino,
                    trofeos_offline_oro = :trofeos_offline_oro,
                    trofeos_offline_plata = :trofeos_offline_plata,
                    trofeos_offline_bronce = :trofeos_offline_bronce,
                    trofeos_online_platino = :trofeos_online_platino,
                    trofeos_online_oro = :trofeos_online_oro,
                    trofeos_online_plata = :trofeos_online_plata,
                    trofeos_online_bronce = :trofeos_online_bronce,
                    total_trofeos = :total_trofeos,
                    pase_online = :pase_online,
                    necesario_platino = :necesario_platino,
                    trofeos_ocultos = :trofeos_ocultos,
                    min_partidas = :min_partidas,
                    trofeos_perdibles = :trofeos_perdibles,
                    trucos_afectan = :trucos_afectan,
                    dificultad_afecta = :dificultad_afecta,
                    comentario = :comentario
                WHERE id = :id
            ");
            
            $stmt->execute([
                ':id' => $id,
                ':titulo' => $titulo,
                ':plataforma' => $plataforma,
                ':fecha_lanzamiento' => $fecha_lanzamiento,
                ':imagen_url' => $icono_url,
                ':banner_url' => $banner_url,
                ':banner_crop_x' => $banner_crop_x,
                ':banner_crop_y' => $banner_crop_y,
                ':banner_crop_width' => $banner_crop_width,
                ':banner_crop_height' => $banner_crop_height,
                ':pegi_url' => $pegi_url,
                ':clasificacion_1_url' => $clasificacion_1_url,
                ':clasificacion_2_url' => $clasificacion_2_url,
                ':clasificacion_3_url' => $clasificacion_3_url,
                ':show_clas1' => $show_clas1,
                ':show_clas2' => $show_clas2,
                ':show_clas3' => $show_clas3,
                ':genero' => $genero,
                ':desarrollador' => $desarrollador,
                ':dificultad_platino' => $dificultad_platino,
                ':duracion_estimada' => $duracion_estimada,
                ':trofeos_offline_platino' => $trofeos_offline_platino,
                ':trofeos_offline_oro' => $trofeos_offline_oro,
                ':trofeos_offline_plata' => $trofeos_offline_plata,
                ':trofeos_offline_bronce' => $trofeos_offline_bronce,
                ':trofeos_online_platino' => $trofeos_online_platino,
                ':trofeos_online_oro' => $trofeos_online_oro,
                ':trofeos_online_plata' => $trofeos_online_plata,
                ':trofeos_online_bronce' => $trofeos_online_bronce,
                ':total_trofeos' => $total_trofeos,
                ':pase_online' => $pase_online,
                ':necesario_platino' => $necesario_platino,
                ':trofeos_ocultos' => $trofeos_ocultos,
                ':min_partidas' => $min_partidas,
                ':trofeos_perdibles' => $trofeos_perdibles,
                ':trucos_afectan' => $trucos_afectan,
                ':dificultad_afecta' => $dificultad_afecta,
                ':comentario' => $comentario
            ]);
        }
    }

    // Guardar mapas interactivos siempre que haya un ID
    if (!empty($id) && is_array($mapas)) {
        error_log('Guardando mapas interactivos para juego ID: ' . $id);
        error_log('Cantidad de mapas: ' . count($mapas));
        
        $stmtDeleteMapas = $conn->prepare('DELETE FROM mapas_interactivos WHERE juego_id = :juego_id');
        $stmtDeleteMapas->execute([':juego_id' => (int)$id]);
        error_log('Mapas interactivos eliminados');

        if (count($mapas) > 0) {
            $stmtInsertMapa = $conn->prepare('INSERT INTO mapas_interactivos (juego_id, nombre, url, orden) VALUES (:juego_id, :nombre, :url, :orden)');
            foreach ($mapas as $index => $mapa) {
                error_log('Procesando mapa ' . $index . ': ' . print_r($mapa, true));
                if (empty($mapa['url']) || empty($mapa['nombre'])) {
                    error_log('Mapa ' . $index . ' sin URL o nombre, saltando');
                    continue;
                }
                $stmtInsertMapa->execute([
                    ':juego_id' => (int)$id,
                    ':nombre' => $mapa['nombre'],
                    ':url' => $mapa['url'],
                    ':orden' => isset($mapa['orden']) ? (int)$mapa['orden'] : $index
                ]);
                error_log('Mapa ' . $index . ' insertado');
            }
        }
    }

    if (!empty($id) && is_array($mediaItems)) {
        error_log('Guardando media items para juego ID: ' . $id);
        error_log('Cantidad de media items: ' . count($mediaItems));
        
        $stmtDeleteMedia = $conn->prepare('DELETE FROM media_juegos WHERE videojuego_id = :videojuego_id');
        $stmtDeleteMedia->execute([':videojuego_id' => (int)$id]);
        error_log('Media items eliminados');

        if (count($mediaItems) > 0) {
            $stmtInsertMedia = $conn->prepare('INSERT INTO media_juegos (videojuego_id, tipo, url, orden) VALUES (:videojuego_id, :tipo, :url, :orden)');
            foreach ($mediaItems as $index => $item) {
                error_log('Procesando media item ' . $index . ': ' . print_r($item, true));
                if (empty($item['url'])) {
                    error_log('Media item ' . $index . ' sin URL, saltando');
                    continue;
                }
                $stmtInsertMedia->execute([
                    ':videojuego_id' => (int)$id,
                    ':tipo' => in_array($item['tipo'] ?? 'image', ['image', 'video'], true) ? $item['tipo'] : 'image',
                    ':url' => $item['url'],
                    ':orden' => isset($item['orden']) ? (int)$item['orden'] : $index
                ]);
                error_log('Media item ' . $index . ' insertado');
            }
        }
    }

    // Si solo se está guardando la galería, no redirigir
    if (isset($_POST['save_gallery_only']) && $_POST['save_gallery_only'] === 'true') {
        error_log('Guardando solo la galería, mediaItems: ' . print_r($mediaItems, true));
        echo json_encode(['success' => true, 'message' => 'Galería guardada correctamente']);
        exit;
    }
    
    header('Location: editar_juego.php?id=' . $id);
    exit;
    
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
