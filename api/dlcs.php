<?php
require_once '../config.php';

try {
    $conn = getConnection();
    $method = $_SERVER['REQUEST_METHOD'];
    
    switch ($method) {
        case 'GET':
            // Obtener DLCs de un videojuego
            if (isset($_GET['game_id'])) {
                $gameId = (int)$_GET['game_id'];
                
                $stmt = $conn->prepare("
                    SELECT * FROM dlcs
                    WHERE videojuego_id = :game_id
                    ORDER BY id ASC
                ");
                $stmt->execute([':game_id' => $gameId]);
                $dlcs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Formatear datos
                foreach ($dlcs as &$dlc) {
                    $dlc['id'] = (int)$dlc['id'];
                    $dlc['videojuego_id'] = (int)$dlc['videojuego_id'];
                    
                    // Decodificar entidades HTML en descripción y trofeos_perdibles
                    if (isset($dlc['descripcion'])) {
                        $dlc['descripcion'] = html_entity_decode($dlc['descripcion'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    }
                    if (isset($dlc['trofeos_perdibles'])) {
                        $dlc['trofeos_perdibles'] = html_entity_decode($dlc['trofeos_perdibles'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    }
                }
                
                echo json_encode($dlcs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'ID de videojuego requerido']);
            }
            break;
            
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Si hay ID, actualizar DLC existente
            if (isset($data['id']) && !empty($data['id'])) {
                $dlcId = (int)$data['id'];
                
                // Construir consulta dinámica
                $fields = [];
                $params = [':id' => $dlcId];
                
                if (isset($data['nombre'])) {
                    $fields[] = 'nombre = :nombre';
                    $params[':nombre'] = $data['nombre'];
                }

                if (isset($data['fecha_lanzamiento'])) {
                    $fields[] = 'fecha_lanzamiento = :fecha_lanzamiento';
                    $params[':fecha_lanzamiento'] = $data['fecha_lanzamiento'] ?: null;
                }
                if (isset($data['descripcion'])) {
                    $fields[] = 'descripcion = :descripcion';
                    $params[':descripcion'] = $data['descripcion'];
                }
                if (isset($data['imagen_url'])) {
                    $fields[] = 'imagen_url = :imagen_url';
                    $params[':imagen_url'] = $data['imagen_url'];
                }
                if (isset($data['banner_url'])) {
                    $fields[] = 'banner_url = :banner_url';
                    $params[':banner_url'] = $data['banner_url'];
                }
                if (isset($data['dificultad_platino'])) {
                    $fields[] = 'dificultad_platino = :dificultad_platino';
                    $params[':dificultad_platino'] = $data['dificultad_platino'];
                }
                if (isset($data['duracion_estimada'])) {
                    $fields[] = 'duracion_estimada = :duracion_estimada';
                    $params[':duracion_estimada'] = $data['duracion_estimada'];
                }
                if (isset($data['trofeos_offline_oro'])) {
                    $fields[] = 'trofeos_offline_oro = :trofeos_offline_oro';
                    $params[':trofeos_offline_oro'] = $data['trofeos_offline_oro'];
                }
                if (isset($data['trofeos_offline_plata'])) {
                    $fields[] = 'trofeos_offline_plata = :trofeos_offline_plata';
                    $params[':trofeos_offline_plata'] = $data['trofeos_offline_plata'];
                }
                if (isset($data['trofeos_offline_bronce'])) {
                    $fields[] = 'trofeos_offline_bronce = :trofeos_offline_bronce';
                    $params[':trofeos_offline_bronce'] = $data['trofeos_offline_bronce'];
                }
                if (isset($data['trofeos_online_oro'])) {
                    $fields[] = 'trofeos_online_oro = :trofeos_online_oro';
                    $params[':trofeos_online_oro'] = $data['trofeos_online_oro'];
                }
                if (isset($data['trofeos_online_plata'])) {
                    $fields[] = 'trofeos_online_plata = :trofeos_online_plata';
                    $params[':trofeos_online_plata'] = $data['trofeos_online_plata'];
                }
                if (isset($data['trofeos_online_bronce'])) {
                    $fields[] = 'trofeos_online_bronce = :trofeos_online_bronce';
                    $params[':trofeos_online_bronce'] = $data['trofeos_online_bronce'];
                }
                if (isset($data['trofeos_perdibles'])) {
                    $fields[] = 'trofeos_perdibles = :trofeos_perdibles';
                    $params[':trofeos_perdibles'] = $data['trofeos_perdibles'];
                }
                
                if (empty($fields)) {
                    http_response_code(400);
                    echo json_encode(['error' => 'No hay campos para actualizar']);
                    break;
                }
                
                $sql = "UPDATE dlcs SET " . implode(', ', $fields) . " WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->execute($params);
                
                echo json_encode(['success' => true, 'id' => $dlcId]);
            } else {
                // Crear nuevo DLC
                $stmt = $conn->prepare("
                    INSERT INTO dlcs (videojuego_id, nombre, fecha_lanzamiento, descripcion, imagen_url, banner_url, dificultad_platino, duracion_estimada, trofeos_offline_oro, trofeos_offline_plata, trofeos_offline_bronce, trofeos_online_oro, trofeos_online_plata, trofeos_online_bronce, trofeos_perdibles)
                    VALUES (:videojuego_id, :nombre, :fecha_lanzamiento, :descripcion, :imagen_url, :banner_url, :dificultad_platino, :duracion_estimada, :trofeos_offline_oro, :trofeos_offline_plata, :trofeos_offline_bronce, :trofeos_online_oro, :trofeos_online_plata, :trofeos_online_bronce, :trofeos_perdibles)
                ");
                
                $stmt->execute([
                    ':videojuego_id' => $data['videojuego_id'],
                    ':nombre' => $data['nombre'],
                    ':fecha_lanzamiento' => !empty($data['fecha_lanzamiento']) ? $data['fecha_lanzamiento'] : null,
                    ':descripcion' => $data['descripcion'] ?? null,
                    ':imagen_url' => $data['imagen_url'] ?? null,
                    ':banner_url' => $data['banner_url'] ?? null,
                    ':dificultad_platino' => $data['dificultad_platino'] ?? null,
                    ':duracion_estimada' => $data['duracion_estimada'] ?? null,
                    ':trofeos_offline_oro' => !empty($data['trofeos_offline_oro']) ? (int)$data['trofeos_offline_oro'] : 0,
                    ':trofeos_offline_plata' => !empty($data['trofeos_offline_plata']) ? (int)$data['trofeos_offline_plata'] : 0,
                    ':trofeos_offline_bronce' => !empty($data['trofeos_offline_bronce']) ? (int)$data['trofeos_offline_bronce'] : 0,
                    ':trofeos_online_oro' => !empty($data['trofeos_online_oro']) ? (int)$data['trofeos_online_oro'] : 0,
                    ':trofeos_online_plata' => !empty($data['trofeos_online_plata']) ? (int)$data['trofeos_online_plata'] : 0,
                    ':trofeos_online_bronce' => !empty($data['trofeos_online_bronce']) ? (int)$data['trofeos_online_bronce'] : 0,
                    ':trofeos_perdibles' => $data['trofeos_perdibles'] ?? null
                ]);
                
                echo json_encode(['success' => true, 'id' => $conn->lastInsertId()]);
            }
            break;
            
        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (isset($data['id'])) {
                $dlcId = (int)$data['id'];
                
                // Primero eliminar trofeos del DLC
                $stmt = $conn->prepare("DELETE FROM trofeos_dlc WHERE dlc_id = :dlc_id");
                $stmt->execute([':dlc_id' => $dlcId]);
                
                // Luego eliminar el DLC
                $stmt = $conn->prepare("DELETE FROM dlcs WHERE id = :id");
                $stmt->execute([':id' => $dlcId]);
                
                echo json_encode(['success' => true]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'ID de DLC requerido']);
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
