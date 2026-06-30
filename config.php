<?php
// Configuración de la base de datos
// Prioriza variables de entorno, usa valores por defecto si no están definidos
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');// host de la base de datos
define('DB_NAME', getenv('DB_NAME') ?: 'videojuegos');// nombre de la base de datos
define('DB_USER', getenv('DB_USER') ?: 'root');// usuario de la base de datos
define('DB_PASS', getenv('DB_PASS') ?: '');// contraseña de la base de datos

// Crear conexión a la base de datos
function getConnection() {
    try {
        // Crea una nueva conexión PDO
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);// modo de error a excepciones
        return $conn;// Retorna la conexión
    } catch(PDOException $e) {
        die("Error de conexión: " . $e->getMessage());// error de conexión y termina el script
    }
}

// Headers de seguridad HTTP
header("X-Content-Type-Options: nosniff");// Previene el MIME-sniffing
header("X-Frame-Options: SAMEORIGIN");// Previene clickjacking (solo permite mismo origen)
header("X-XSS-Protection: 1; mode=block");// Habilita protección XSS del navegador
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");// Fuerza HTTPS por 1 año
header("Referrer-Policy: strict-origin-when-cross-origin");// Controla la información del referer
header("Permissions-Policy: geolocation=(), microphone=(), camera=()");// Deshabilita geolocalización, micrófono y cámara

// Solo establecer headers JSON y CORS si estamos en la API
$isApiRequest = strpos($_SERVER['REQUEST_URI'], '/api/') !== false;
if ($isApiRequest) {
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
    if (in_array($origin, $allowedOrigins)) {// Si el origen está en la lista de permitidos, establece el header
        header("Access-Control-Allow-Origin: $origin");
    }
    // Permite credenciales en CORS
    header("Access-Control-Allow-Credentials: true");
    header("Content-Type: application/json; charset=UTF-8");// Establece el tipo de contenido a JSON
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");// Permite métodos HTTP específicos
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");// Permite headers específicos

    // Manejar solicitudes OPTIONS (preflight)
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        http_response_code(200); // código 200 OK
        exit();
    }
}
?>
