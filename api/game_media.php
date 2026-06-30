<?php
// Incluye el archivo de configuración
require_once '../config.php';
// Incluye el middleware de autenticación
require_once '../api_auth.php';

// Verifica autenticación (permite GET sin autenticación, pero requiere auth para POST/PUT/DELETE)
requireAuth(true);

// Función para obtener conexión a la base de datos
function getConnectionForMedia() {
    // Retorna la conexión
    return getConnection();
}

try {
    // Obtiene la conexión a la base de datos
    $conn = getConnectionForMedia();
    // Obtiene el método HTTP de la solicitud
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            // Si se proporciona game_id, obtiene media del juego
            if (isset($_GET['game_id'])) {
                // Convierte el ID a entero
                $gameId = (int)$_GET['game_id'];
                // Prepara la consulta para obtener media del juego
                $stmt = $conn->prepare(
                    "SELECT id, videojuego_id, tipo, url, orden FROM media_juegos WHERE videojuego_id = :game_id ORDER BY orden ASC, id ASC"
                );
                $stmt->execute([':game_id' => $gameId]);
                // Retorna los resultados en JSON
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                break;
            }

            // Si se proporciona dlc_id, obtiene media del DLC
            if (isset($_GET['dlc_id'])) {
                // Convierte el ID a entero
                $dlcId = (int)$_GET['dlc_id'];
                // Prepara la consulta para obtener media del DLC
                $stmt = $conn->prepare(
                    "SELECT id, dlc_id, tipo, url, orden FROM media_dlcs WHERE dlc_id = :dlc_id ORDER BY orden ASC, id ASC"
                );
                $stmt->execute([':dlc_id' => $dlcId]);
                // Retorna los resultados en JSON
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                break;
            }

            // Si no se proporciona ningún ID, retorna error
            http_response_code(400);
            echo json_encode(['error' => 'ID de juego o DLC requerido']);
            break;

        case 'POST':
            // Verificar si es una subida de archivo (FormData)
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                // Obtiene el archivo subido
                $file = $_FILES['file'];
                // Obtiene el tipo de media (image por defecto)
                $tipo = $_POST['tipo'] ?? 'image';
                // Obtiene el ID del DLC si existe
                $dlc_id = isset($_POST['dlc_id']) ? (int)$_POST['dlc_id'] : null;
                // Obtiene el ID del juego si existe
                $game_id = isset($_POST['game_id']) ? (int)$_POST['game_id'] : null;

                // Crear directorio de uploads si no existe
                $uploadDir = '../uploads/media/';
                if (!is_dir($uploadDir)) {
                    // Crea el directorio con permisos 0755
                    mkdir($uploadDir, 0755, true);
                }

                // Generar nombre único para el archivo
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = uniqid('media_', true) . '.' . $extension;
                $filepath = $uploadDir . $filename;

                // Mover el archivo al directorio de uploads
                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    // Genera la URL del archivo
                    $url = 'uploads/media/' . $filename;

                    // Si es media de juego, inserta en media_juegos
                    if ($game_id) {
                        $stmt = $conn->prepare(
                            "INSERT INTO media_juegos (videojuego_id, tipo, url, orden) VALUES (:videojuego_id, :tipo, :url, :orden)"
                        );
                        $stmt->execute([
                            ':videojuego_id' => $game_id,
                            ':tipo' => $tipo,
                            ':url' => $url,
                            ':orden' => 0
                        ]);
                    } elseif ($dlc_id) {
                        // Si es media de DLC, inserta en media_dlcs
                        $stmt = $conn->prepare(
                            "INSERT INTO media_dlcs (dlc_id, tipo, url, orden) VALUES (:dlc_id, :tipo, :url, :orden)"
                        );
                        $stmt->execute([
                            ':dlc_id' => $dlc_id,
                            ':tipo' => $tipo,
                            ':url' => $url,
                            ':orden' => 0
                        ]);
                    } else {
                        // Si no se proporciona game_id ni dlc_id, retorna error
                        http_response_code(400);
                        echo json_encode(['error' => 'Debe indicarse game_id o dlc_id']);
                        break;
                    }

                    // Retorna éxito con el ID y URL del archivo
                    echo json_encode([
                        'id' => (int)$conn->lastInsertId(),
                        'url' => $url,
                        'message' => 'Archivo subido correctamente'
                    ]);
                } else {
                    // Si falla al mover el archivo, retorna error
                    http_response_code(500);
                    echo json_encode(['error' => 'Error al mover el archivo']);
                }
                break;
            }

            // Procesar como JSON (URL)
            $data = json_decode(file_get_contents('php://input'), true);
            // Valida que los datos requeridos estén presentes
            if (!$data || !isset($data['tipo']) || !isset($data['url'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Faltan datos requeridos']);
                break;
            }

            // Si es media de juego, inserta en media_juegos
            if (isset($data['game_id']) && !empty($data['game_id'])) {
                $stmt = $conn->prepare(
                    "INSERT INTO media_juegos (videojuego_id, tipo, url, orden) VALUES (:videojuego_id, :tipo, :url, :orden)"
                );
                $stmt->execute([
                    ':videojuego_id' => (int)$data['game_id'],
                    ':tipo' => $data['tipo'],
                    ':url' => $data['url'],
                    ':orden' => isset($data['orden']) ? (int)$data['orden'] : 0
                ]);
            } elseif (isset($data['dlc_id']) && !empty($data['dlc_id'])) {
                // Si es media de DLC, inserta en media_dlcs
                $stmt = $conn->prepare(
                    "INSERT INTO media_dlcs (dlc_id, tipo, url, orden) VALUES (:dlc_id, :tipo, :url, :orden)"
                );
                $stmt->execute([
                    ':dlc_id' => (int)$data['dlc_id'],
                    ':tipo' => $data['tipo'],
                    ':url' => $data['url'],
                    ':orden' => isset($data['orden']) ? (int)$data['orden'] : 0
                ]);
            } else {
                // Si no se proporciona game_id ni dlc_id, retorna error
                http_response_code(400);
                echo json_encode(['error' => 'Debe indicarse game_id o dlc_id']);
                break;
            }

            // Retorna éxito con el ID del elemento
            echo json_encode([
                'id' => (int)$conn->lastInsertId(),
                'message' => 'Elemento añadido correctamente'
            ]);
            break;

        case 'DELETE':
            // Valida que se proporcionen ID y tabla
            if (!isset($_GET['id']) || !isset($_GET['table'])) {
                http_response_code(400);
                echo json_encode(['error' => 'ID y tabla requeridos']);
                break;
            }

            // Obtiene la tabla objetivo
            $table = $_GET['table'];
            // Si es tabla de juegos, prepara la consulta de eliminación
            if ($table === 'game') {
                $stmt = $conn->prepare("DELETE FROM media_juegos WHERE id = :id");
            } elseif ($table === 'dlc') {
                // Si es tabla de DLCs, prepara la consulta de eliminación
                $stmt = $conn->prepare("DELETE FROM media_dlcs WHERE id = :id");
            } else {
                // Si la tabla no es válida, retorna error
                http_response_code(400);
                echo json_encode(['error' => 'Tabla no válida']);
                break;
            }

            // Ejecuta la eliminación de la media
            $stmt->execute([':id' => (int)$_GET['id']]);
            // Retorna mensaje de éxito
            echo json_encode(['message' => 'Elemento eliminado correctamente']);
            break;

        default:
            // Método no permitido
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            break;
    }
} catch (PDOException $e) {
    // Captura errores de base de datos
    http_response_code(500);
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}