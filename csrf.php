<?php
// Gestión de tokens CSRF para protección contra ataques CSRF

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    // Inicia la sesión
    session_start();
}

/**
 * Generar un token CSRF
 */
function generateCsrfToken() {
    // Si no existe el token en la sesión
    if (!isset($_SESSION['csrf_token'])) {
        // Genera un token aleatorio de 64 caracteres hexadecimales
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        // Guarda la hora de generación
        $_SESSION['csrf_token_time'] = time();
    }
    // Retorna el token CSRF
    return $_SESSION['csrf_token'];
}

/**
 * Validar un token CSRF
 */
function validateCsrfToken($token) {
    // Si no existe el token o la hora en la sesión
    if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_time'])) {
        // Retorna false (token inválido)
        return false;
    }

    // El token expira después de 24 horas (86400 segundos)
    if (time() - $_SESSION['csrf_token_time'] > 86400) {
        // Elimina el token expirado
        unset($_SESSION['csrf_token']);
        unset($_SESSION['csrf_token_time']);
        // Retorna false (token expirado)
        return false;
    }

    // Compara el token proporcionado con el de la sesión de forma segura
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Generar campo HTML para token CSRF
 */
function csrfField() {
    // Genera el token CSRF
    $token = generateCsrfToken();
    // Retorna un campo input hidden con el token
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * Regenerar token CSRF (útil después de acciones importantes)
 */
function regenerateCsrfToken() {
    // Genera un nuevo token CSRF
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    // Actualiza la hora de generación
    $_SESSION['csrf_token_time'] = time();
}
?>
