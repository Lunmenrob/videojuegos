<?php
require_once '../config.php';

try {
    // Conectar sin especificar base de datos para poder crearla
    $dsn = "mysql:host=" . DB_HOST . ";charset=utf8mb4";
    $conn = new PDO($dsn, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Leer el archivo SQL
    $sql = file_get_contents('database.sql');
    
    // Ejecutar el SQL completo
    $conn->exec($sql);
    
    // Redirigir al home con mensaje de éxito
    header('Location: ../home.php?success=database_created');
    exit;
    
} catch (PDOException $e) {
    // Redirigir al home con mensaje de error
    header('Location: ../home.php?error=' . urlencode($e->getMessage()));
    exit;
}
?>
