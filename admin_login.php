<?php
// Inicia la sesión
session_start();
// Incluye el archivo de configuración
require_once 'config.php';
// Incluye el archivo de protección CSRF
require_once 'csrf.php';

// Si el usuario ya está logueado, redirige al index
if (!empty($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

// Inicializa variable de error
$error = '';
// Si el método es POST, procesa el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';// Validar token CSRF
    if (!validateCsrfToken($csrfToken)) {
        $error = 'Error de seguridad. Por favor, recargue la página e intente nuevamente.';
    } else {
        $usuario = trim($_POST['usuario'] ?? '');// Obtiene y limpia el usuario
        $password = $_POST['password'] ?? '';// Obtiene la contraseña

        // Valida que usuario y contraseña no estén vacíos
        if ($usuario !== '' && $password !== '') {
            try {
                $conn = getConnection(); // Obtiene la conexión a la base de datos
                $stmt = $conn->prepare('SELECT id, usuario, password_hash FROM admins WHERE usuario = :usuario LIMIT 1');// Prepara la consulta para buscar el administrador
                $stmt->execute([':usuario' => $usuario]);
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verifica la contraseña
                if ($admin && password_verify($password, $admin['password_hash'])) {
                    regenerateCsrfToken();// Regenerar token CSRF después de login exitoso

                    // Establece las variables de sesión
                    $_SESSION['admin_id'] = (int) $admin['id'];
                    $_SESSION['admin_user'] = $admin['usuario'];
                    $_SESSION['login_success'] = true;
                    header('Location: index.php');// Redirige al index
                    exit;
                }
            } catch (Exception $e) {
                $error = 'Error al procesar el login.';
            }
        }

        $error = 'Usuario o contraseña incorrectos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso de administrador</title>
    <!-- Enlace a la hoja de estilos del login -->
    <link rel="stylesheet" href="estilos/admin_login.css">
    <!-- Enlace a Font Awesome para iconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <main class="login-shell">
        <section class="login-card">
            <h1>Acceso de administrador</h1>
            <p>Introduce las credenciales para entrar al panel de gestión.</p>
            <!-- Muestra mensaje de error si existe -->
            <?php if ($error !== ''): ?>
                <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            <!-- Formulario de login -->
            <form method="post">
                <!-- Campo oculto con token CSRF -->
                <?php echo csrfField(); ?>
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" required autocomplete="username" placeholder="admin">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="password">
                <button type="submit">Entrar</button>
            </form>
        </section>
    </main>
</body>
</html>
