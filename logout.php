<?php
// Inicia la sesión
session_start();

// Destruir todas las variables de sesión
$_SESSION = [];

// Eliminar la cookie de sesión si existe
if (ini_get("session.use_cookies")) {
    // Obtiene los parámetros de la cookie de sesión
    $params = session_get_cookie_params();
    // Establece la cookie con una fecha de expiración en el pasado
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir la sesión
session_destroy();

// Redirigir a home
header('Location: /Prácticas/videojuegos/home.php');
// Termina el script
exit;
?>
