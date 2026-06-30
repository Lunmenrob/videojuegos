<?php
// Habilitar display errors para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config.php';

try {
    $conn = getConnection();
    $method = $_SERVER['REQUEST_METHOD'];
    
    switch ($method) {
        case 'GET':
            // Obtener un videojuego específico
            if (isset($_GET['id'])) {
                $gameId = (int)$_GET['id'];
                
                // Debug: Log del ID recibido
                error_log("API: Buscando juego con ID: " . $gameId);
                
                $stmt = $conn->prepare("
                    SELECT v.*, 
                        p.total_trofeos,
                        p.bronce_conseguidos,
                        p.plata_conseguidos,
                        p.oro_conseguidos,
                        p.platino_conseguido,
                        p.porcentaje_completado
                    FROM juegos v
                    LEFT JOIN progreso_trofeos p ON v.id = p.videojuego_id
                    WHERE v.id = :id
                ");
                $stmt->execute([':id' => $gameId]);
                $game = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Debug: Log del resultado
                error_log("API: Resultado de la consulta: " . ($game ? "Encontrado" : "No encontrado"));
                if ($game) {
                    error_log("API: Datos del juego: " . print_r($game, true));
                }
                
                if ($game) {
                    $game['id'] = (int)$game['id'];
                    $game['total_trofeos'] = (int)($game['total_trofeos'] ?? 0);
                    $game['bronce_conseguidos'] = (int)($game['bronce_conseguidos'] ?? 0);
                    $game['plata_conseguidos'] = (int)($game['plata_conseguidos'] ?? 0);
                    $game['oro_conseguidos'] = (int)($game['oro_conseguidos'] ?? 0);
                    $game['platino_conseguido'] = (bool)($game['platino_conseguido'] ?? false);
                    $game['porcentaje_completado'] = (float)($game['porcentaje_completado'] ?? 0);
                    $game['show_clas1'] = (bool)($game['show_clas1'] ?? true);
                    $game['show_clas2'] = (bool)($game['show_clas2'] ?? true);
                    $game['show_clas3'] = (bool)($game['show_clas3'] ?? true);
                    
                    // Decodificar entidades HTML en comentario y trofeos_perdibles
                    if (isset($game['comentario'])) {
                        $game['comentario'] = html_entity_decode($game['comentario'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    }
                    if (isset($game['trofeos_perdibles'])) {
                        $game['trofeos_perdibles'] = html_entity_decode($game['trofeos_perdibles'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    }
                    
<<<<<<< HEAD
                    // Cargar mapas interactivos
                    $stmtMapas = $conn->prepare("SELECT id, nombre, url, orden FROM mapas_interactivos WHERE juego_id = :juego_id ORDER BY orden ASC");
                    $stmtMapas->execute([':juego_id' => $gameId]);
                    $mapas = $stmtMapas->fetchAll(PDO::FETCH_ASSOC);
                    $game['mapas_interactivos'] = $mapas;
                    
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                    echo json_encode($game, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Videojuego no encontrado con ID: ' . $gameId]);
                }
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'ID de juego no proporcionado']);
            }
            break;
            
        case 'POST':
            // Crear nuevo videojuego
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data || !isset($data['titulo']) || !isset($data['plataforma'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Faltan datos requeridos']);
                break;
            }
            
            $stmt = $conn->prepare("
<<<<<<< HEAD
                INSERT INTO juegos (titulo, plataforma, fecha_lanzamiento, genero, desarrollador, imagen_url, banner_url, pegi_url, clasificacion_1_url, clasificacion_2_url, clasificacion_3_url)
                VALUES (:titulo, :plataforma, :fecha_lanzamiento, :genero, :desarrollador, :imagen_url, :banner_url, :pegi_url, :clasificacion_1_url, :clasificacion_2_url, :clasificacion_3_url)
=======
                INSERT INTO juegos (titulo, plataforma, fecha_lanzamiento, genero, desarrollador, imagen_url, banner_url, pegi_url, clasificacion_1_url, clasificacion_2_url, clasificacion_3_url, mapa_interactivo_url)
                VALUES (:titulo, :plataforma, :fecha_lanzamiento, :genero, :desarrollador, :imagen_url, :banner_url, :pegi_url, :clasificacion_1_url, :clasificacion_2_url, :clasificacion_3_url, :mapa_interactivo_url)
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
            ");
            
            $stmt->execute([
                ':titulo' => $data['titulo'],
                ':plataforma' => $data['plataforma'],
                ':fecha_lanzamiento' => $data['fecha_lanzamiento'] ?? null,
                ':genero' => $data['genero'] ?? null,
                ':desarrollador' => $data['desarrollador'] ?? null,
                ':imagen_url' => $data['imagen_url'] ?? null,
                ':banner_url' => $data['banner_url'] ?? null,
                ':pegi_url' => $data['pegi_url'] ?? null,
                ':clasificacion_1_url' => $data['clasificacion_1_url'] ?? null,
                ':clasificacion_2_url' => $data['clasificacion_2_url'] ?? null,
<<<<<<< HEAD
                ':clasificacion_3_url' => $data['clasificacion_3_url'] ?? null
=======
                ':clasificacion_3_url' => $data['clasificacion_3_url'] ?? null,
                ':mapa_interactivo_url' => $data['mapa_interactivo_url'] ?? null
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
            ]);
            
            $gameId = $conn->lastInsertId();
            
            // Crear registro de progreso inicial
            $conn->prepare("
                INSERT INTO progreso_trofeos (videojuego_id, total_trofeos, porcentaje_completado)
                VALUES (:game_id, 0, 0.00)
            ")->execute([':game_id' => $gameId]);
            
            echo json_encode(['id' => (int)$gameId, 'message' => 'Videojuego creado correctamente']);
            break;
            
        case 'PUT':
            // Actualizar videojuego existente
            $data = json_decode(file_get_contents('php://input'), true);
            
            error_log("Datos recibidos en PUT: " . print_r($data, true));
            
            if (!$data || !isset($data['id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Faltan datos requeridos']);
                break;
            }
            
            $gameId = (int)$data['id'];
            error_log("Game ID: " . $gameId);
            
            $stmt = $conn->prepare("
                UPDATE juegos 
                SET titulo = :titulo,
                    plataforma = :plataforma,
                    fecha_lanzamiento = :fecha_lanzamiento,
                    genero = :genero,
                    desarrollador = :desarrollador,
                    imagen_url = :imagen_url,
                    banner_url = :banner_url,
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
                    pase_online = :pase_online,
                    necesario_platino = :necesario_platino,
                    trofeos_ocultos = :trofeos_ocultos,
                    min_partidas = :min_partidas,
                    trofeos_perdibles = :trofeos_perdibles,
                    trucos_afectan = :trucos_afectan,
                    dificultad_afecta = :dificultad_afecta,
                    comentario = :comentario,
                    show_clas1 = :show_clas1,
                    show_clas2 = :show_clas2,
                    show_clas3 = :show_clas3
                WHERE id = :id
            ");
            
            $stmt->execute([
                ':id' => $gameId,
                ':titulo' => $data['titulo'] ?? '',
                ':plataforma' => $data['plataforma'] ?? 'PS4',
                ':fecha_lanzamiento' => $data['fecha_lanzamiento'] ?? null,
                ':genero' => $data['genero'] ?? null,
                ':desarrollador' => $data['desarrollador'] ?? null,
                ':imagen_url' => $data['imagen_url'] ?? null,
                ':banner_url' => $data['banner_url'] ?? null,
                ':dificultad_platino' => $data['dificultad_platino'] ?? '01 sobre 10',
                ':duracion_estimada' => $data['duracion_estimada'] ?? null,
                ':trofeos_offline_platino' => $data['trofeos_offline_platino'] ?? 0,
                ':trofeos_offline_oro' => $data['trofeos_offline_oro'] ?? 0,
                ':trofeos_offline_plata' => $data['trofeos_offline_plata'] ?? 0,
                ':trofeos_offline_bronce' => $data['trofeos_offline_bronce'] ?? 0,
                ':trofeos_online_platino' => $data['trofeos_online_platino'] ?? 0,
                ':trofeos_online_oro' => $data['trofeos_online_oro'] ?? 0,
                ':trofeos_online_plata' => $data['trofeos_online_plata'] ?? 0,
                ':trofeos_online_bronce' => $data['trofeos_online_bronce'] ?? 0,
                ':pase_online' => $data['pase_online'] ?? false,
                ':necesario_platino' => $data['necesario_platino'] ?? 'NO (Pero requiere internet)',
                ':trofeos_ocultos' => $data['trofeos_ocultos'] ?? null,
                ':min_partidas' => $data['min_partidas'] ?? '1 Partida',
                ':trofeos_perdibles' => $data['trofeos_perdibles'] ?? null,
                ':trucos_afectan' => $data['trucos_afectan'] ?? false,
                ':dificultad_afecta' => $data['dificultad_afecta'] ?? false,
                ':comentario' => $data['comentario'] ?? null,
                ':show_clas1' => isset($data['show_clas1']) ? (int)$data['show_clas1'] : 1,
                ':show_clas2' => isset($data['show_clas2']) ? (int)$data['show_clas2'] : 1,
                ':show_clas3' => isset($data['show_clas3']) ? (int)$data['show_clas3'] : 1
            ]);
            
            echo json_encode(['message' => 'Videojuego actualizado correctamente']);
            break;
            
        case 'DELETE':
            // Eliminar videojuego
            if (!isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'ID de videojuego requerido']);
                break;
            }
            
            $gameId = (int)$_GET['id'];
            
            $stmt = $conn->prepare("DELETE FROM juegos WHERE id = :id");
            $stmt->execute([':id' => $gameId]);
            
            echo json_encode(['message' => 'Videojuego eliminado correctamente']);
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            break;
    }
    
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>
