<?php
session_start();// Inicia la sesión

// Verificar si el usuario está logado como administrador
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_user'])) {
    header('Location: /Prácticas/videojuegos/home.php');// Si no está logado, redirigir a home.php
    exit;
}

// Verificar IP de sesión para prevenir session hijacking
if (isset($_SESSION['admin_ip']) && $_SESSION['admin_ip'] !== $_SERVER['REMOTE_ADDR']) {
    // IP ha cambiado, destruir sesión y redirigir
    session_destroy();
    header('Location: /Prácticas/videojuegos/home.php?error=session_invalid');
    exit;
}

// Verificar User-Agent para prevenir session hijacking
if (isset($_SESSION['admin_user_agent']) && $_SESSION['admin_user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    // User-Agent ha cambiado, destruir sesión y redirigir
    session_destroy();
    header('Location: /Prácticas/videojuegos/home.php?error=session_invalid');
    exit;
}
?>
