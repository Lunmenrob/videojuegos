// Clase principal de la aplicación de videojuegos
class VideojuegosApp {
    constructor() {
        this.currentFilter = 'todos';// Filtro actual de plataforma (todos por defecto)
        this.currentSort = 'nombre';// Ordenamiento actual (nombre por defecto)
        this.currentGame = null;// Juego actual seleccionado
        this.games = [];// Array de juegos cargados
        this.isPublicPage = window.location.pathname.includes('/publico/');// Verifica si es la página pública
        this.apiBasePath = this.isPublicPage ? '../api/' : 'api/';// Ruta base de la API según si es página pública o admin
        this.assetBasePath = this.isPublicPage ? '../' : '';// Ruta base de assets según si es página pública o admin
        // Página de detalles según si es página pública o admin
        this.detailsPage = this.isPublicPage ? 'public_detalles.php' : 'detalles.php';
        // Inicializa la aplicación
        this.init();
    }

    // Método de inicialización
    init() {
        this.setupEventListeners();// Configura los event listeners
        this.loadGames();// Carga los juegos
    }

    // Configura los event listeners de la interfaz
    setupEventListeners() {
        document.getElementById('searchBtn').addEventListener('click', () => this.searchGames());// Event listener para el botón de búsqueda
        document.getElementById('searchInput').addEventListener('keyup', (e) => {// Event listener para la tecla Enter en el input de búsqueda
            if (e.key === 'Enter') this.searchGames();
        });

        // Event listener para el filtro de plataforma
        document.getElementById('platformFilter').addEventListener('change', (e) => this.setFilter(e.target.value));

        // Event listener para el ordenamiento
        document.getElementById('sortFilter').addEventListener('change', (e) => this.setSort(e.target.value));
    }

    // Carga los juegos desde la API
    async loadGames() {
        try {
            const response = await fetch(`${this.apiBasePath}games.php`);// Realiza la petición fetch a la API de juegos
            this.games = await response.json();// Parsea la respuesta JSON y la asigna al array de juegos
            this.renderGames();// Renderiza los juegos
        } catch (error) {
            console.error('Error al cargar videojuegos:', error);// Log de error si falla la carga
            this.showMessage('Error al cargar los videojuegos', 'error');// Muestra mensaje de error
        }
    }

    // Renderiza los juegos en el contenedor
    renderGames() {
        const container = document.getElementById('gamesContainer');// Obtiene el contenedor de juegos
        let filteredGames = this.filterGamesByPlatform(this.games);// Filtra los juegos por plataforma
        filteredGames = this.sortGames(filteredGames);// Ordena los juegos
        
        // Genera el HTML para cada juego
        container.innerHTML = filteredGames.map(game => `
            <a href="${this.detailsPage}?id=${game.id}" class="game-item-link">
                <div class="game-item">
                    <div class="game-icon">
                        ${game.imagen_url ? 
                            `<img src="${game.imagen_url}" alt="${game.titulo}" onerror="this.style.display='none';">` : 
                            ''
                        }
                        <span class="platform-badge">${game.plataforma}</span>
                    </div>
                <div class="game-info">
                    <h3 class="game-title">${game.titulo}</h3>
                    <div class="trophies-info">
                        <div class="trophy-icons">
                            <div class="trophy-icon platinum">
                                <img src="${this.assetBasePath}interfaz/trofeos/trofeos/platino.png" alt="Platino" class="trophy-img">
                                <span class="trophy-count">${game.platino_conseguido ? 1 : 0}</span>
                            </div>
                            <div class="trophy-icon gold">
                                <img src="${this.assetBasePath}interfaz/trofeos/trofeos/oro.png" alt="Oro" class="trophy-img">
                                <span class="trophy-count">${game.oro_conseguidos || 0}</span>
                            </div>
                            <div class="trophy-icon silver">
                                <img src="${this.assetBasePath}interfaz/trofeos/trofeos/plata.png" alt="Plata" class="trophy-img">
                                <span class="trophy-count">${game.plata_conseguidos || 0}</span>
                            </div>
                            <div class="trophy-icon bronze">
                                <img src="${this.assetBasePath}interfaz/trofeos/trofeos/bronce.png" alt="Bronce" class="trophy-img">
                                <span class="trophy-count">${game.bronce_conseguidos || 0}</span>
                            </div>
                        </div>
                        <div class="trophy-total">
                            <span class="trophy-text">${(game.bronce_conseguidos || 0) + (game.plata_conseguidos || 0) + (game.oro_conseguidos || 0) + (game.platino_conseguido ? 1 : 0)} / ${game.total_trofeos || 0}</span>
                        </div>
                    </div>
                </div>
                <div class="game-percentage">
                    <span class="percentage-text">${game.porcentaje_completado || 0}%</span>
                </div>
                </div>
            </a>
        `).join('');
    }

