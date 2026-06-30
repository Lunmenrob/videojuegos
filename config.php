<?php
// Configuración de la base de datos
// Define el host de la base de datos
define('DB_HOST', 'localhost');
// Define el nombre de la base de datos
define('DB_NAME', 'videojuegos');
// Define el usuario de la base de datos
define('DB_USER', 'root');
// Define la contraseña de la base de datos
define('DB_PASS', '');

// Crear conexión a la base de datos
function getConnection() {
    try {
        // Crea una nueva conexión PDO
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
        // Establece el modo de error a excepciones
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Retorna la conexión
        return $conn;
    } catch(PDOException $e) {
        // Muestra un error de conexión y termina el script
        die("Error de conexión: " . $e->getMessage());
    }
}

// Headers de seguridad HTTP
// Previene el MIME-sniffing
header("X-Content-Type-Options: nosniff");
// Previene clickjacking (solo permite mismo origen)
header("X-Frame-Options: SAMEORIGIN");
// Habilita protección XSS del navegador
header("X-XSS-Protection: 1; mode=block");
// Fuerza HTTPS por 1 año
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
// Controla la información del referer
header("Referrer-Policy: strict-origin-when-cross-origin");
// Deshabilita geolocalización, micrófono y cámara
header("Permissions-Policy: geolocation=(), microphone=(), camera=()");

// Configurar cabeceras para CORS - Restringir a mismo origen para mayor seguridad
// Array de orígenes permitidos
$allowedOrigins = [
    'http://localhost',
    'http://localhost:8080',
    'http://127.0.0.1',
    'http://127.0.0.1:8080'
];

// Obtiene el origen de la solicitud
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
// Si el origen está en la lista de permitidos, establece el header
if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $origin");
}
// Permite credenciales en CORS
header("Access-Control-Allow-Credentials: true");
// Establece el tipo de contenido a JSON
header("Content-Type: application/json; charset=UTF-8");
// Permite métodos HTTP específicos
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
// Permite headers específicos
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Manejar solicitudes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Retorna código 200 OK
    http_response_code(200);
    // Termina el script
    exit();
}
?>
