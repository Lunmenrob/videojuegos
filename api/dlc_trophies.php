<?php // Inicio del script PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config.php'; // Incluye el archivo de configuración de la base de datos
// Incluye el middleware de autenticación
require_once '../api_auth.php';

// Verifica autenticación (permite GET sin autenticación, pero requiere auth para POST/PUT/DELETE)
requireAuth(true);

try { // Inicia bloque try para capturar excepciones
    $conn = getConnection(); // Obtiene la conexión a la base de datos
    $method = $_SERVER['REQUEST_METHOD']; // Obtiene el método HTTP de la petición (GET, POST, DELETE, etc.)
    
    switch ($method) { // Evalúa el método HTTP para ejecutar la acción correspondiente
        case 'GET': // Si el método es GET (leer datos)
            // Obtener trofeos de un DLC específico
            if (isset($_GET['dlc_id'])) { // Verifica si se proporcionó el parámetro dlc_id en la URL
                $dlcId = (int)$_GET['dlc_id']; // Convierte el ID a entero para seguridad
                
                $stmt = $conn->prepare("
                    SELECT * FROM trofeos_dlc
                    WHERE dlc_id = :dlc_id
                    ORDER BY id ASC
                ");
                $stmt->execute([':dlc_id' => $dlcId]);
                $trophies = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Formatear los datos de los trofeos
                foreach ($trophies as &$trophy) { // Recorre cada trofeo por referencia para modificarlo
                    $trophy['id'] = (int)$trophy['id']; // Convierte el ID a entero
                    $trophy['dlc_id'] = (int)$trophy['dlc_id']; // Convierte el dlc_id a entero
                    $trophy['conseguido'] = (bool)$trophy['conseguido']; // Convierte conseguido a booleano
                    $trophy['perdible'] = (bool)$trophy['perdible']; // Convierte perdible a booleano
                    $trophy['online'] = (bool)($trophy['es_online'] ?? $trophy['online'] ?? 0); // Convierte online a booleano usando operador de fusión null
                    
                    // Decodificar entidades HTML en instrucciones
                    if (isset($trophy['instrucciones'])) { // Verifica si existe el campo instrucciones
                        $trophy['instrucciones'] = html_entity_decode($trophy['instrucciones'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); // Decodifica entidades HTML a caracteres normales
                    }
                }
                
                echo json_encode($trophies, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); // Codifica los datos a JSON y los imprime
            } else { // Si no se proporcionó el dlc_id
                http_response_code(400); // Envía código de respuesta 400 (Bad Request)
                echo json_encode(['error' => 'ID de DLC requerido']); // Envía mensaje de error en JSON
            }
            break; // Sale del switch
            
        case 'POST': // Si el método es POST (crear o actualizar)
            $data = json_decode(file_get_contents('php://input'), true); // Lee y decodifica el JSON del cuerpo de la petición
            
            // Si hay ID, actualizar trofeo existente
            if (isset($data['id']) && !empty($data['id'])) { // Verifica si se proporcionó un ID para actualizar
                $trophyId = (int)$data['id']; // Convierte el ID a entero
                
                // Construir consulta dinámica
                $fields = []; // Array para almacenar los campos a actualizar
                $params = [':id' => $trophyId]; // Array de parámetros para la consulta, incluye el ID
                
                if (isset($data['nombre_trofeo'])) { // Si se proporcionó el nombre del trofeo
                    $fields[] = 'nombre_trofeo = :nombre_trofeo'; // Agrega el campo a actualizar
                    $params[':nombre_trofeo'] = $data['nombre_trofeo']; // Agrega el valor al array de parámetros
                }
                if (isset($data['descripcion'])) { // Si se proporcionó la descripción
                    $fields[] = 'descripcion = :descripcion'; // Agrega el campo a actualizar
                    $params[':descripcion'] = $data['descripcion']; // Agrega el valor al array de parámetros
                }
                if (isset($data['tipo'])) { // Si se proporcionó el tipo
                    $tiposPermitidos = ['BRONCE', 'PLATA', 'ORO', 'PLATINO']; // Define los tipos válidos
                    $tipoUpper = strtoupper($data['tipo']); // Convierte el tipo a mayúsculas
                    if (in_array($tipoUpper, $tiposPermitidos)) { // Verifica si el tipo es válido
                        $fields[] = 'tipo = :tipo'; // Agrega el campo a actualizar
                        $params[':tipo'] = $tipoUpper; // Agrega el valor al array de parámetros
                    }
                }
                if (isset($data['instrucciones'])) { // Si se proporcionaron instrucciones
                    $fields[] = 'instrucciones = :instrucciones'; // Agrega el campo a actualizar
                    $params[':instrucciones'] = $data['instrucciones']; // Agrega el valor al array de parámetros
                }
                if (isset($data['video_url'])) { // Si se proporcionó la URL del video
                    $fields[] = 'video_url = :video_url'; // Agrega el campo a actualizar
                    $params[':video_url'] = $data['video_url']; // Agrega el valor al array de parámetros
                }
                if (isset($data['icono_url'])) { // Si se proporcionó la URL del icono
                    $fields[] = 'icono_url = :icono_url'; // Agrega el campo a actualizar
                    $params[':icono_url'] = $data['icono_url']; // Agrega el valor al array de parámetros
                }
                if (isset($data['conseguido'])) { // Si se proporcionó el estado conseguido
                    $fields[] = 'conseguido = :conseguido'; // Agrega el campo a actualizar
                    $params[':conseguido'] = $data['conseguido'] ? 1 : 0; // Convierte booleano a 1 o 0
                }
                if (isset($data['perdible'])) { // Si se proporcionó el estado perdible
                    $fields[] = 'perdible = :perdible'; // Agrega el campo a actualizar
                    $params[':perdible'] = $data['perdible'] ? 1 : 0; // Convierte booleano a 1 o 0
                }
                if (isset($data['online'])) { // Si se proporcionó el estado online
                    $fields[] = 'es_online = :es_online'; // Agrega el campo a actualizar
                    $params[':es_online'] = $data['online'] ? 1 : 0; // Convierte booleano a 1 o 0
                }
                
                if (empty($fields)) { // Si no se proporcionaron campos para actualizar
                    http_response_code(400); // Envía código de respuesta 400 (Bad Request)
                    echo json_encode(['error' => 'No hay campos para actualizar']); // Envía mensaje de error en JSON
                    break; // Sale del switch
                }
                
                $sql = "UPDATE trofeos_dlc SET " . implode(', ', $fields) . " WHERE id = :id"; // Construye la consulta SQL UPDATE dinámica
                $stmt = $conn->prepare($sql); // Prepara la consulta SQL
                $stmt->execute($params); // Ejecuta la consulta con los parámetros
                
                echo json_encode(['success' => true, 'id' => $trophyId]); // Envía respuesta de éxito con el ID
            } else { // Si no se proporcionó ID, crear nuevo trofeo
                // Crear nuevo trofeo de DLC
                $stmt = $conn->prepare("
                    INSERT INTO trofeos_dlc (dlc_id, nombre_trofeo, descripcion, tipo, instrucciones, icono_url, video_url, conseguido, perdible, es_online)
                    VALUES (:dlc_id, :nombre_trofeo, :descripcion, :tipo, :instrucciones, :icono_url, :video_url, :conseguido, :perdible, :es_online)
                ");
                
                $stmt->execute([ // Ejecuta la consulta con los valores
                    ':dlc_id' => $data['dlc_id'], // ID del DLC al que pertenece el trofeo
                    ':nombre_trofeo' => $data['nombre_trofeo'], // Nombre del trofeo
                    ':descripcion' => $data['descripcion'] ?? null, // Descripción (o null si no se proporciona)
                    ':tipo' => strtoupper($data['tipo']), // Tipo en mayúsculas
                    ':instrucciones' => $data['instrucciones'] ?? null, // Instrucciones (o null si no se proporciona)
                    ':icono_url' => $data['icono_url'] ?? null, // URL del icono (o null si no se proporciona)
                    ':video_url' => $data['video_url'] ?? null, // URL del video (o null si no se proporciona)
                    ':conseguido' => $data['conseguido'] ?? 0, // Estado conseguido (0 por defecto)
                    ':perdible' => $data['perdible'] ?? 0, // Estado perdible (0 por defecto)
                    ':es_online' => $data['online'] ?? 0 // Estado online (0 por defecto)
                ]);
                
                echo json_encode(['success' => true, 'id' => $conn->lastInsertId()]); // Envía respuesta de éxito con el ID generado
            }
            break; // Sale del switch
            
        case 'DELETE': // Si el método es DELETE (eliminar)
            $data = json_decode(file_get_contents('php://input'), true); // Lee y decodifica el JSON del cuerpo de la petición
            
            if (isset($data['id'])) { // Verifica si se proporcionó el ID
                $trophyId = (int)$data['id']; // Convierte el ID a entero
                
                $stmt = $conn->prepare("DELETE FROM trofeos_dlc WHERE id = :id"); // Prepara la consulta SQL DELETE
                $stmt->execute([':id' => $trophyId]); // Ejecuta la consulta con el ID
                
                echo json_encode(['success' => true]); // Envía respuesta de éxito
            } else { // Si no se proporcionó el ID
                http_response_code(400); // Envía código de respuesta 400 (Bad Request)
                echo json_encode(['error' => 'ID de trofeo requerido']); // Envía mensaje de error en JSON
            }
            break; // Sale del switch
            
        default: // Si el método no es ninguno de los anteriores
            http_response_code(405); // Envía código de respuesta 405 (Method Not Allowed)
            echo json_encode(['error' => 'Método no permitido']); // Envía mensaje de error en JSON
            break; // Sale del switch
    } // Cierra el switch
} catch (Exception $e) { // Captura cualquier excepción ocurrida
    http_response_code(500); // Envía código de respuesta 500 (Internal Server Error)
    echo json_encode(['error' => $e->getMessage()]); // Envía el mensaje de la excepción en JSON
} // Cierra el bloque catch
