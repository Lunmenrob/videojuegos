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
            // Obtener DLCs de un videojuego específico
            if (isset($_GET['game_id'])) { // Verifica si se proporcionó el parámetro game_id en la URL
                $gameId = (int)$_GET['game_id']; // Convierte el ID a entero para seguridad
                
                $stmt = $conn->prepare("
                    SELECT * FROM dlcs
                    WHERE videojuego_id = :game_id
                    ORDER BY id ASC
                ");
                $stmt->execute([':game_id' => $gameId]);
                $dlcs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Formatear los datos de los DLCs
                foreach ($dlcs as &$dlc) { // Recorre cada DLC por referencia para modificarlo
                    $dlc['id'] = (int)$dlc['id']; // Convierte el ID a entero
                    $dlc['videojuego_id'] = (int)$dlc['videojuego_id']; // Convierte el videojuego_id a entero
                    
                    // Decodificar entidades HTML en descripción y trofeos_perdibles
                    if (isset($dlc['descripcion'])) { // Verifica si existe el campo descripción
                        $dlc['descripcion'] = html_entity_decode($dlc['descripcion'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); // Decodifica entidades HTML a caracteres normales
                    }
                    if (isset($dlc['trofeos_perdibles'])) { // Verifica si existe el campo trofeos_perdibles
                        $dlc['trofeos_perdibles'] = html_entity_decode($dlc['trofeos_perdibles'], ENT_QUOTES | ENT_HTML5, 'UTF-8'); // Decodifica entidades HTML a caracteres normales
                    }
                } // Cierra el foreach
                
                echo json_encode($dlcs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); // Codifica los datos a JSON y los imprime
            } else { // Si no se proporcionó el game_id
                http_response_code(400); // Envía código de respuesta 400 (Bad Request)
                echo json_encode(['error' => 'ID de videojuego requerido']); // Envía mensaje de error en JSON
            }
            break; // Sale del switch
            
        case 'POST': // Si el método es POST (crear o actualizar)
            $data = json_decode(file_get_contents('php://input'), true); // Lee y decodifica el JSON del cuerpo de la petición
            
            // Si hay ID, actualizar DLC existente
            if (isset($data['id']) && !empty($data['id'])) { // Verifica si se proporcionó un ID para actualizar
                $dlcId = (int)$data['id']; // Convierte el ID a entero
                
                // Construir consulta dinámica
                $fields = []; // Array para almacenar los campos a actualizar
                $params = [':id' => $dlcId]; // Array de parámetros para la consulta, incluye el ID
                
                if (isset($data['nombre'])) { // Si se proporcionó el nombre
                    $fields[] = 'nombre = :nombre'; // Agrega el campo a actualizar
                    $params[':nombre'] = $data['nombre']; // Agrega el valor al array de parámetros
                }

                if (isset($data['fecha_lanzamiento'])) { // Si se proporcionó la fecha de lanzamiento
                    $fields[] = 'fecha_lanzamiento = :fecha_lanzamiento'; // Agrega el campo a actualizar
                    $params[':fecha_lanzamiento'] = $data['fecha_lanzamiento'] ?: null; // Usa null si está vacío
                }
                if (isset($data['descripcion'])) { // Si se proporcionó la descripción
                    $fields[] = 'descripcion = :descripcion'; // Agrega el campo a actualizar
                    $params[':descripcion'] = $data['descripcion']; // Agrega el valor al array de parámetros
                }
                if (isset($data['imagen_url'])) { // Si se proporcionó la URL de la imagen
                    $fields[] = 'imagen_url = :imagen_url'; // Agrega el campo a actualizar
                    $params[':imagen_url'] = $data['imagen_url']; // Agrega el valor al array de parámetros
                }
                if (isset($data['banner_url'])) { // Si se proporcionó la URL del banner
                    $fields[] = 'banner_url = :banner_url'; // Agrega el campo a actualizar
                    $params[':banner_url'] = $data['banner_url']; // Agrega el valor al array de parámetros
                }
                if (isset($data['dificultad_platino'])) { // Si se proporcionó la dificultad del platino
                    $fields[] = 'dificultad_platino = :dificultad_platino'; // Agrega el campo a actualizar
                    $params[':dificultad_platino'] = $data['dificultad_platino']; // Agrega el valor al array de parámetros
                }
                if (isset($data['duracion_estimada'])) { // Si se proporcionó la duración estimada
                    $fields[] = 'duracion_estimada = :duracion_estimada'; // Agrega el campo a actualizar
                    $params[':duracion_estimada'] = $data['duracion_estimada']; // Agrega el valor al array de parámetros
                }
                if (isset($data['trofeos_offline_oro'])) { // Si se proporcionaron trofeos offline oro
                    $fields[] = 'trofeos_offline_oro = :trofeos_offline_oro'; // Agrega el campo a actualizar
                    $params[':trofeos_offline_oro'] = $data['trofeos_offline_oro']; // Agrega el valor al array de parámetros
                }
                if (isset($data['trofeos_offline_plata'])) { // Si se proporcionaron trofeos offline plata
                    $fields[] = 'trofeos_offline_plata = :trofeos_offline_plata'; // Agrega el campo a actualizar
                    $params[':trofeos_offline_plata'] = $data['trofeos_offline_plata']; // Agrega el valor al array de parámetros
                }
                if (isset($data['trofeos_offline_bronce'])) { // Si se proporcionaron trofeos offline bronce
                    $fields[] = 'trofeos_offline_bronce = :trofeos_offline_bronce'; // Agrega el campo a actualizar
                    $params[':trofeos_offline_bronce'] = $data['trofeos_offline_bronce']; // Agrega el valor al array de parámetros
                }
                if (isset($data['trofeos_online_oro'])) { // Si se proporcionaron trofeos online oro
                    $fields[] = 'trofeos_online_oro = :trofeos_online_oro'; // Agrega el campo a actualizar
                    $params[':trofeos_online_oro'] = $data['trofeos_online_oro']; // Agrega el valor al array de parámetros
                }
                if (isset($data['trofeos_online_plata'])) { // Si se proporcionaron trofeos online plata
                    $fields[] = 'trofeos_online_plata = :trofeos_online_plata'; // Agrega el campo a actualizar
                    $params[':trofeos_online_plata'] = $data['trofeos_online_plata']; // Agrega el valor al array de parámetros
                }
                if (isset($data['trofeos_online_bronce'])) { // Si se proporcionaron trofeos online bronce
                    $fields[] = 'trofeos_online_bronce = :trofeos_online_bronce'; // Agrega el campo a actualizar
                    $params[':trofeos_online_bronce'] = $data['trofeos_online_bronce']; // Agrega el valor al array de parámetros
                }
                if (isset($data['trofeos_perdibles'])) { // Si se proporcionaron trofeos perdibles
                    $fields[] = 'trofeos_perdibles = :trofeos_perdibles'; // Agrega el campo a actualizar
                    $params[':trofeos_perdibles'] = $data['trofeos_perdibles']; // Agrega el valor al array de parámetros
                }
                
                if (empty($fields)) { // Si no se proporcionaron campos para actualizar
                    http_response_code(400); // Envía código de respuesta 400 (Bad Request)
                    echo json_encode(['error' => 'No hay campos para actualizar']); // Envía mensaje de error en JSON
                    break; // Sale del switch
                }
                
                $sql = "UPDATE dlcs SET " . implode(', ', $fields) . " WHERE id = :id"; // Construye la consulta SQL UPDATE dinámica
                $stmt = $conn->prepare($sql); // Prepara la consulta SQL
                $stmt->execute($params); // Ejecuta la consulta con los parámetros
                
                echo json_encode(['success' => true, 'id' => $dlcId]); // Envía respuesta de éxito con el ID
            } else { // Si no se proporcionó ID, crear nuevo DLC
                // Crear nuevo DLC
                $stmt = $conn->prepare("
                    INSERT INTO dlcs (videojuego_id, nombre, fecha_lanzamiento, descripcion, imagen_url, banner_url, dificultad_platino, duracion_estimada, trofeos_offline_oro, trofeos_offline_plata, trofeos_offline_bronce, trofeos_online_oro, trofeos_online_plata, trofeos_online_bronce, trofeos_perdibles)
                    VALUES (:videojuego_id, :nombre, :fecha_lanzamiento, :descripcion, :imagen_url, :banner_url, :dificultad_platino, :duracion_estimada, :trofeos_offline_oro, :trofeos_offline_plata, :trofeos_offline_bronce, :trofeos_online_oro, :trofeos_online_plata, :trofeos_online_bronce, :trofeos_perdibles)
                ");
                
                $stmt->execute([ // Ejecuta la consulta con los valores
                    ':videojuego_id' => $data['videojuego_id'], // ID del videojuego al que pertenece el DLC
                    ':nombre' => $data['nombre'], // Nombre del DLC
                    ':fecha_lanzamiento' => !empty($data['fecha_lanzamiento']) ? $data['fecha_lanzamiento'] : null, // Fecha de lanzamiento (o null si está vacío)
                    ':descripcion' => $data['descripcion'] ?? null, // Descripción (o null si no se proporciona)
                    ':imagen_url' => $data['imagen_url'] ?? null, // URL de la imagen (o null si no se proporciona)
                    ':banner_url' => $data['banner_url'] ?? null, // URL del banner (o null si no se proporciona)
                    ':dificultad_platino' => $data['dificultad_platino'] ?? null, // Dificultad del platino (o null si no se proporciona)
                    ':duracion_estimada' => $data['duracion_estimada'] ?? null, // Duración estimada (o null si no se proporciona)
                    ':trofeos_offline_oro' => !empty($data['trofeos_offline_oro']) ? (int)$data['trofeos_offline_oro'] : 0, // Trofeos offline oro (0 por defecto)
                    ':trofeos_offline_plata' => !empty($data['trofeos_offline_plata']) ? (int)$data['trofeos_offline_plata'] : 0, // Trofeos offline plata (0 por defecto)
                    ':trofeos_offline_bronce' => !empty($data['trofeos_offline_bronce']) ? (int)$data['trofeos_offline_bronce'] : 0, // Trofeos offline bronce (0 por defecto)
                    ':trofeos_online_oro' => !empty($data['trofeos_online_oro']) ? (int)$data['trofeos_online_oro'] : 0, // Trofeos online oro (0 por defecto)
                    ':trofeos_online_plata' => !empty($data['trofeos_online_plata']) ? (int)$data['trofeos_online_plata'] : 0, // Trofeos online plata (0 por defecto)
                    ':trofeos_online_bronce' => !empty($data['trofeos_online_bronce']) ? (int)$data['trofeos_online_bronce'] : 0, // Trofeos online bronce (0 por defecto)
                    ':trofeos_perdibles' => $data['trofeos_perdibles'] ?? null // Trofeos perdibles (o null si no se proporciona)
                ]);
                
                echo json_encode(['success' => true, 'id' => $conn->lastInsertId()]); // Envía respuesta de éxito con el ID generado
            }
            break; // Sale del switch
            
        case 'DELETE': // Si el método es DELETE (eliminar)
            $data = json_decode(file_get_contents('php://input'), true); // Lee y decodifica el JSON del cuerpo de la petición
            
            if (isset($data['id'])) { // Verifica si se proporcionó el ID
                $dlcId = (int)$data['id']; // Convierte el ID a entero
                
                // Primero eliminar trofeos del DLC
                $stmt = $conn->prepare("DELETE FROM trofeos_dlc WHERE dlc_id = :dlc_id"); // Prepara la consulta SQL DELETE para trofeos
                $stmt->execute([':dlc_id' => $dlcId]); // Ejecuta la consulta con el ID del DLC
                
                // Luego eliminar el DLC
                $stmt = $conn->prepare("DELETE FROM dlcs WHERE id = :id"); // Prepara la consulta SQL DELETE para el DLC
                $stmt->execute([':id' => $dlcId]); // Ejecuta la consulta con el ID
                
                echo json_encode(['success' => true]); // Envía respuesta de éxito
            } else { // Si no se proporcionó el ID
                http_response_code(400); // Envía código de respuesta 400 (Bad Request)
                echo json_encode(['error' => 'ID de DLC requerido']); // Envía mensaje de error en JSON
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
