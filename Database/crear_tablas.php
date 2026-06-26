<?php
require_once '../config.php';

try {
    $conn = getConnection();
    
    // Leer el archivo SQL de datos guardados
    $sqlFile = 'db_videojuegos_guardados.sql';
    
    if (!file_exists($sqlFile)) {
        echo "❌ El archivo SQL no existe: " . $sqlFile;
        exit;
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Dividir el SQL en instrucciones individuales
    $statements = explode(';', $sql);
    
    // Ejecutar cada instrucción por separado
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            try {
                $conn->exec($statement);
            } catch (PDOException $e) {
                // Continuar con la siguiente instrucción si una falla
                error_log("Error ejecutando instrucción: " . $e->getMessage());
            }
        }
    }
    
    // Redirigir al home con mensaje de éxito
    header('Location: ../home.php?success=tables_inserted');
    exit;
    
} catch (PDOException $e) {
    // Redirigir al home con mensaje de error
    header('Location: ../home.php?error=' . urlencode($e->getMessage()));
    exit;
}
?>
