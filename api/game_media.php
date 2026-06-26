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