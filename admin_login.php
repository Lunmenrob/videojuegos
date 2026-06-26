<?php
session_start();
require_once 'config.php';
require_once 'csrf.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar token CSRF
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!validateCsrfToken($csrfToken)) {
        $error = 'Error de seguridad. Por favor, recargue la página e intente nuevamente.';
    } else {
        $usuario = trim($_POST['usuario'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($usuario !== '' && $password !== '') {
            try {
                $conn = getConnection();
                $stmt = $conn->prepare('SELECT id, usuario, password_hash FROM admins WHERE usuario = :usuario LIMIT 1');
                $stmt->execute([':usuario' => $usuario]);
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($admin && password_verify($password, $admin['password_hash'])) {
                    // Regenerar token CSRF después de login exitoso
                    regenerateCsrfToken();

                    $_SESSION['admin_id'] = (int) $admin['id'];
                    $_SESSION['admin_user'] = $admin['usuario'];
                    $_SESSION['login_success'] = true;
                    header('Location: index.php');
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
    <link rel="stylesheet" href="estilos/admin_login.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <main class="login-shell">
        <section class="login-card">
            <h1>Acceso de administrador</h1>
            <p>Introduce las credenciales para entrar al panel de gestión.</p>
            <?php if ($error !== ''): ?>
                <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            <form method="post">
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
