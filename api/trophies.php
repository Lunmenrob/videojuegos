<?php
require_once '../config.php';

try {
    $conn = getConnection();
    $method = $_SERVER['REQUEST_METHOD'];
    
    switch ($method) {
        case 'GET':
            // Obtener trofeos de un videojuego
            if (isset($_GET['game_id'])) {
                $gameId = (int)$_GET['game_id'];
                
                $stmt = $conn->prepare("
                    SELECT t.*, 
                        CASE WHEN t.conseguido = 1 THEN 'conseguido' ELSE 'no-conseguido' END as estado
                    FROM trofeos t
                    WHERE t.videojuego_id = :game_id
                    ORDER BY t.id ASC
                ");
                $stmt->execute([':game_id' => $gameId]);
                $trophies = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Formatear datos
                foreach ($trophies as &$trophy) {
                    $trophy['id'] = (int)$trophy['id'];
                    $trophy['videojuego_id'] = (int)$trophy['videojuego_id'];
                    $trophy['conseguido'] = (bool)$trophy['conseguido'];
                    $trophy['perdible'] = (bool)$trophy['perdible'];
                    $trophy['online'] = (bool)$trophy['online'];
                    
                    // Decodificar entidades HTML en instrucciones
                    if (isset($trophy['instrucciones'])) {
                        $trophy['instrucciones'] = html_entity_decode($trophy['instrucciones'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    }
                }
                
                echo json_encode($trophies, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'ID de videojuego requerido']);
            }
            break;
            
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            
            error_log('POST trophies.php - Datos recibidos: ' . print_r($data, true));
            
            // Si hay ID, actualizar trofeo existente
            if (isset($data['id']) && !empty($data['id'])) {
                error_log('POST trophies.php - Actualizando trofeo ID: ' . $data['id']);
                
                $trophyId = (int)$data['id'];
                
                // Construir consulta dinámica
                $fields = [];
                $params = [':id' => $trophyId];
                
                if (isset($data['nombre_trofeo'])) {
                    $fields[] = 'nombre_trofeo = :nombre_trofeo';
                    $params[':nombre_trofeo'] = $data['nombre_trofeo'];
                }
                if (isset($data['descripcion'])) {
                    $fields[] = 'descripcion = :descripcion';
                    $params[':descripcion'] = $data['descripcion'];
                }
                if (isset($data['tipo'])) {
                    $tiposPermitidos = ['BRONCE', 'PLATA', 'ORO', 'PLATINO'];
                    $tipoUpper = strtoupper($data['tipo']);
                    if (in_array($tipoUpper, $tiposPermitidos)) {
                        $fields[] = 'tipo = :tipo';
                        $params[':tipo'] = $tipoUpper;
                    }
                }
                if (isset($data['instrucciones'])) {
                    $fields[] = 'instrucciones = :instrucciones';
                    $params[':instrucciones'] = $data['instrucciones'];
                }
                if (isset($data['video_url'])) {
                    $fields[] = 'video_url = :video_url';
                    $params[':video_url'] = $data['video_url'];
                }
                if (isset($data['icono_url'])) {
                    $fields[] = 'icono_url = :icono_url';
                    $params[':icono_url'] = $data['icono_url'];
                }
                if (isset($data['conseguido'])) {
                    $fields[] = 'conseguido = :conseguido';
                    $params[':conseguido'] = $data['conseguido'] ? 1 : 0;
                }
                if (isset($data['perdible'])) {
                    $fields[] = 'perdible = :perdible';
                    $params[':perdible'] = $data['perdible'] ? 1 : 0;
                }
                if (isset($data['online'])) {
                    $fields[] = 'online = :online';
                    $params[':online'] = $data['online'] ? 1 : 0;
                }
                
                if (empty($fields)) {
                    http_response_code(400);
                    echo json_encode(['error' => 'No hay campos para actualizar']);
                    break;
                }
                
                $sql = "UPDATE trofeos SET " . implode(', ', $fields) . " WHERE id = :id";
                error_log('POST trophies.php - SQL UPDATE: ' . $sql);
                error_log('POST trophies.php - Params: ' . print_r($params, true));
                
                try {
                    $stmt = $conn->prepare($sql);
                    $stmt->execute($params);
                    error_log('POST trophies.php - Rows affected: ' . $stmt->rowCount());
                } catch (PDOException $e) {
                    error_log('POST trophies.php - ERROR SQL: ' . $e->getMessage());
                    http_response_code(500);
                    echo json_encode(['error' => 'Error en base de datos: ' . $e->getMessage()]);
                    break;
                }
                
                // Obtener trofeo actualizado
                $stmt = $conn->prepare("SELECT * FROM trofeos WHERE id = :id");
                $stmt->execute([':id' => $trophyId]);
                $updatedTrophy = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$updatedTrophy) {
                    http_response_code(500);
                    echo json_encode(['error' => 'No se pudo recuperar el trofeo actualizado']);
                    break;
                }
                
                // Formatear datos
                $updatedTrophy['id'] = (int)$updatedTrophy['id'];
                $updatedTrophy['videojuego_id'] = (int)$updatedTrophy['videojuego_id'];
                $updatedTrophy['conseguido'] = (bool)$updatedTrophy['conseguido'];
                $updatedTrophy['perdible'] = (bool)$updatedTrophy['perdible'];
                $updatedTrophy['online'] = (bool)$updatedTrophy['online'];
                
                // Actualizar progreso
                try {
                    updateProgress($conn, $updatedTrophy['videojuego_id']);
                } catch (Exception $e) {
                    error_log('POST trophies.php - Error en updateProgress: ' . $e->getMessage());
                    // No fallar si updateProgress da error, solo loggear
                }
                
                // Devolver respuesta de éxito
                http_response_code(200);
                echo json_encode([
                    'success' => true,
                    'message' => 'Trofeo actualizado correctamente',
                    'data' => $updatedTrophy
                ]);
                
            } else {
                // Crear nuevo trofeo
                error_log('POST trophies.php - Creando nuevo trofeo');
                
                // Aceptar tanto videojuegos_id como videojuegos_id
                $videojuegoId = isset($data['videojuego_id']) ? $data['videojuego_id'] : (isset($data['videojuegos_id']) ? $data['videojuegos_id'] : null);
                
                // Solo el nombre es requerido
                if (!$data || !isset($data['nombre_trofeo']) || !$videojuegoId) {
                    error_log('Faltan datos requeridos');
                    http_response_code(400);
                    echo json_encode(['error' => 'Faltan datos requeridos: nombre y ID del juego', 'received' => $data]);
                    break;
                }
                
                $stmt = $conn->prepare("
                    INSERT INTO trofeos (videojuego_id, nombre_trofeo, descripcion, tipo, icono_url, instrucciones, video_url, conseguido, perdible, online)
                    VALUES (:videojuego_id, :nombre_trofeo, :descripcion, :tipo, :icono_url, :instrucciones, :video_url, :conseguido, :perdible, :online)
                ");
                
                $stmt->execute([
                    ':videojuego_id' => $videojuegoId,
                    ':nombre_trofeo' => $data['nombre_trofeo'],
                    ':descripcion' => $data['descripcion'] ?? '',
                    ':tipo' => isset($data['tipo']) ? strtoupper($data['tipo']) : 'BRONCE',
                    ':icono_url' => $data['icono_url'] ?? null,
                    ':instrucciones' => $data['instrucciones'] ?? null,
                    ':video_url' => $data['video_url'] ?? null,
                    ':conseguido' => $data['conseguido'] ? 1 : 0,
                    ':perdible' => $data['perdible'] ? 1 : 0,
                    ':online' => $data['online'] ? 1 : 0
                ]);
                
                $newId = $conn->lastInsertId();
                
                // Obtener el trofeo creado
                $stmt = $conn->prepare("SELECT * FROM trofeos WHERE id = :id");
                $stmt->execute([':id' => $newId]);
                $newTrophy = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Formatear datos
                $newTrophy['id'] = (int)$newTrophy['id'];
                $newTrophy['videojuego_id'] = (int)$newTrophy['videojuego_id'];
                $newTrophy['conseguido'] = (bool)$newTrophy['conseguido'];
                $newTrophy['perdible'] = (bool)$newTrophy['perdible'];
                $newTrophy['online'] = (bool)$newTrophy['online'];
                
                // Actualizar progreso
                updateProgress($conn, $videojuegoId);
                
                echo json_encode($newTrophy);
            }
            break;
            
        case 'PUT':
            // Actualizar trofeo existente
            $rawInput = file_get_contents('php://input');
            $data = json_decode($rawInput, true);
            
            error_log('PUT trophies.php - Raw input: ' . $rawInput);
            error_log('PUT trophies.php - Decoded data: ' . print_r($data, true));
            error_log('PUT trophies.php - GET params: ' . print_r($_GET, true));
            
            // Obtener ID desde URL o desde cuerpo
            $trophyId = isset($_GET['id']) ? (int)$_GET['id'] : null;
            if (!$trophyId && isset($data['id'])) {
                $trophyId = (int)$data['id'];
            }
            
            if (!$trophyId) {
                http_response_code(400);
                echo json_encode(['error' => 'ID de trofeo requerido']);
                break;
            }
            
            // Usar el ID obtenido
            $data['id'] = $trophyId;
            
            // Construir consulta dinámica solo con campos que se envían
            $fields = [];
            $params = [':id' => $data['id']];
            
            if (isset($data['nombre_trofeo'])) {
                $fields[] = 'nombre_trofeo = :nombre_trofeo';
                $params[':nombre_trofeo'] = $data['nombre_trofeo'];
            }
            if (isset($data['descripcion'])) {
                $fields[] = 'descripcion = :descripcion';
                $params[':descripcion'] = $data['descripcion'];
            }
            if (isset($data['tipo'])) {
                // Validar que el tipo esté en la lista permitida
                $tiposPermitidos = ['BRONCE', 'PLATA', 'ORO', 'PLATINO'];
                $tipoUpper = strtoupper($data['tipo']);
                if (!in_array($tipoUpper, $tiposPermitidos)) {
                    error_log('PUT trophies.php - Tipo inválido: [' . $data['tipo'] . ']');
                    http_response_code(400);
                    echo json_encode(['error' => 'Tipo de trofeo inválido. Valores permitidos: BRONCE, PLATA, ORO, PLATINO']);
                    break;
                }
                $fields[] = 'tipo = :tipo';
                $params[':tipo'] = $tipoUpper;
                error_log('PUT trophies.php - Tipo recibido y validado: [' . $tipoUpper . ']');
            }
            if (isset($data['instrucciones'])) {
                $fields[] = 'instrucciones = :instrucciones';
                $params[':instrucciones'] = $data['instrucciones'];
            }
            if (isset($data['video_url'])) {
                $fields[] = 'video_url = :video_url';
                $params[':video_url'] = $data['video_url'];
            }
            if (isset($data['icono_url'])) {
                $fields[] = 'icono_url = :icono_url';
                $params[':icono_url'] = $data['icono_url'];
            }
            if (isset($data['conseguido'])) {
                $fields[] = 'conseguido = :conseguido';
                $params[':conseguido'] = $data['conseguido'] ? 1 : 0;
            }
            if (isset($data['perdible'])) {
                $fields[] = 'perdible = :perdible';
                $params[':perdible'] = $data['perdible'] ? 1 : 0;
            }
            if (isset($data['online'])) {
                $fields[] = 'online = :online';
                $params[':online'] = $data['online'] ? 1 : 0;
            }
            
            if (empty($fields)) {
                http_response_code(400);
                echo json_encode(['error' => 'No hay campos para actualizar']);
                break;
            }
            
            $sql = "UPDATE trofeos SET " . implode(', ', $fields) . " WHERE id = :id";
            error_log('PUT trophies.php - SQL: ' . $sql);
            error_log('PUT trophies.php - Params: ' . print_r($params, true));
            
            try {
                $stmt = $conn->prepare($sql);
                $stmt->execute($params);
                error_log('PUT trophies.php - Rows affected: ' . $stmt->rowCount());
            } catch (PDOException $e) {
                error_log('PUT trophies.php - SQL ERROR: ' . $e->getMessage());
                http_response_code(500);
                echo json_encode(['error' => 'Error en base de datos: ' . $e->getMessage()]);
                break;
            }
            
            // Obtener el trofeo actualizado para devolverlo
            $stmt = $conn->prepare("SELECT * FROM trofeos WHERE id = :id");
            $stmt->execute([':id' => $data['id']]);
            $updatedTrophy = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$updatedTrophy) {
                error_log('PUT trophies.php - ERROR: No se pudo recuperar el trofeo actualizado con id=' . $data['id']);
                http_response_code(500);
                echo json_encode(['error' => 'No se pudo recuperar el trofeo actualizado']);
                break;
            }
            
            // Formatear datos
            $updatedTrophy['id'] = (int)$updatedTrophy['id'];
            $updatedTrophy['videojuego_id'] = (int)$updatedTrophy['videojuego_id'];
            $updatedTrophy['conseguido'] = (bool)$updatedTrophy['conseguido'];
            $updatedTrophy['perdible'] = (bool)$updatedTrophy['perdible'];
            $updatedTrophy['online'] = (bool)$updatedTrophy['online'];
            
            // Obtener game_id para actualizar progreso
            $gameId = $updatedTrophy['videojuego_id'];
            
            try {
                updateProgress($conn, $gameId);
            } catch (Exception $e) {
                error_log('PUT trophies.php - Error en updateProgress: ' . $e->getMessage());
                // No fallar si updateProgress da error
            }
            
            // Devolver respuesta de éxito
            http_response_code(200);
            error_log('PUT trophies.php - Response: ' . print_r($updatedTrophy, true));
            echo json_encode([
                'success' => true,
                'message' => 'Trofeo actualizado correctamente',
                'data' => $updatedTrophy
            ]);
            break;
            
        case 'DELETE':
            // Eliminar trofeo
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data || !isset($data['id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'ID de trofeo requerido']);
                break;
            }
            
            // Obtener game_id antes de eliminar
            $stmt = $conn->prepare("SELECT videojuego_id FROM trofeos WHERE id = :id");
            $stmt->execute([':id' => $data['id']]);
            $trophy = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$trophy) {
                http_response_code(404);
                echo json_encode(['error' => 'Trofeo no encontrado']);
                break;
            }
            
            $stmt = $conn->prepare("DELETE FROM trofeos WHERE id = :id");
            $stmt->execute([':id' => $data['id']]);
            
            // Actualizar progreso
            updateProgress($conn, $trophy['videojuego_id']);
            
            echo json_encode(['message' => 'Trofeo eliminado correctamente']);
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

function updateProgress($conn, $gameId) {
    // Obtener conteo de trofeos por tipo
    $stmt = $conn->prepare("
        SELECT 
            tipo,
            COUNT(*) as total,
            SUM(CASE WHEN conseguido = 1 THEN 1 ELSE 0 END) as conseguidos
        FROM trofeos 
        WHERE videojuego_id = :game_id
        GROUP BY tipo
    ");
    $stmt->execute([':game_id' => $gameId]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $bronce = $plata = $oro = $platino = 0;
    $bronce_conseguidos = $plata_conseguidos = $oro_conseguidos = 0;
    $platino_conseguido = false;
    
    foreach ($results as $row) {
        switch ($row['tipo']) {
            case 'BRONCE':
                $bronce = (int)$row['total'];
                $bronce_conseguidos = (int)$row['conseguidos'];
                break;
            case 'PLATA':
                $plata = (int)$row['total'];
                $plata_conseguidos = (int)$row['conseguidos'];
                break;
            case 'ORO':
                $oro = (int)$row['total'];
                $oro_conseguidos = (int)$row['conseguidos'];
                break;
            case 'PLATINO':
                $platino = (int)$row['total'];
                $platino_conseguido = (int)$row['conseguidos'] > 0;
                break;
        }
    }
    
    $total_trofeos = $bronce + $plata + $oro + $platino;
    $total_conseguidos = $bronce_conseguidos + $plata_conseguidos + $oro_conseguidos + ($platino_conseguido ? 1 : 0);
    $porcentaje = $total_trofeos > 0 ? round(($total_conseguidos / $total_trofeos) * 100, 2) : 0;
    
    // Actualizar o insertar progreso
    $stmt = $conn->prepare("
        INSERT INTO progreso_trofeos 
        (videojuego_id, total_trofeos, bronce_conseguidos, plata_conseguidos, oro_conseguidos, platino_conseguido, porcentaje_completado)
        VALUES (:game_id, :total_trofeos, :bronce_conseguidos, :plata_conseguidos, :oro_conseguidos, :platino_conseguido, :porcentaje)
        ON DUPLICATE KEY UPDATE
        total_trofeos = VALUES(total_trofeos),
        bronce_conseguidos = VALUES(bronce_conseguidos),
        plata_conseguidos = VALUES(plata_conseguidos),
        oro_conseguidos = VALUES(oro_conseguidos),
        platino_conseguido = VALUES(platino_conseguido),
        porcentaje_completado = VALUES(porcentaje_completado),
        ultima_actualizacion = CURRENT_TIMESTAMP
    ");
    
    $stmt->execute([
        ':game_id' => $gameId,
        ':total_trofeos' => $total_trofeos,
        ':bronce_conseguidos' => $bronce_conseguidos,
        ':plata_conseguidos' => $plata_conseguidos,
        ':oro_conseguidos' => $oro_conseguidos,
        ':platino_conseguido' => $platino_conseguido ? 1 : 0,
        ':porcentaje' => $porcentaje
    ]);
}
?>
