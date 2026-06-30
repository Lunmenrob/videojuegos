<?php
// Incluye el archivo de configuración
require_once '../config.php';

try {
    // Obtiene la conexión a la base de datos
    $conn = getConnection();
    // Obtiene el método HTTP de la solicitud
    $method = $_SERVER['REQUEST_METHOD'];
    
    // Si el método es POST
    if ($method === 'POST') {
        // Decodifica el JSON del cuerpo de la solicitud
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Valida que los datos requeridos estén presentes
        if (!$data || !isset($data['action']) || !isset($data['trophy_id'])) {
            // Retorna error 400 si faltan datos
            http_response_code(400);
            echo json_encode(['error' => 'Faltan datos requeridos']);
            // Termina el script
            exit;
        }
        
        // Convierte el ID del trofeo a entero
        $trophyId = (int)$data['trophy_id'];
        // Obtiene la acción a realizar
        $action = $data['action'];
        
        // Si la acción es toggle (cambiar estado del trofeo)
        if ($action === 'toggle') {
            // Obtener información del trofeo
            $stmt = $conn->prepare("SELECT videojuego_id, tipo FROM trofeos WHERE id = :id");
            $stmt->execute([':id' => $trophyId]);
            $trophy = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Si no se encuentra el trofeo
            if (!$trophy) {
                // Retorna error 404
                http_response_code(404);
                echo json_encode(['error' => 'Trofeo no encontrado']);
                // Termina el script
                exit;
            }
            
            // Obtiene el estado conseguido del trofeo
            $conseguido = isset($data['conseguido']) ? (bool)$data['conseguido'] : false;
            
            // Actualizar estado del trofeo
            $stmt = $conn->prepare("
                UPDATE trofeos 
                SET conseguido = :conseguido
                WHERE id = :id
            ");
            $stmt->execute([
                ':id' => $trophyId,
                ':conseguido' => $conseguido
            ]);
            
            // Actualizar progreso del videojuego
            updateProgress($conn, $trophy['videojuego_id']);
            
            // Retorna mensaje de éxito
            echo json_encode(['message' => 'Trofeo actualizado correctamente']);
            
        } elseif ($action === 'update_icon') {
            // Actualizar icono_url del trofeo
            $iconoUrl = isset($data['icono_url']) ? $data['icono_url'] : null;
            
            $stmt = $conn->prepare("
                UPDATE trofeos 
                SET icono_url = :icono_url
                WHERE id = :id
            ");
            $stmt->execute([
                ':id' => $trophyId,
                ':icono_url' => $iconoUrl
            ]);
            
            echo json_encode(['message' => 'Icono del trofeo actualizado correctamente']);
            
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Acción no válida']);
        }
        
    } else {
        // Si el método no es POST
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
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
        ':platino_conseguido' => $platino_conseguido,
        ':porcentaje' => $porcentaje
    ]);
}
?>