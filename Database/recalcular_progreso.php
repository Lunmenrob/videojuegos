<?php
require_once '../config.php';

try {
    $conn = getConnection();
    
    // Obtener todos los juegos
    $stmt = $conn->query("SELECT id, titulo FROM juegos");
    $juegos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Recalculando progreso de trofeos para todos los juegos</h2>";
    
    foreach ($juegos as $juego) {
        $gameId = $juego['id'];
        $titulo = $juego['titulo'];
        
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
        
        echo "<p><strong>$titulo</strong>: $total_conseguidos/$total_trofeos ($porcentaje%)</p>";
    }
    
    echo "<h3>¡Progreso recalculado correctamente!</h3>";
    echo "<p><a href='../index.php'>Volver al índice</a></p>";
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
