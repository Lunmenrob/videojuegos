<?php
require_once 'auth.php';
$loginSuccess = false;
if (!empty($_SESSION['login_success'])) {
    $loginSuccess = true;
    unset($_SESSION['login_success']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Videojuegos PS4/PS5</title>
    <link rel="stylesheet" href="estilos/index.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .game-title {
            font-size: 1.4rem !important;
            font-weight: 200 !important;
            color: #ffffff !important;
            line-height: 1.3 !important;
            margin: 0 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3) !important;
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, sans-serif !important;
        }
        
        .filter-btn.ps4 {
            background: #000000 !important;
            color: #ffffff !important;
            border: 1px solid #000000 !important;
            font-family: 'Zrnic', sans-serif !important;
            font-size: 1.3rem !important;
        }
        
        .filter-btn.ps5 {
            background: #ffffff !important;
            color: #000000 !important;
            border: 1px solid #000000 !important;
            font-family: 'Zrnic', sans-serif !important;
            font-size: 1.3rem !important;
        }
        
        .action-buttons {
            display: flex;
            justify-content: flex-end;
            margin-top: 1rem;
        }
        
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #0071e3 0%, #0056b3 100%);
            border: none;
            border-radius: 6px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 113, 227, 0.4);
        }
        
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        
        .modal-content {
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            padding: 2rem;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .close {
            background: none;
            border: none;
            color: #ffffff;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 4px;
            transition: background 0.2s;
        }
        
        .close:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #ffffff;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            background: #2a2a2a;
            border: 1px solid #3a3a3a;
            border-radius: 6px;
            color: #ffffff;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #0071e3;
        }
        
        .add-btn {
            width: 50px;
            height: 50px;
            background: #252525;
            border: 1px solid #3a3a3a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.2rem;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .add-btn:hover {
            background: #3a3a3a;
            border-color: #4a4a4a;
        }
        
        .platform-badge {
            background: #000000 !important;
            color: white !important;
        }
        
        .search-box button {
            background: #3a3a3a !important;
        }
        
        .search-box button:hover {
            background: #4a4a4a !important;
        }
        
        .filter-dropdowns {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }
        
        .dropdown-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .dropdown-group label {
            color: #ffffff;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .dropdown-group select {
            padding: 0.5rem 1rem;
            background: #252525;
            border: 1px solid #3a3a3a;
            border-radius: 6px;
            color: #ffffff;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Zrnic', sans-serif;
            min-width: 150px;
        }
        
        .dropdown-group select:hover {
            background: #3a3a3a;
            border-color: #4a4a4a;
        }
        
        .dropdown-group select:focus {
            outline: none;
            border-color: #0071e3;
        }

        .login-success-banner {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
            margin-bottom: 1.5rem;
            border-radius: 8px;
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <?php include 'estilos/header.php'; ?>
    <div class="page-content">

    <?php if ($loginSuccess): ?>
    <div class="login-success-banner">
        <i class="fas fa-check-circle"></i>
        <span>Login correcto. Bienvenido, administrador.</span>
    </div>
    <?php endif; ?>

    <main class="container">
        <section class="controls">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Buscar videojuegos...">
                <button id="searchBtn"><i class="fas fa-search"></i></button>
            </div>
            
            <div class="filter-dropdowns">
                <div class="dropdown-group">
                    <label for="platformFilter">Plataforma:</label>
                    <select id="platformFilter">
                        <option value="todos">Todos</option>
                        <option value="ps4">PS4</option>
                        <option value="ps5">PS5</option>
                    </select>
                </div>
                
                <div class="dropdown-group">
                    <label for="sortFilter">Ordenar por:</label>
                    <select id="sortFilter">
                        <option value="nombre" selected>Nombre A-Z</option>
                        <option value="completados">Completados</option>
                        <option value="incompletos">Incompletos</option>
                    </select>
                </div>
            </div>

            <div class="action-buttons">
                <a href="agregar_juego.php" class="add-btn"><i class="fas fa-plus"></i></a>
            </div>
                                </section>

        <section id="gamesContainer" class="games-list">
            <!-- Los videojuegos se cargarán aquí dinámicamente -->
        </section>

        <section id="gameDetails" class="game-details hidden">
            <div class="details-header">
                <button id="backBtn" class="btn-back"><i class="fas fa-arrow-left"></i> Volver</button>
                <h2 id="gameTitle"></h2>
            </div>
            
            <div class="game-info">
                <div class="game-stats">
                    <div class="stat-card">
                        <i class="fas fa-trophy bronze"></i>
                        <span id="bronzeCount">0</span>
                        <p>Bronce</p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-trophy silver"></i>
                        <span id="silverCount">0</span>
                        <p>Plata</p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-trophy gold"></i>
                        <span id="goldCount">0</span>
                        <p>Oro</p>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-crown platinum"></i>
                        <span id="platinumStatus">No</span>
                        <p>Platino</p>
                    </div>
                </div>
                
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill"></div>
                    <span class="progress-text" id="progressText">0%</span>
                </div>
            </div>

            <div class="trophies-section">
                <h3>Trofeos</h3>
                <div class="trophies-controls">
                    <input type="text" id="trophySearch" placeholder="Buscar trofeos...">
                    <select id="trophyFilter">
                        <option value="todos">Todos</option>
                        <option value="conseguidos">Conseguidos</option>
                        <option value="no-conseguidos">No conseguidos</option>
                        <option value="bronce">Bronce</option>
                        <option value="plata">Plata</option>
                        <option value="oro">Oro</option>
                        <option value="platino">Platino</option>
                    </select>
                </div>
                <div id="trophiesList" class="trophies-list">
                    <!-- Los trofeos se cargarán aquí dinámicamente -->
                </div>
            </div>
        </section>
    </main>
        
    <script src="Javascripts/index.js"></script>
    <?php include 'estilos/footer.php'; ?>
    </div>
</body>
</html>
