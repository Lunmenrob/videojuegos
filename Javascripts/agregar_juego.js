class AgregarJuego {
    constructor() {
        this.trophyCount = 1;
        this.init();
    }

    init() {
        this.setupEventListeners();
    }

    setupEventListeners() {
        const form = document.getElementById('gameForm');
        if (form) {
            form.addEventListener('submit', (e) => this.handleSubmit(e));
        }
    }

    async handleSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const gameTitle = formData.get('titulo');
        
        // Crear carpetas si no existen
        await this.createFolders(gameTitle);
        
        // Subir archivos
        const bannerPath = await this.uploadFile(formData.get('banner'), 'Banners', gameTitle);
        const iconPath = await this.uploadFile(formData.get('icono'), 'icono', gameTitle);
        
        // Preparar datos del juego
        const gameData = {
            titulo: gameTitle,
            plataforma: formData.get('plataforma'),
            fecha_lanzamiento: formData.get('fecha_lanzamiento'),
            genero: formData.get('genero'),
            desarrollador: formData.get('desarrollador'),
            banner_url: bannerPath,
            icono_url: iconPath,
            total_trofeos: formData.get('total_trofeos'),
            descripcion: formData.get('descripcion')
        };

        try {
            // Guardar juego
            const gameResponse = await fetch('api/game.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(gameData)
            });

            if (!gameResponse.ok) {
                throw new Error('Error al guardar el juego');
            }

            const gameResult = await gameResponse.json();
            const gameId = gameResult.id;

            // Guardar trofeos
            await this.saveTrophies(formData, gameId, gameTitle);

            this.showMessage('Juego agregado correctamente', 'success');
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 2000);
        } catch (error) {
            console.error('Error:', error);
            this.showMessage('Error al agregar el juego', 'error');
        }
    }

    async createFolders(gameTitle) {
        const folders = [
            `interfaz/Banners`,
            `interfaz/icono`,
            `interfaz/trofeos/${this.sanitizeFolderName(gameTitle)}`
        ];

        for (const folder of folders) {
            try {
                await fetch('api/create_folder.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ folder: folder })
                });
            } catch (error) {
                console.log('Folder may already exist:', folder);
            }
        }
    }

    async uploadFile(file, folder, gameTitle) {
        if (!file || file.size === 0) return null;

        const formData = new FormData();
        formData.append('file', file);
        formData.append('folder', folder);
        formData.append('gameTitle', gameTitle);

        try {
            const response = await fetch('api/upload_file.php', {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                const result = await response.json();
                return result.path;
            }
        } catch (error) {
            console.error('Error uploading file:', error);
        }
        return null;
    }

    async saveTrophies(formData, gameId, gameTitle) {
        const trophyData = [];

        // Recopilar datos de trofeos
        for (let i = 0; i < this.trophyCount; i++) {
            const tipo = formData.get(`trofeos[${i}][tipo]`);
            const nombre = formData.get(`trofeos[${i}][nombre]`);
            const descripcion = formData.get(`trofeos[${i}][descripcion]`);
            const iconoUrl = formData.get(`trofeos[${i}][icono_url]`);

            if (tipo && nombre) {
                trophyData.push({
                    videojuego_id: gameId,
                    nombre_trofeo: nombre,
                    descripcion: descripcion,
                    tipo: tipo,
                    icono_url: iconoUrl
                });
            }
        }

        // Guardar trofeos en la base de datos
        if (trophyData.length > 0) {
            await fetch('api/trophies.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ trophies: trophyData })
            });
        }
    }

    sanitizeFolderName(name) {
        return name.toLowerCase()
                   .replace(/[^a-z0-9]/g, '')
                   .replace(/\s+/g, '');
    }

    showMessage(message, type) {
        const messageDiv = document.getElementById('message');
        messageDiv.textContent = message;
        messageDiv.className = `message ${type}`;
        
        setTimeout(() => {
            messageDiv.classList.add('hidden');
        }, 5000);
    }
}

// Funciones globales para los trofeos
let trophyCount = 1;

function addTrophy() {
    trophyCount++;
    const container = document.getElementById('trophiesContainer');
    
    const trophyItem = document.createElement('div');
    trophyItem.className = 'trophy-item';
    trophyItem.innerHTML = `
        <div class="trophy-header">
            <span class="trophy-number">${trophyCount}</span>
            <button type="button" class="remove-trophy" onclick="removeTrophy(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="trophy-content">
            <div class="form-row">
                <div class="form-group">
                    <label>Tipo de Trofeo</label>
                    <select name="trofeos[${trophyCount - 1}][tipo]" class="trophy-type">
                        <option value="BRONCE">Bronce</option>
                        <option value="PLATA">Plata</option>
                        <option value="ORO">Oro</option>
                        <option value="PLATINO">Platino</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nombre del Trofeo</label>
                    <input type="text" name="trofeos[${trophyCount - 1}][nombre]" placeholder="Ej: Primer paso">
                </div>
            </div>
            <div class="form-group full-width">
                <label>Descripción</label>
                <textarea name="trofeos[${trophyCount - 1}][descripcion]" rows="2" placeholder="Ej: Completa el primer nivel"></textarea>
            </div>
            <div class="form-group">
                <label>URL del Icono del Trofeo</label>
                <input type="url" name="trofeos[${trophyCount - 1}][icono_url]" placeholder="https://..." class="url-input">
                <small>Enlace a la imagen del trofeo</small>
            </div>
        </div>
    `;
    
    container.appendChild(trophyItem);
}

function removeTrophy(button) {
    const trophyItem = button.closest('.trophy-item');
    trophyItem.remove();
    
    // Reenumerar los trofeos restantes
    const remainingTrophies = document.querySelectorAll('.trophy-item');
    remainingTrophies.forEach((trophy, index) => {
        trophy.querySelector('.trophy-number').textContent = index + 1;
    });
    
    trophyCount = remainingTrophies.length;
}

document.addEventListener('DOMContentLoaded', () => {
    new AgregarJuego();
    setupSpoilers();
});

// Configurar spoilers para colapsar/expandir al hacer clic
function setupSpoilers() {
    const spoilers = document.querySelectorAll('.ql-spoiler');
    spoilers.forEach(spoiler => {
        spoiler.addEventListener('click', function() {
            this.classList.toggle('revealed');
        });
    });

    // Configurar MutationObserver para detectar spoilers nuevos en editores
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.nodeType === 1) { // Element node
                    const newSpoilers = node.querySelectorAll ? node.querySelectorAll('.ql-spoiler') : [];
                    newSpoilers.forEach(spoiler => {
                        spoiler.addEventListener('click', function() {
                            this.classList.toggle('revealed');
                        });
                    });

                    // Si el nodo mismo es un spoiler
                    if (node.classList && node.classList.contains('ql-spoiler')) {
                        node.addEventListener('click', function() {
                            this.classList.toggle('revealed');
                        });
                    }
                }
            });
        });
    });

    // Observar cambios en todo el documento
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
}
