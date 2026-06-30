<?php
session_start();

// Verificar si el usuario está logado como administrador
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_user'])) {
    // Si no está logado, redirigir a home.php
    header('Location: /Prácticas/videojuegos/home.php');
    exit;
}
?>
