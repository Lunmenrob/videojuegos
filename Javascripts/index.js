class VideojuegosApp {
    constructor() {
        this.currentFilter = 'todos';
        this.currentSort = 'nombre';
        this.currentGame = null;
        this.games = [];
        this.isPublicPage = window.location.pathname.includes('/publico/');
        this.apiBasePath = this.isPublicPage ? '../api/' : 'api/';
        this.assetBasePath = this.isPublicPage ? '../' : '';
        this.detailsPage = this.isPublicPage ? 'public_detalles.php' : 'detalles.php';
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadGames();
    }

    setupEventListeners() {
        // Búsqueda
        document.getElementById('searchBtn').addEventListener('click', () => this.searchGames());
        document.getElementById('searchInput').addEventListener('keyup', (e) => {
            if (e.key === 'Enter') this.searchGames();
        });

        // Filtros
        document.getElementById('platformFilter').addEventListener('change', (e) => this.setFilter(e.target.value));

        // Ordenamiento
        document.getElementById('sortFilter').addEventListener('change', (e) => this.setSort(e.target.value));
    }

    async loadGames() {
        try {
            const response = await fetch(`${this.apiBasePath}games.php`);
            this.games = await response.json();
            this.renderGames();
        } catch (error) {
            console.error('Error al cargar videojuegos:', error);
            this.showMessage('Error al cargar los videojuegos', 'error');
        }
    }

    renderGames() {
        const container = document.getElementById('gamesContainer');
        let filteredGames = this.filterGamesByPlatform(this.games);
        filteredGames = this.sortGames(filteredGames);
        
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
                                <img src="${this.assetBasePath}interfaz/trofeos/platino.png" alt="Platino" class="trophy-img">
                                <span class="trophy-count">${game.platino_conseguido ? 1 : 0}</span>
                            </div>
                            <div class="trophy-icon gold">
                                <img src="${this.assetBasePath}interfaz/trofeos/oro.png" alt="Oro" class="trophy-img">
                                <span class="trophy-count">${game.oro_conseguidos || 0}</span>
                            </div>
                            <div class="trophy-icon silver">
                                <img src="${this.assetBasePath}interfaz/trofeos/plata.png" alt="Plata" class="trophy-img">
                                <span class="trophy-count">${game.plata_conseguidos || 0}</span>
                            </div>
                            <div class="trophy-icon bronze">
                                <img src="${this.assetBasePath}interfaz/trofeos/bronce.png" alt="Bronce" class="trophy-img">
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

    filterGamesByPlatform(games) {
        if (this.currentFilter === 'todos') return games;
        return games.filter(game => 
            game.plataforma.toLowerCase() === this.currentFilter || 
            (this.currentFilter === 'ambos' && game.plataforma === 'AMBOS')
        );
    }

    setFilter(filter) {
        this.currentFilter = filter;
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.filter === filter);
        });
        this.renderGames();
    }

    setSort(sort) {
        this.currentSort = sort;
        document.querySelectorAll('.sort-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.sort === sort);
        });
        this.renderGames();
    }

    sortGames(games) {
        let sortedGames = [...games];

        switch (this.currentSort) {
            case 'nombre':
                sortedGames.sort((a, b) => a.titulo.localeCompare(b.titulo));
                break;
            case 'completados':
                sortedGames.sort((a, b) => {
                    const aCompleted = a.platino_conseguido ? 1 : 0;
                    const bCompleted = b.platino_conseguido ? 1 : 0;
                    return bCompleted - aCompleted;
                });
                break;
            case 'incompletos':
                sortedGames.sort((a, b) => {
                    const aCompleted = a.platino_conseguido ? 1 : 0;
                    const bCompleted = b.platino_conseguido ? 1 : 0;
                    return aCompleted - bCompleted;
                });
                break;
            default:
                // Ordenar por nombre por defecto (A-Z)
                sortedGames.sort((a, b) => a.titulo.localeCompare(b.titulo));
                break;
        }

        return sortedGames;
    }

    async searchGames() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        if (!searchTerm) {
            this.renderGames();
            return;
        }

        try {
            const response = await fetch(`${this.apiBasePath}games.php?search=${encodeURIComponent(searchTerm)}`);
            this.games = await response.json();
            this.renderGames();
        } catch (error) {
            console.error('Error en búsqueda:', error);
        }
    }

    showMessage(message, type = 'info') {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message message-${type}`;
        messageDiv.textContent = message;
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
        
        document.body.appendChild(messageDiv);
        
        setTimeout(() => {
            messageDiv.remove();
        }, 3000);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const app = new VideojuegosApp();
});