    // Filtra los juegos por plataforma
    filterGamesByPlatform(games) {
        if (this.currentFilter === 'todos') return games;// Si el filtro es 'todos', retorna todos los juegos sin filtrar
        return games.filter(game => // Filtra los juegos según la plataforma seleccionada
            game.plataforma.toLowerCase() === this.currentFilter || 
            (this.currentFilter === 'ambos' && game.plataforma === 'AMBOS')
        );
    }

    // Establece el filtro de plataforma
    setFilter(filter) {
        this.currentFilter = filter;// Actualiza el filtro actual
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.filter === filter);
        });
        this.renderGames();// Renderiza los juegos con el nuevo filtro
    }

    // Establece el ordenamiento
    setSort(sort) {
        this.currentSort = sort;// Actualiza el ordenamiento actual
        document.querySelectorAll('.sort-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.sort === sort);
        });
        this.renderGames();// Renderiza los juegos con el nuevo ordenamiento
    }

    // Ordena los juegos según el criterio seleccionado
    sortGames(games) {
        let sortedGames = [...games];// Crea una copia del array de juegos

        // Log para depuración
        console.log('sortGames - currentSort:', this.currentSort);
        console.log('sortGames - games before sort:', games.map(g => ({ titulo: g.titulo, porcentaje: g.porcentaje_completado })));

        // Switch según el tipo de ordenamiento
        switch (this.currentSort) {
            case 'nombre':
                sortedGames.sort((a, b) => a.titulo.localeCompare(b.titulo));// Ordena alfabéticamente por título
                break;
            case 'completados':
                // Filtrar solo juegos con platino o 100% de completado
                sortedGames = sortedGames.filter(game => {
                    const aPercent = parseFloat(game.porcentaje_completado) || 0;
                    return game.platino_conseguido || aPercent === 100;
                });
                // Ordenar por porcentaje de completado (mayor a menor)
                sortedGames.sort((a, b) => {
                    const aPercent = parseFloat(a.porcentaje_completado) || 0;
                    const bPercent = parseFloat(b.porcentaje_completado) || 0;
                    return bPercent - aPercent;
                });
                break;
            case 'incompletos':
                // Filtrar solo juegos sin platino y menos de 100%
                sortedGames = sortedGames.filter(game => {
                    const aPercent = parseFloat(game.porcentaje_completado) || 0;
                    return !game.platino_conseguido && aPercent < 100;
                });
                // Ordenar por porcentaje de completado (menor a mayor)
                sortedGames.sort((a, b) => {
                    const aPercent = parseFloat(a.porcentaje_completado) || 0;
                    const bPercent = parseFloat(b.porcentaje_completado) || 0;
                    return aPercent - bPercent;
                });
                break;
            default:
                // Ordenar por nombre por defecto (A-Z)
                sortedGames.sort((a, b) => a.titulo.localeCompare(b.titulo));
                break;
        }

        // Log para depuración
        console.log('sortGames - games after sort:', sortedGames.map(g => ({ titulo: g.titulo, porcentaje: g.porcentaje_completado })));

        // Retorna los juegos ordenados
        return sortedGames;
    }

    // Busca juegos por término de búsqueda
    async searchGames() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();// Obtiene el término de búsqueda y lo convierte a minúsculas
        // Si no hay término de búsqueda, recarga todos los juegos
        if (!searchTerm) {
            
            await this.loadGames();// Recargar todos los juegos cuando se borra la búsqueda
            return;
        }

        try {
            // Realiza la petición fetch con el término de búsqueda
            const response = await fetch(`${this.apiBasePath}games.php?search=${encodeURIComponent(searchTerm)}`);
            this.games = await response.json();// Parsea la respuesta JSON y la asigna al array de juegos
            this.renderGames();// Renderiza los juegos filtrados
        } catch (error) {
            console.error('Error en búsqueda:', error);// Log de error si falla la búsqueda
        }
    }

    // Muestra un mensaje flotante en la pantalla
    showMessage(message, type = 'info') {
        // Crea un elemento div para el mensaje
        const messageDiv = document.createElement('div');
        // Establece la clase del mensaje según el tipo
        messageDiv.className = `message message-${type}`;
        // Establece el texto del mensaje
        messageDiv.textContent = message;
        // Establece los estilos inline del mensaje
        messageDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            background: ${type === 'error' ? '#e53e3e' : type === 'success' ? '#48bb78' : '#667eea'};
            color: white;
            border-radius: 8px;
            z-index: 2000;
            animation: slideIn 0.3s ease;
        `;
        
        // Agrega el mensaje al body
        document.body.appendChild(messageDiv);
        
        // Elimina el mensaje después de 3 segundos
        setTimeout(() => {
            messageDiv.remove();
        }, 3000);
    }
}

// Event listener que se ejecuta cuando el DOM está completamente cargado
document.addEventListener('DOMContentLoaded', () => {
    // Crea una instancia de la aplicación de videojuegos
    const app = new VideojuegosApp();
});
