<?php
require_once '../config.php';

try {
    $conn = getConnection();
    
    // Vaciar las tablas antes de importar (en orden correcto para evitar errores de FK)
    $tablesToTruncate = [
        'progreso_trofeos',
        'trofeos_dlc',
        'trofeos',
        'media_dlcs',
        'media_juegos',
        'dlcs',
        'juegos',
        'admins'
    ];
    
    foreach ($tablesToTruncate as $table) {
        try {
            $conn->exec("SET FOREIGN_KEY_CHECKS = 0");
            $conn->exec("TRUNCATE TABLE `$table`");
            $conn->exec("SET FOREIGN_KEY_CHECKS = 1");
        } catch (PDOException $e) {
            // La tabla podría no existir, continuar
            error_log("Error truncando tabla $table: " . $e->getMessage());
        }
    }
    
    // Verificar si la columna 'es_online' existe en la tabla 'trofeos' y agregarla si no
    try {
        $stmt = $conn->query("SHOW COLUMNS FROM `trofeos` LIKE 'es_online'");
        if ($stmt->rowCount() === 0) {
            $conn->exec("ALTER TABLE `trofeos` ADD COLUMN `es_online` TINYINT(1) DEFAULT 0 AFTER `conseguido`");
        }
    } catch (PDOException $e) {
        error_log("Error verificando/agregando columna es_online en trofeos: " . $e->getMessage());
    }
    
    // Verificar si la columna 'es_online' existe en la tabla 'trofeos_dlc' y agregarla si no
    try {
        $stmt = $conn->query("SHOW COLUMNS FROM `trofeos_dlc` LIKE 'es_online'");
        if ($stmt->rowCount() === 0) {
            $conn->exec("ALTER TABLE `trofeos_dlc` ADD COLUMN `es_online` TINYINT(1) DEFAULT 0 AFTER `conseguido`");
        }
    } catch (PDOException $e) {
        error_log("Error verificando/agregando columna es_online en trofeos_dlc: " . $e->getMessage());
    }
    
    // Aumentar el tamaño de la columna video_url a TEXT para permitir URLs más largas
    try {
        $conn->exec("ALTER TABLE `trofeos` MODIFY COLUMN `video_url` TEXT");
    } catch (PDOException $e) {
        error_log("Error modificando columna video_url: " . $e->getMessage());
    }
    
    // Mostrar estructura actual de la tabla trofeos para comparación
    echo "<br><strong>Estructura actual de la tabla trofeos:</strong><br>";
    try {
        $stmt = $conn->query("DESCRIBE `trofeos`");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "- {$row['Field']}: {$row['Type']}<br>";
        }
    } catch (PDOException $e) {
        echo "Error obteniendo estructura: " . $e->getMessage() . "<br>";
    }
    
    // Verificar cuántos trofeos hay en la base de datos
    echo "<br><strong>Conteo de registros en tablas:</strong><br>";
    try {
        $stmt = $conn->query("SELECT COUNT(*) as count FROM `trofeos`");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "- Trofeos: {$row['count']}<br>";
        
        $stmt = $conn->query("SELECT COUNT(*) as count FROM `juegos`");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "- Juegos: {$row['count']}<br>";
        
        $stmt = $conn->query("SELECT COUNT(*) as count FROM `dlcs`");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "- DLCs: {$row['count']}<br>";
    } catch (PDOException $e) {
        echo "Error obteniendo conteos: " . $e->getMessage() . "<br>";
    }
    
    // Leer el archivo SQL de datos guardados
    $sqlFile = 'db_videojuegos_guardados.sql';
    
    if (!file_exists($sqlFile)) {
        echo "❌ El archivo SQL no existe: " . $sqlFile;
        exit;
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Usar PDO multi_query para ejecutar el SQL completo
    echo "Ejecutando SQL usando PDO multi_query...<br>";
    
    try {
        if ($conn->multi_query($sql)) {
            do {
                if ($result = $conn->store_result()) {
                    $result->free();
                }
            } while ($conn->more_results() && $conn->next_result());
            
            echo "✅ Importación completada con éxito<br>";
            echo "El archivo SQL se importó correctamente usando PDO multi_query.<br>";
        } else {
            throw new PDOException($conn->error);
        }
    } catch (PDOException $e) {
        echo "❌ Error durante la importación<br>";
        echo "Error: " . $e->getMessage() . "<br>";
    }
    
    echo "<br><a href='../home.php'>Volver al home</a>";
    exit;
    
} catch (PDOException $e) {
    // Redirigir al home con mensaje de error
    header('Location: ../home.php?error=' . urlencode($e->getMessage()));
    exit;
}
?>
