<?php
// Iniciar sesión para gestionar variables de sesión
session_start();
// Incluir protección CSRF para formulario de login
require_once 'csrf.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <!-- Enlace a la hoja de estilos de home -->
    <link rel="stylesheet" href="estilos/css/home.css">
    <!-- Enlace a Font Awesome para iconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Contenedor principal de la página -->
    <main class="home-shell">
        <!-- Tarjeta central con opciones de perfil -->
        <section class="home-card">
            <h1>Bienvenido a Gestión de Trofeos</h1>
            <p>Elige un perfil</p>
            <!-- Opciones de acceso: visitante y administrador -->
            <div class="home-options">
                <!-- Opción de visitante (acceso público sin login) -->
                <a class="home-option" href="publico/public_index.php">
                    <img src="interfaz/trofeos/usuarios/invitado.png" alt="Visitante">
                    <h2>Visitante</h2>
                </a>
                <!-- Opción de administrador (requiere login) -->
                <div class="home-option admin-option" id="admin-option">
                    <img src="interfaz/trofeos/usuarios/admin.jpg" alt="Administrador">
                    <h2>Administrador</h2>
                </div>
            </div>
            <!-- Sección para configuración de base de datos -->
            <div class="database-section">
                <p class="database-question">¿Sin Base de Datos?</p>
                <!-- Mostrar mensaje de éxito si la operación fue exitosa -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="database-message database-success">
                        <?php
                        if ($_GET['success'] === 'database_created') {
                            echo '✅ Base de datos creada correctamente';
                        } elseif ($_GET['success'] === 'tables_inserted') {
                            echo '✅ Datos insertados correctamente';
                        } elseif ($_GET['success'] === 'tables_imported') {
                            $count = isset($_GET['count']) ? htmlspecialchars($_GET['count']) : '0';
                            echo "✅ Tablas importadas correctamente ($count sentencias ejecutadas)";
                        }
                        ?>
                    </div>
                <?php endif; ?>
                <!-- Mostrar mensaje de error si hubo algún problema -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="database-message database-error">
                        ❌ Error: <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>
                <!-- Botones para crear base de datos e insertar tablas -->
                <div class="database-buttons">
                    <button type="button" class="database-btn" onclick="window.location.href='Database/crear_bd.php'">Crear Base de Datos</button>
                    <button type="button" class="database-btn" onclick="window.location.href='Database/crear_tablas.php'">Insertar Tablas</button>
                </div>
            </div>
            <!-- Formulario de login para administrador (oculto por defecto) -->
            <div class="login-form-container" id="login-form-container" style="display: none;">
                <form class="login-form" id="login-form" method="post" action="admin_login.php">
                    <!-- Campo oculto CSRF para protección -->
                    <?php echo csrfField(); ?>
                    <!-- Campo de usuario -->
                    <div class="form-group">
                        <label for="usuario">Usuario</label>
                        <input type="text" id="usuario" name="usuario" required autocomplete="username" placeholder="admin">
                    </div>
                    <!-- Campo de contraseña -->
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="password">
                    </div>
                    <!-- Botón para enviar formulario -->
                    <button type="submit" class="login-submit">Entrar</button>
                    <!-- Botón para cancelar y cerrar formulario -->
                    <button type="button" class="login-cancel" id="login-cancel">Cancelar</button>
                </form>
            </div>
        </section>
    </main>
    <!-- Script para manejar la interacción del formulario de login -->
    <script>
        // Espera a que el DOM esté completamente cargado
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener elementos del DOM
            const adminOption = document.getElementById('admin-option');
            const loginFormContainer = document.getElementById('login-form-container');
            const loginCancel = document.getElementById('login-cancel');

            // Al hacer clic en opción de administrador, mostrar formulario de login
            adminOption.addEventListener('click', function() {
                loginFormContainer.style.display = 'block';
            });

            // Al hacer clic en cancelar, ocultar formulario de login
            loginCancel.addEventListener('click', function() {
                loginFormContainer.style.display = 'none';
            });
        });
    </script>
</body>
</html>