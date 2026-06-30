<?php
require_once '../config.php';

try {
    $conn = getConnection();
    $method = $_SERVER['REQUEST_METHOD'];
    
    switch ($method) {
        case 'GET':
            // Obtener trofeos de un DLC
            if (isset($_GET['dlc_id'])) {
                $dlcId = (int)$_GET['dlc_id'];
                
                $stmt = $conn->prepare("
                    SELECT * FROM trofeos_dlc
                    WHERE dlc_id = :dlc_id
                    ORDER BY id ASC
                ");
                $stmt->execute([':dlc_id' => $dlcId]);
                $trophies = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Formatear datos
                foreach ($trophies as &$trophy) {
                    $trophy['id'] = (int)$trophy['id'];
                    $trophy['dlc_id'] = (int)$trophy['dlc_id'];
                    $trophy['conseguido'] = (bool)$trophy['conseguido'];
                    $trophy['perdible'] = (bool)$trophy['perdible'];
                    $trophy['online'] = (bool)($trophy['es_online'] ?? $trophy['online'] ?? 0);
                    
                    // Decodificar entidades HTML en instrucciones
                    if (isset($trophy['instrucciones'])) {
                        $trophy['instrucciones'] = html_entity_decode($trophy['instrucciones'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    }
                }
                
                echo json_encode($trophies, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'ID de DLC requerido']);
            }
            break;
            
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Si hay ID, actualizar trofeo existente
            if (isset($data['id']) && !empty($data['id'])) {
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
                    $fields[] = 'es_online = :es_online';
                    $params[':es_online'] = $data['online'] ? 1 : 0;
                }
                
                if (empty($fields)) {
                    http_response_code(400);
                    echo json_encode(['error' => 'No hay campos para actualizar']);
                    break;
                }
                
                $sql = "UPDATE trofeos_dlc SET " . implode(', ', $fields) . " WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->execute($params);
                
                echo json_encode(['success' => true, 'id' => $trophyId]);
            } else {
                // Crear nuevo trofeo de DLC
                $stmt = $conn->prepare("
                    INSERT INTO trofeos_dlc (dlc_id, nombre_trofeo, descripcion, tipo, instrucciones, icono_url, video_url, conseguido, perdible, es_online)
                    VALUES (:dlc_id, :nombre_trofeo, :descripcion, :tipo, :instrucciones, :icono_url, :video_url, :conseguido, :perdible, :es_online)
                ");
                
                $stmt->execute([
                    ':dlc_id' => $data['dlc_id'],
                    ':nombre_trofeo' => $data['nombre_trofeo'],
                    ':descripcion' => $data['descripcion'] ?? null,
                    ':tipo' => strtoupper($data['tipo']),
                    ':instrucciones' => $data['instrucciones'] ?? null,
                    ':icono_url' => $data['icono_url'] ?? null,
                    ':video_url' => $data['video_url'] ?? null,
                    ':conseguido' => $data['conseguido'] ?? 0,
                    ':perdible' => $data['perdible'] ?? 0,
                    ':es_online' => $data['online'] ?? 0
                ]);
                
                echo json_encode(['success' => true, 'id' => $conn->lastInsertId()]);
            }
            break;
            
        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (isset($data['id'])) {
                $trophyId = (int)$data['id'];
                
                $stmt = $conn->prepare("DELETE FROM trofeos_dlc WHERE id = :id");
                $stmt->execute([':id' => $trophyId]);
                
                echo json_encode(['success' => true]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'ID de trofeo requerido']);
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
