<?php
require_once '../csrf.php';
?>
<header class="site-header">
    <div class="header-container">
        <div class="header-left">
            <a href="/Prácticas/videojuegos/home.php" class="logo-link">
                <img src="https://playstation-stores.com/wp-content/uploads/2020/11/Playstation-Wholesale-Store-Logo-White-300x230.png" alt="PlayStation Logo" class="playstation-logo">
            </a>
        </div>
        <div class="header-right">
            <button class="login-btn" id="header-login-btn">
                <i class="fas fa-user"></i>
                <span>Iniciar Sesión</span>
            </button>
            <div class="header-login-dropdown" id="header-login-dropdown" style="display: none;">
                <form class="header-login-form" method="post" action="/Prácticas/videojuegos/admin_login.php">
                    <?php echo csrfField(); ?>
                    <div class="header-login-form-group">
                        <label for="header-usuario">Usuario</label>
                        <input type="text" id="header-usuario" name="usuario" required autocomplete="username" placeholder="admin">
                    </div>
                    <div class="header-login-form-group">
                        <label for="header-password">Contraseña</label>
                        <input type="password" id="header-password" name="password" required autocomplete="current-password" placeholder="password">
                    </div>
                    <button type="submit" class="header-login-submit">Entrar</button>
                    <button type="button" class="header-login-cancel" id="header-login-cancel">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</header>

<style>
    .site-header {
        background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
        border-bottom: 2px solid #0070d1;
        padding: 0.7rem 1.6rem;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 9998;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    .header-container {
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
    }

    .header-left {
        display: flex;
        align-items: center;
    }

    .logo-link {
        text-decoration: none;
        display: flex;
        align-items: center;
        margin-left: 0.5rem;
    }

    .playstation-logo {
        height: 38px;
        width: auto;
        transition: transform 0.3s ease;
    }

    .logo-link:hover .playstation-logo {
        transform: scale(1.05);
    }

    .header-right {
        display: flex;
        align-items: center;
        position: relative;
    }

    .login-btn {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #0070d1 0%, #0056b3 100%);
        color: white;
        text-decoration: none;
        border-radius: 999px;
        font-weight: 600;
        font-size: 0.82rem;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        margin-right: 0.5rem;
    }

    .login-btn:hover {
        background: linear-gradient(135deg, #0056b3 0%, #003d82 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 112, 209, 0.4);
        border-color: #0070d1;
    }

    .login-btn i {
        font-size: 0.9rem;
    }

    .header-login-dropdown {
        position: absolute;
        top: calc(100% + 0.5rem);
        right: 0;
        background: rgba(22, 22, 22, 0.98);
        border: 1px solid #2a2a2a;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        min-width: 300px;
        z-index: 1001;
    }

    .header-login-form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .header-login-form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .header-login-form label {
        font-weight: 600;
        font-size: 0.9rem;
        color: #c4c4c4;
    }

    .header-login-form input {
        padding: 0.75rem 0.9rem;
        border-radius: 8px;
        border: 1px solid #3a3a3a;
        background: #232323;
        color: #fff;
        font-size: 0.95rem;
    }

    .header-login-form input:focus {
        outline: none;
        border-color: #0070d1;
        box-shadow: 0 0 0 3px rgba(0, 112, 209, 0.2);
    }

    .header-login-submit {
        padding: 0.85rem 1rem;
        border: none;
        border-radius: 8px;
        background: linear-gradient(135deg, #0070d1 0%, #0056b3 100%);
        color: #fff;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .header-login-submit:hover {
        background: linear-gradient(135deg, #0056b3 0%, #003d82 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 112, 209, 0.4);
    }

    .header-login-cancel {
        padding: 0.85rem 1rem;
        border: 1px solid #3a3a3a;
        border-radius: 8px;
        background: transparent;
        color: #c4c4c4;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .header-login-cancel:hover {
        background: #2a2a2a;
        color: #fff;
    }

    @media (max-width: 768px) {
        .site-header {
            padding: 0.5rem 0.8rem;
        }

        .header-container {
            flex-direction: column;
            gap: 1rem;
        }

        .playstation-logo {
            height: 32px;
        }

        .login-btn {
            padding: 0.45rem 0.8rem;
            font-size: 0.78rem;
        }

        .header-login-dropdown {
            right: 0.8rem;
            min-width: 250px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginBtn = document.getElementById('header-login-btn');
    const loginDropdown = document.getElementById('header-login-dropdown');
    const loginCancel = document.getElementById('header-login-cancel');

    console.log('Login button:', loginBtn);
    console.log('Login dropdown:', loginDropdown);
    console.log('Login cancel:', loginCancel);

    if (loginBtn && loginDropdown && loginCancel) {
        loginBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Login button clicked');
            loginDropdown.style.display = loginDropdown.style.display === 'none' ? 'block' : 'none';
            console.log('Dropdown display:', loginDropdown.style.display);
        });

        loginCancel.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Cancel clicked');
            loginDropdown.style.display = 'none';
        });

        // Cerrar al hacer clic fuera del dropdown
        document.addEventListener('click', function(e) {
            if (!loginDropdown.contains(e.target) && e.target !== loginBtn) {
                loginDropdown.style.display = 'none';
            }
        });
    } else {
        console.error('No se encontraron los elementos del login');
    }
});
</script>
