<?php
/**
 * Sistema de Rate Limiting para prevenir ataques de fuerza bruta
 * Almacena intentos fallidos en sesión y bloquea temporalmente después de N intentos
 */

// Configuración del rate limiting
define('MAX_LOGIN_ATTEMPTS', 5); // Máximo de intentos fallidos permitidos
define('LOGIN_LOCKOUT_TIME', 900); // Tiempo de bloqueo en segundos (15 minutos)
define('LOGIN_ATTEMPT_WINDOW', 300); // Ventana de tiempo para contar intentos (5 minutos)

/**
 * Verifica si la IP está bloqueada por demasiados intentos fallidos
 * @return bool True si está bloqueado, false si no
 */
function isIpLockedOut() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $lockoutKey = 'login_lockout_' . $ip;
    
    // Verifica si existe un bloqueo activo
    if (isset($_SESSION[$lockoutKey])) {
        $lockoutTime = $_SESSION[$lockoutKey];
        // Si el bloqueo aún es válido
        if (time() - $lockoutTime < LOGIN_LOCKOUT_TIME) {
            return true;
        } else {
            // Bloqueo expirado, eliminar
            unset($_SESSION[$lockoutKey]);
            unset($_SESSION['login_attempts_' . $ip]);
        }
    }
    
    return false;
}

/**
 * Registra un intento de login fallido
 * @return int Número de intentos fallidos actuales
 */
function recordFailedLoginAttempt() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $attemptKey = 'login_attempts_' . $ip;
    
    // Inicializar contador si no existe
    if (!isset($_SESSION[$attemptKey])) {
        $_SESSION[$attemptKey] = [
            'count' => 0,
            'first_attempt' => time()
        ];
    }
    
    // Incrementar contador
    $_SESSION[$attemptKey]['count']++;
    
    // Verificar si excedió el máximo de intentos
    if ($_SESSION[$attemptKey]['count'] >= MAX_LOGIN_ATTEMPTS) {
        // Bloquear la IP
        $_SESSION['login_lockout_' . $ip] = time();
        return MAX_LOGIN_ATTEMPTS;
    }
    
    // Limpiar intentos antiguos fuera de la ventana de tiempo
    if (time() - $_SESSION[$attemptKey]['first_attempt'] > LOGIN_ATTEMPT_WINDOW) {
        $_SESSION[$attemptKey]['count'] = 1;
        $_SESSION[$attemptKey]['first_attempt'] = time();
    }
    
    return $_SESSION[$attemptKey]['count'];
}

/**
 * Reinicia los intentos de login fallidos (llamar después de login exitoso)
 */
function resetLoginAttempts() {
    $ip = $_SERVER['REMOTE_ADDR'];
    unset($_SESSION['login_attempts_' . $ip]);
    unset($_SESSION['login_lockout_' . $ip]);
}

/**
 * Obtiene el tiempo restante de bloqueo en segundos
 * @return int Segundos restantes, 0 si no está bloqueado
 */
function getLockoutRemainingTime() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $lockoutKey = 'login_lockout_' . $ip;
    
    if (isset($_SESSION[$lockoutKey])) {
        $lockoutTime = $_SESSION[$lockoutKey];
        $remaining = LOGIN_LOCKOUT_TIME - (time() - $lockoutTime);
        return max(0, $remaining);
    }
    
    return 0;
}
?>
