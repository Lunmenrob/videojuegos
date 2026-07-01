<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo Público</title>
    <link rel="stylesheet" href="../estilos/css/index.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
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
        }

        .dropdown-group select:hover {
            background: #3a3a3a;
            border-color: #4a4a4a;
        }

        .dropdown-group select:focus {
            outline: none;
            border-color: #0071e3;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="page-content">
    
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
    </div>

    <script>window.detailsPage = 'public_detalles.php';</script>
    <script src="../Javascripts/index.js"></script>
    <?php include '../estilos/footer.php'; ?>
</body>
</html>
