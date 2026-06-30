<?php
/**
 * Middleware de autenticación para la API
 * Verifica que el usuario esté autenticado como administrador antes de permitir acceso a los endpoints
 */

// Inicia la sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica si el usuario está autenticado como administrador
 * @param bool $allowRead Si es true, permite métodos GET sin autenticación (solo lectura)
 */
function requireAuth($allowRead = false) {
    $method = $_SERVER['REQUEST_METHOD'];
    
    // Si se permite lectura y el método es GET, no requerir autenticación
    if ($allowRead && $method === 'GET') {
        return;
    }
    
    // Para métodos de escritura (POST/PUT/DELETE) o si no se permite lectura, verificar autenticación
    // Verifica si existe admin_id y admin_user en la sesión
    if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_user'])) {
        // Retorna error 401 Unauthorized
        http_response_code(401);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['error' => 'No autorizado. Se requiere autenticación de administrador para esta acción.']);
        exit;
    }
    
    // Opcional: Verificar IP de sesión para prevenir session hijacking
    if (isset($_SESSION['admin_ip']) && $_SESSION['admin_ip'] !== $_SERVER['REMOTE_ADDR']) {
        // IP ha cambiado, posible session hijacking
        http_response_code(401);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['error' => 'Sesión inválida. La dirección IP ha cambiado.']);
        exit;
    }
    
    // Opcional: Verificar User-Agent para prevenir session hijacking
    if (isset($_SESSION['admin_user_agent']) && $_SESSION['admin_user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
        // User-Agent ha cambiado, posible session hijacking
        http_response_code(401);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['error' => 'Sesión inválida. El User-Agent ha cambiado.']);
        exit;
    }
}

/**
 * Verifica si el usuario es administrador (para endpoints públicos que necesitan verificación opcional)
 */
function isAdmin() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_user']);
}
?>
