<?php
<<<<<<< HEAD
// Iniciar sesión para gestionar variables de sesión
session_start();
// Incluir protección CSRF para formulario de login
=======
session_start();
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
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
<<<<<<< HEAD
    <!-- Contenedor principal de la página -->
    <main class="home-shell">
        <!-- Tarjeta central con opciones de perfil -->
        <section class="home-card">
            <h1>Bienvenido a Gestión de Trofeos</h1>
            <p>Elige un perfil</p>
            <!-- Opciones de acceso: visitante y administrador -->
            <div class="home-options">
                <!-- Opción de visitante (acceso público sin login) -->
=======
    <main class="home-shell">
        <section class="home-card">
            <h1>Bienvenido a Gestión de Trofeos</h1>
            <p>Elige un perfil</p>
            <div class="home-options">
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                <a class="home-option" href="publico/public_index.php">
                    <img src="estilos/usuarios/invitado.png" alt="Visitante">
                    <h2>Visitante</h2>
                </a>
<<<<<<< HEAD
                <!-- Opción de administrador (requiere login) -->
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                <div class="home-option admin-option" id="admin-option">
                    <img src="estilos/usuarios/admin.jpg" alt="Administrador">
                    <h2>Administrador</h2>
                </div>
            </div>
<<<<<<< HEAD
            <!-- Sección para configuración de base de datos -->
            <div class="database-section">
                <p class="database-question">¿Sin Base de Datos?</p>
                <!-- Mostrar mensaje de éxito si la operación fue exitosa -->
=======
            <div class="database-section">
                <p class="database-question">¿Sin Base de Datos?</p>
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
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
<<<<<<< HEAD
                <!-- Mostrar mensaje de error si hubo algún problema -->
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                <?php if (isset($_GET['error'])): ?>
                    <div class="database-message database-error">
                        ❌ Error: <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>
<<<<<<< HEAD
                <!-- Botones para crear base de datos e insertar tablas -->
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                <div class="database-buttons">
                    <button type="button" class="database-btn" onclick="window.location.href='Database/crear_bd.php'">Crear Base de Datos</button>
                    <button type="button" class="database-btn" onclick="window.location.href='Database/crear_tablas.php'">Insertar Tablas</button>
                </div>
            </div>
<<<<<<< HEAD
            <!-- Formulario de login para administrador (oculto por defecto) -->
            <div class="login-form-container" id="login-form-container" style="display: none;">
                <form class="login-form" id="login-form" method="post" action="admin_login.php">
                    <!-- Campo oculto CSRF para protección -->
                    <?php echo csrfField(); ?>
                    <!-- Campo de usuario -->
=======
            <div class="login-form-container" id="login-form-container" style="display: none;">
                <form class="login-form" id="login-form" method="post" action="admin_login.php">
                    <?php echo csrfField(); ?>
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                    <div class="form-group">
                        <label for="usuario">Usuario</label>
                        <input type="text" id="usuario" name="usuario" required autocomplete="username" placeholder="admin">
                    </div>
<<<<<<< HEAD
                    <!-- Campo de contraseña -->
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="password">
                    </div>
<<<<<<< HEAD
                    <!-- Botón para enviar formulario -->
                    <button type="submit" class="login-submit">Entrar</button>
                    <!-- Botón para cancelar y cerrar formulario -->
=======
                    <button type="submit" class="login-submit">Entrar</button>
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
                    <button type="button" class="login-cancel" id="login-cancel">Cancelar</button>
                </form>
            </div>
        </section>
    </main>
<<<<<<< HEAD
    <!-- Script para manejar la interacción del formulario de login -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener elementos del DOM
=======
    <script>
        document.addEventListener('DOMContentLoaded', function() {
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
            const adminOption = document.getElementById('admin-option');
            const loginFormContainer = document.getElementById('login-form-container');
            const loginCancel = document.getElementById('login-cancel');

<<<<<<< HEAD
            // Al hacer clic en opción de administrador, mostrar formulario de login
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
            adminOption.addEventListener('click', function() {
                loginFormContainer.style.display = 'block';
            });

<<<<<<< HEAD
            // Al hacer clic en cancelar, ocultar formulario de login
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
            loginCancel.addEventListener('click', function() {
                loginFormContainer.style.display = 'none';
            });
        });
    </script>
</body>
</html>