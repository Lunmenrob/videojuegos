<?php
session_start();// Inicia la sesión

$_SESSION = [];// Destruir todas las variables de sesión

// Eliminar la cookie de sesión si existe
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();// Obtiene los parámetros de la cookie de sesión
    setcookie(session_name(), '', time() - 42000,// Establece la cookie con una fecha de expiración en el pasado
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();// Destruir la sesión

header('Location: /Prácticas/videojuegos/home.php');// Redirigir a home
exit;
?>
