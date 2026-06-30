<?php
require_once '../config.php';

try {
    $conn = getConnection();
    $stmt = $conn->prepare('SELECT id, usuario FROM admins');
    $stmt->execute();
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Administradores en la base de datos:</h2>";
    
    if (empty($admins)) {
        echo "<p style='color: red;'>No hay administradores en la base de datos.</p>";
        echo "<p>Creando administrador por defecto...</p>";
        
        // Crear administrador por defecto
        $defaultUser = 'admin';
        $defaultPass = 'admin';
        $passwordHash = password_hash($defaultPass, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare('INSERT INTO admins (usuario, password_hash) VALUES (:usuario, :password_hash)');
        $stmt->execute([':usuario' => $defaultUser, ':password_hash' => $passwordHash]);
        
        echo "<p style='color: green;'>✅ Administrador creado exitosamente.</p>";
        echo "<p><strong>Usuario:</strong> $defaultUser</p>";
        echo "<p><strong>Contraseña:</strong> $defaultPass</p>";
        echo "<p style='color: orange;'>⚠️ Por seguridad, cambia esta contraseña después de hacer login.</p>";
    } else {
        echo "<ul>";
        foreach ($admins as $admin) {
            echo "<li>ID: {$admin['id']}, Usuario: {$admin['usuario']}</li>";
        }
        echo "</ul>";
        echo "<p>Si necesitas cambiar la contraseña de un administrador, puedes usar este SQL:</p>";
        echo "<pre>";
        echo "UPDATE admins SET password_hash = '" . password_hash('nueva_contraseña', PASSWORD_DEFAULT) . "' WHERE usuario = 'admin';";
        echo "</pre>";
    }
    
    echo "<br><a href='../home.php'>Volver al home</a>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
