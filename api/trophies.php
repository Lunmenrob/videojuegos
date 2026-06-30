<?php
// Incluye el archivo de configuración
require_once '../config.php';

try {
    // Obtiene la conexión a la base de datos
    $conn = getConnection();
    // Obtiene el método HTTP de la solicitud
    $method = $_SERVER['REQUEST_METHOD'];
    
    switch ($method) {
        case 'GET':
            // Obtener trofeos de un videojuego
            if (isset($_GET['game_id'])) {
                // Convierte el ID a entero
                $gameId = (int)$_GET['game_id'];
                
                // Log para depuración
                error_log("GET trophies.php - game_id: $gameId");
                
                // Prepara la consulta para obtener los trofeos del juego
                $stmt = $conn->prepare("
                    SELECT t.*, 
                        CASE WHEN t.conseguido = 1 THEN 'conseguido' ELSE 'no-conseguido' END as estado
                    FROM trofeos t
                    WHERE t.videojuego_id = :game_id
                    ORDER BY t.id ASC
                ");
                $stmt->execute([':game_id' => $gameId]);
                // Obtiene todos los trofeos
                $trophies = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Log para depuración
                error_log("GET trophies.php - Trofeos encontrados: " . count($trophies));
                
                // Formatear datos
                foreach ($trophies as &$trophy) {
                    $trophy['id'] = (int)$trophy['id'];
                    $trophy['videojuego_id'] = (int)$trophy['videojuego_id'];
                    $trophy['conseguido'] = (bool)$trophy['conseguido'];
                    $trophy['perdible'] = (bool)$trophy['perdible'];
                    $trophy['online'] = (bool)($trophy['es_online'] ?? $trophy['online'] ?? 0);
                    
                    // Decodificar entidades HTML en instrucciones
                    if (isset($trophy['instrucciones'])) {
                        $trophy['instrucciones'] = html_entity_decode($trophy['instrucciones'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    }
                }
                
                // Log para depuración
                error_log("GET trophies.php - JSON response: " . json_encode($trophies, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                
                // Retorna los trofeos en formato JSON
                echo json_encode($trophies, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            } else {
                // Si no se proporciona game_id, retorna error
                error_log("GET trophies.php - ERROR: game_id no proporcionado");
                http_response_code(400);
                echo json_encode(['error' => 'ID de videojuego requerido']);
            }
            break;
            
        case 'POST':
            // Decodifica el JSON del cuerpo de la solicitud
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Log para depuración
            error_log('POST trophies.php - Datos recibidos: ' . print_r($data, true));
            
            // Si hay ID, actualizar trofeo existente
            if (isset($data['id']) && !empty($data['id'])) {
                // Log para depuración
                error_log('POST trophies.php - Actualizando trofeo ID: ' . $data['id']);
                
                // Convierte el ID a entero
                $trophyId = (int)$data['id'];
                
                // Construir consulta dinámica
                $fields = [];
                $params = [':id' => $trophyId];
                
                // Agrega campos a actualizar si están presentes
                if (isset($data['nombre_trofeo'])) {
                    $fields[] = 'nombre_trofeo = :nombre_trofeo';
                    $params[':nombre_trofeo'] = $data['nombre_trofeo'];
                }
                if (isset($data['descripcion'])) {
                    $fields[] = 'descripcion = :descripcion';
                    $params[':descripcion'] = $data['descripcion'];
                }
                if (isset($data['tipo'])) {
                    // Valida que el tipo esté en la lista permitida
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
                    $fields[] = 'es_online = :es_online';
                    $params[':es_online'] = $data['online'] ? 1 : 0;
                }
                
                // Si no hay campos para actualizar, retorna error
                if (empty($fields)) {
                    http_response_code(400);
                    echo json_encode(['error' => 'No hay campos para actualizar']);
                    break;
                }
                
                // Construye la consulta SQL de actualización
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
                
                // Si no se pudo recuperar el trofeo, retorna error
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
                $updatedTrophy['online'] = (bool)($updatedTrophy['es_online'] ?? $updatedTrophy['online'] ?? 0);
                
                // Actualizar progreso del juego
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
                
                // Aceptar tanto videojuegos_id como videojuego_id (compatibilidad)
                $videojuegoId = isset($data['videojuego_id']) ? $data['videojuego_id'] : (isset($data['videojuegos_id']) ? $data['videojuegos_id'] : null);
                
                // Valida que los datos requeridos estén presentes
                if (!$data || !isset($data['nombre_trofeo']) || !$videojuegoId) {
                    error_log('Faltan datos requeridos');
                    http_response_code(400);
                    echo json_encode(['error' => 'Faltan datos requeridos: nombre y ID del juego', 'received' => $data]);
                    break;
                }
                
                // Prepara la consulta para insertar el nuevo trofeo
                $stmt = $conn->prepare("
                    INSERT INTO trofeos (videojuego_id, nombre_trofeo, descripcion, tipo, icono_url, instrucciones, video_url, conseguido, perdible, es_online)
                    VALUES (:videojuego_id, :nombre_trofeo, :descripcion, :tipo, :icono_url, :instrucciones, :video_url, :conseguido, :perdible, :es_online)
                ");
                
                // Ejecuta la inserción con los parámetros
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
                    ':es_online' => $data['online'] ? 1 : 0
                ]);
                
                // Obtiene el ID del trofeo insertado
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
                $newTrophy['online'] = (bool)($newTrophy['es_online'] ?? $newTrophy['online'] ?? 0);
                
                // Actualizar progreso del juego
                updateProgress($conn, $videojuegoId);
                
                // Retorna el trofeo creado
                echo json_encode($newTrophy);
            }
            break;
            
        case 'PUT':
            // Actualizar trofeo existente
            $rawInput = file_get_contents('php://input');
            $data = json_decode($rawInput, true);
            
            // Logs para depuración
            error_log('PUT trophies.php - Raw input: ' . $rawInput);
            error_log('PUT trophies.php - Decoded data: ' . print_r($data, true));
            error_log('PUT trophies.php - GET params: ' . print_r($_GET, true));
            
            // Obtener ID desde URL o desde cuerpo
            $trophyId = isset($_GET['id']) ? (int)$_GET['id'] : null;
            if (!$trophyId && isset($data['id'])) {
                $trophyId = (int)$data['id'];
            }
            
            // Valida que se proporcione el ID
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
            
            // Agrega campos a actualizar si están presentes
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
                $fields[] = 'es_online = :es_online';
                $params[':es_online'] = $data['online'] ? 1 : 0;
            }
            
            // Si no hay campos para actualizar, retorna error
            if (empty($fields)) {
                http_response_code(400);
                echo json_encode(['error' => 'No hay campos para actualizar']);
                break;
            }
            
            // Construye la consulta SQL de actualización
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
            
            // Si no se pudo recuperar el trofeo, retorna error
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
            $updatedTrophy['online'] = (bool)($updatedTrophy['es_online'] ?? $updatedTrophy['online'] ?? 0);
            
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
            
            // Valida que se proporcione el ID
            if (!$data || !isset($data['id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'ID de trofeo requerido']);
                break;
            }
            
            // Obtener game_id antes de eliminar
            $stmt = $conn->prepare("SELECT videojuego_id FROM trofeos WHERE id = :id");
            $stmt->execute([':id' => $data['id']]);
            $trophy = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Si no se encuentra el trofeo, retorna error 404
            if (!$trophy) {
                http_response_code(404);
                echo json_encode(['error' => 'Trofeo no encontrado']);
                break;
            }
            
            // Elimina el trofeo
            $stmt = $conn->prepare("DELETE FROM trofeos WHERE id = :id");
            $stmt->execute([':id' => $data['id']]);
            
            // Actualizar progreso del juego
            updateProgress($conn, $trophy['videojuego_id']);
            
            // Retorna mensaje de éxito
            echo json_encode(['message' => 'Trofeo eliminado correctamente']);
            break;
            
        default:
            // Método no permitido
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            break;
    }
    
} catch(PDOException $e) {
    // Captura errores de base de datos
    http_response_code(500);
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}

// Función para actualizar el progreso de trofeos de un juego
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
    
    // Inicializa contadores
    $bronce = $plata = $oro = $platino = 0;
    $bronce_conseguidos = $plata_conseguidos = $oro_conseguidos = 0;
    $platino_conseguido = false;
    
    // Itera sobre los resultados para asignar contadores
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
    
    // Calcula el total de trofeos y conseguidos
    $total_trofeos = $bronce + $plata + $oro + $platino;
    $total_conseguidos = $bronce_conseguidos + $plata_conseguidos + $oro_conseguidos + ($platino_conseguido ? 1 : 0);
    // Calcula el porcentaje de completado
    $porcentaje = $total_trofeos > 0 ? round(($total_conseguidos / $total_trofeos) * 100, 2) : 0;
    
    // Actualizar o insertar progreso en la base de datos
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
    
    // Ejecuta la consulta con los parámetros
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
