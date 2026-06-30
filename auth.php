<?php
session_start();// Inicia la sesión

// Verificar si el usuario está logado como administrador
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_user'])) {
    header('Location: /Prácticas/videojuegos/home.php');// Si no está logado, redirigir a home.php
    exit;
}
?>
