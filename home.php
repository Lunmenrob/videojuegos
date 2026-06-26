<?php
session_start();
require_once 'csrf.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link rel="stylesheet" href="estilos/home.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <main class="home-shell">
        <section class="home-card">
            <h1>Bienvenido a Gestión de Trofeos</h1>
            <p>Elige un perfil</p>
            <div class="home-options">
                <a class="home-option" href="publico/public_index.php">
                    <img src="estilos/usuarios/invitado.png" alt="Visitante">
                    <h2>Visitante</h2>
                </a>
                <div class="home-option admin-option" id="admin-option">
                    <img src="estilos/usuarios/admin.jpg" alt="Administrador">
                    <h2>Administrador</h2>
                </div>
            </div>
            <div class="database-section">
                <p class="database-question">¿Sin Base de Datos?</p>
                <?php if (isset($_GET['success'])): ?>
                    <div class="database-message database-success">
                        <?php
                        if ($_GET['success'] === 'database_created') {
                            echo '✅ Base de datos creada correctamente';
                        } elseif ($_GET['success'] === 'tables_inserted') {
                            echo '✅ Datos insertados correctamente';
                        }
                        ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_GET['error'])): ?>
                    <div class="database-message database-error">
                        ❌ Error: <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>
                <div class="database-buttons">
                    <button type="button" class="database-btn" onclick="window.location.href='Database/crear_bd.php'">Crear Base de Datos</button>
                    <button type="button" class="database-btn" onclick="window.location.href='Database/crear_tablas.php'">Insertar Tablas</button>
                </div>
            </div>
            <div class="login-form-container" id="login-form-container" style="display: none;">
                <form class="login-form" id="login-form" method="post" action="admin_login.php">
                    <?php echo csrfField(); ?>
                    <div class="form-group">
                        <label for="usuario">Usuario</label>
                        <input type="text" id="usuario" name="usuario" required autocomplete="username" placeholder="admin">
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="password">
                    </div>
                    <button type="submit" class="login-submit">Entrar</button>
                    <button type="button" class="login-cancel" id="login-cancel">Cancelar</button>
                </form>
            </div>
        </section>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const adminOption = document.getElementById('admin-option');
            const loginFormContainer = document.getElementById('login-form-container');
            const loginCancel = document.getElementById('login-cancel');

            adminOption.addEventListener('click', function() {
                loginFormContainer.style.display = 'block';
            });

            loginCancel.addEventListener('click', function() {
                loginFormContainer.style.display = 'none';
            });
        });
    </script>
</body>
</html>