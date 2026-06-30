<?php
require_once '../config.php';

function getConnectionForMedia() {
    return getConnection();
}

try {
    $conn = getConnectionForMedia();
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            if (isset($_GET['game_id'])) {
                $gameId = (int)$_GET['game_id'];
                $stmt = $conn->prepare(
                    "SELECT id, videojuego_id, tipo, url, orden FROM media_juegos WHERE videojuego_id = :game_id ORDER BY orden ASC, id ASC"
                );
                $stmt->execute([':game_id' => $gameId]);
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                break;
            }

            if (isset($_GET['dlc_id'])) {
                $dlcId = (int)$_GET['dlc_id'];
                $stmt = $conn->prepare(
                    "SELECT id, dlc_id, tipo, url, orden FROM media_dlcs WHERE dlc_id = :dlc_id ORDER BY orden ASC, id ASC"
                );
                $stmt->execute([':dlc_id' => $dlcId]);
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                break;
            }

            http_response_code(400);
            echo json_encode(['error' => 'ID de juego o DLC requerido']);
            break;

        case 'POST':
            // Verificar si es una subida de archivo (FormData)
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['file'];
                $tipo = $_POST['tipo'] ?? 'image';
                $dlc_id = isset($_POST['dlc_id']) ? (int)$_POST['dlc_id'] : null;
                $game_id = isset($_POST['game_id']) ? (int)$_POST['game_id'] : null;

                // Crear directorio de uploads si no existe
                $uploadDir = '../uploads/media/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Generar nombre único para el archivo
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = uniqid('media_', true) . '.' . $extension;
                $filepath = $uploadDir . $filename;

                // Mover el archivo
                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    $url = 'uploads/media/' . $filename;

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
                        http_response_code(400);
                        echo json_encode(['error' => 'Debe indicarse game_id o dlc_id']);
                        break;
                    }

                    echo json_encode([
                        'id' => (int)$conn->lastInsertId(),
                        'url' => $url,
                        'message' => 'Archivo subido correctamente'
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode(['error' => 'Error al mover el archivo']);
                }
                break;
            }

            // Procesar como JSON (URL)
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data || !isset($data['tipo']) || !isset($data['url'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Faltan datos requeridos']);
                break;
            }

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
                http_response_code(400);
                echo json_encode(['error' => 'Debe indicarse game_id o dlc_id']);
                break;
            }

            echo json_encode([
                'id' => (int)$conn->lastInsertId(),
                'message' => 'Elemento añadido correctamente'
            ]);
            break;

        case 'DELETE':
            if (!isset($_GET['id']) || !isset($_GET['table'])) {
                http_response_code(400);
                echo json_encode(['error' => 'ID y tabla requeridos']);
                break;
            }

            $table = $_GET['table'];
            if ($table === 'game') {
                $stmt = $conn->prepare("DELETE FROM media_juegos WHERE id = :id");
            } elseif ($table === 'dlc') {
                $stmt = $conn->prepare("DELETE FROM media_dlcs WHERE id = :id");
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Tabla no válida']);
                break;
            }

            $stmt->execute([':id' => (int)$_GET['id']]);
            echo json_encode(['message' => 'Elemento eliminado correctamente']);
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            break;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}