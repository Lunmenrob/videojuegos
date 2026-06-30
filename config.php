<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'videojuegos');
define('DB_USER', 'root');
define('DB_PASS', '');

// Crear conexión
function getConnection() {
    try {
<<<<<<< HEAD
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
=======
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}

// Headers de seguridad HTTP
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: geolocation=(), microphone=(), camera=()");

// Configurar cabeceras para CORS - Restringir a mismo origen para mayor seguridad
$allowedOrigins = [
    'http://localhost',
    'http://localhost:8080',
    'http://127.0.0.1',
    'http://127.0.0.1:8080'
];

$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $origin");
}
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Manejar solicitudes OPTIONS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}
?>
