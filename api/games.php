<?php
// Incluye el archivo de configuración
require_once '../config.php';
// Incluye el middleware de autenticación
require_once '../api_auth.php';

// Verifica autenticación (permite GET sin autenticación, pero requiere auth para POST/PUT/DELETE)
requireAuth(true);

try {
    // Obtiene la conexión a la base de datos
    $conn = getConnection();
    // Obtiene el método HTTP de la solicitud
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            // Obtener parámetros de búsqueda
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $platform = isset($_GET['platform']) ? $_GET['platform'] : '';

            // Construir consulta SQL
            $sql = "
                SELECT v.*,
                    p.total_trofeos,
                    p.bronce_conseguidos,
                    p.plata_conseguidos,
                    p.oro_conseguidos,
                    p.platino_conseguido,
                    p.porcentaje_completado
                FROM juegos v
                LEFT JOIN progreso_trofeos p ON v.id = p.videojuego_id
            ";

            // Array de parámetros para la consulta
            $params = [];
            // Array de condiciones WHERE
            $conditions = [];

            // Si hay término de búsqueda, agrega condición
            if (!empty($search)) {
                $conditions[] = "(v.titulo LIKE :search OR v.desarrollador LIKE :search OR v.genero LIKE :search)";
                $params[':search'] = '%' . $search . '%';
            }

            // Si hay filtro de plataforma, agrega condición
            if (!empty($platform)) {
                $conditions[] = "v.plataforma = :platform";
                $params[':platform'] = $platform;
            }

            // Si hay condiciones, las agrega a la consulta
            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(' AND ', $conditions);
            }

            // Ordena por fecha de adición descendente
            $sql .= " ORDER BY v.fecha_adicionado DESC";

            // Prepara y ejecuta la consulta
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            // Obtiene todos los resultados
            $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Formatear fechas y asegurar que los campos numéricos sean del tipo correcto
            foreach ($games as &$game) {
                $game['id'] = (int)$game['id'];
                $game['total_trofeos'] = (int)($game['total_trofeos'] ?? 0);
                $game['bronce_conseguidos'] = (int)($game['bronce_conseguidos'] ?? 0);
                $game['plata_conseguidos'] = (int)($game['plata_conseguidos'] ?? 0);
                $game['oro_conseguidos'] = (int)($game['oro_conseguidos'] ?? 0);
                $game['platino_conseguido'] = (bool)($game['platino_conseguido'] ?? false);
                $game['porcentaje_completado'] = (float)($game['porcentaje_completado'] ?? 0);
            }

            // Retorna los juegos en formato JSON
            echo json_encode($games);
            break;

        case 'DELETE':
            // Borrar un juego completo
            if (!isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'ID de juego requerido']);
                break;
            }

            // Convierte el ID a entero
            $gameId = (int)$_GET['id'];
            // Log para depuración
            error_log("DELETE request for game ID: " . $gameId);

            try {
                // Inicia una transacción
                $conn->beginTransaction();

                // Primero obtener los DLCs para poder borrar sus dependencias
                error_log("Getting DLCs for game: " . $gameId);
                $stmt = $conn->prepare("SELECT id FROM dlcs WHERE videojuego_id = :game_id");
                $stmt->execute([':game_id' => $gameId]);
                $dlcs = $stmt->fetchAll(PDO::FETCH_COLUMN);
                error_log("Found DLCs: " . count($dlcs));

                // Borrar media de DLCs primero
                foreach ($dlcs as $dlcId) {
                    error_log("Deleting media for DLC: " . $dlcId);
                    $stmt = $conn->prepare("DELETE FROM media_dlcs WHERE dlc_id = :dlc_id");
                    $stmt->execute([':dlc_id' => $dlcId]);
                }

                // Borrar trofeos de DLCs
                foreach ($dlcs as $dlcId) {
                    error_log("Deleting trofeos for DLC: " . $dlcId);
                    $stmt = $conn->prepare("DELETE FROM trofeos_dlc WHERE dlc_id = :dlc_id");
                    $stmt->execute([':dlc_id' => $dlcId]);
                }

                // Borrar DLCs
                error_log("Deleting DLCs for game: " . $gameId);
                $stmt = $conn->prepare("DELETE FROM dlcs WHERE videojuego_id = :game_id");
                $stmt->execute([':game_id' => $gameId]);

                // Borrar media del juego
                error_log("Deleting media for game: " . $gameId);
                $stmt = $conn->prepare("DELETE FROM media_juegos WHERE videojuego_id = :game_id");
                $stmt->execute([':game_id' => $gameId]);

                // Borrar trofeos del juego
                error_log("Deleting trofeos for game: " . $gameId);
                $stmt = $conn->prepare("DELETE FROM trofeos WHERE videojuego_id = :game_id");
                $stmt->execute([':game_id' => $gameId]);

                // Borrar progreso de trofeos
                error_log("Deleting progreso_trofeos for game: " . $gameId);
                $stmt = $conn->prepare("DELETE FROM progreso_trofeos WHERE videojuego_id = :game_id");
                $stmt->execute([':game_id' => $gameId]);

                // Finalmente borrar el juego
                error_log("Deleting game: " . $gameId);
                $stmt = $conn->prepare("DELETE FROM juegos WHERE id = :game_id");
                $stmt->execute([':game_id' => $gameId]);

                // Confirma la transacción
                $conn->commit();
                error_log("Game deleted successfully: " . $gameId);

                // Retorna éxito
                http_response_code(200);
                echo json_encode(['success' => true, 'message' => 'Juego borrado correctamente']);
            } catch (PDOException $e) {
                // Revierte la transacción en caso de error
                $conn->rollBack();
                error_log("DELETE error: " . $e->getMessage());
                error_log("DELETE error trace: " . $e->getTraceAsString());
                http_response_code(500);
                echo json_encode(['error' => 'Error borrando juego: ' . $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            }
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
?>
