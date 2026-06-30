// Clase para manejar el formulario de agregar juego
class AgregarJuego {
    constructor() {
        // Contador de trofeos (comienza en 1)
        this.trophyCount = 1;
        // Inicializa la clase
        this.init();
    }

    // Método de inicialización
    init() {
        // Configura los event listeners
        this.setupEventListeners();
    }

    // Configura los event listeners del formulario
    setupEventListeners() {
        // Obtiene el formulario por su ID
        const form = document.getElementById('gameForm');
        // Si existe el formulario, agrega el event listener de submit
        if (form) {
            form.addEventListener('submit', (e) => this.handleSubmit(e));
        }
    }

    // Maneja el envío del formulario
    async handleSubmit(e) {
        // Previene el comportamiento por defecto del formulario
        e.preventDefault();
        
        // Crea un FormData con los datos del formulario
        const formData = new FormData(e.target);
        // Obtiene el título del juego
        const gameTitle = formData.get('titulo');
        
        // Crear carpetas si no existen
        await this.createFolders(gameTitle);
        
        // Subir archivos (banner e icono)
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
            // Guardar juego en la base de datos
            const gameResponse = await fetch('api/game.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(gameData)
            });

            // Verifica si la respuesta fue exitosa
            if (!gameResponse.ok) {
                throw new Error('Error al guardar el juego');
            }

            // Parsea la respuesta JSON
            const gameResult = await gameResponse.json();
            // Obtiene el ID del juego creado
            const gameId = gameResult.id;

            // Guardar trofeos asociados al juego
            await this.saveTrophies(formData, gameId, gameTitle);

            // Muestra mensaje de éxito
            this.showMessage('Juego agregado correctamente', 'success');
            // Redirige a index.php después de 2 segundos
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 2000);
        } catch (error) {
            // Log de error si falla el guardado
            console.error('Error:', error);
            // Muestra mensaje de error
            this.showMessage('Error al agregar el juego', 'error');
        }
    }

    // Crea las carpetas necesarias para el juego
    async createFolders(gameTitle) {
        // Array con las rutas de las carpetas a crear
        const folders = [
            `interfaz/Banners`,
            `interfaz/icono`,
            `interfaz/trofeos/${this.sanitizeFolderName(gameTitle)}`
        ];

        // Itera sobre cada carpeta
        for (const folder of folders) {
            try {
                // Realiza la petición para crear la carpeta
                await fetch('api/create_folder.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ folder: folder })
                });
            } catch (error) {
                // Log si la carpeta ya existe
                console.log('Folder may already exist:', folder);
            }
        }
    }

    // Sube un archivo al servidor
    async uploadFile(file, folder, gameTitle) {
        // Si no hay archivo o está vacío, retorna null
        if (!file || file.size === 0) return null;

        // Crea un FormData con los datos del archivo
        const formData = new FormData();
        formData.append('file', file);
        formData.append('folder', folder);
        formData.append('gameTitle', gameTitle);

        try {
            // Realiza la petición para subir el archivo
            const response = await fetch('api/upload_file.php', {
                method: 'POST',
                body: formData
            });

            // Si la respuesta fue exitosa
            if (response.ok) {
                // Parsea la respuesta JSON
                const result = await response.json();
                // Retorna la ruta del archivo
                return result.path;
            }
        } catch (error) {
            // Log de error si falla la subida
            console.error('Error uploading file:', error);
        }
        // Retorna null si falló
        return null;
    }

    // Guarda los trofeos en la base de datos
    async saveTrophies(formData, gameId, gameTitle) {
        // Array para almacenar los datos de trofeos
        const trophyData = [];

        // Recopilar datos de trofeos del formulario
        for (let i = 0; i < this.trophyCount; i++) {
            // Obtiene los datos de cada trofeo
            const tipo = formData.get(`trofeos[${i}][tipo]`);
            const nombre = formData.get(`trofeos[${i}][nombre]`);
            const descripcion = formData.get(`trofeos[${i}][descripcion]`);
            const iconoUrl = formData.get(`trofeos[${i}][icono_url]`);

            // Si tiene tipo y nombre, agrega al array
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

    // Sanitiza el nombre de la carpeta (elimina caracteres especiales)
    sanitizeFolderName(name) {
        // Convierte a minúsculas
        return name.toLowerCase()
                   // Elimina caracteres que no sean letras o números
                   .replace(/[^a-z0-9]/g, '')
                   // Elimina espacios
                   .replace(/\s+/g, '');
    }

    // Muestra un mensaje en la interfaz
    showMessage(message, type) {
        // Obtiene el elemento de mensaje
        const messageDiv = document.getElementById('message');
        // Establece el texto del mensaje
        messageDiv.textContent = message;
        // Establece la clase del mensaje según el tipo
        messageDiv.className = `message ${type}`;
        
        // Oculta el mensaje después de 5 segundos
        setTimeout(() => {
            messageDiv.classList.add('hidden');
        }, 5000);
    }
}

// Funciones globales para los trofeos
// Contador global de trofeos
let trophyCount = 1;

// Función para agregar un nuevo trofeo al formulario
function addTrophy() {
    // Incrementa el contador de trofeos
    trophyCount++;
    // Obtiene el contenedor de trofeos
    const container = document.getElementById('trophiesContainer');
    
    // Crea un elemento div para el item de trofeo
    const trophyItem = document.createElement('div');
    // Establece la clase del item
    trophyItem.className = 'trophy-item';
    // Establece el HTML del item de trofeo
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
    
    // Agrega el item al contenedor
    container.appendChild(trophyItem);
}

// Función para eliminar un trofeo del formulario
function removeTrophy(button) {
    // Obtiene el item de trofeo más cercano al botón
    const trophyItem = button.closest('.trophy-item');
    // Elimina el item
    trophyItem.remove();
    
    // Reenumerar los trofeos restantes
    const remainingTrophies = document.querySelectorAll('.trophy-item');
    remainingTrophies.forEach((trophy, index) => {
        // Actualiza el número del trofeo
        trophy.querySelector('.trophy-number').textContent = index + 1;
    });
    
    // Actualiza el contador de trofeos
    trophyCount = remainingTrophies.length;
}

// Event listener que se ejecuta cuando el DOM está completamente cargado
document.addEventListener('DOMContentLoaded', () => {
    // Crea una instancia de AgregarJuego
    new AgregarJuego();
    // Configura los spoilers
    setupSpoilers();
});

// Configurar spoilers para colapsar/expandir al hacer clic
function setupSpoilers() {
    // Obtiene todos los spoilers existentes
    const spoilers = document.querySelectorAll('.ql-spoiler');
    // Agrega event listener a cada spoiler
    spoilers.forEach(spoiler => {
        spoiler.addEventListener('click', function() {
            // Alterna la clase revealed al hacer clic
            this.classList.toggle('revealed');
        });
    });

    // Configurar MutationObserver para detectar spoilers nuevos en editores
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                // Si el nodo es un elemento
                if (node.nodeType === 1) { // Element node
                    // Busca spoilers dentro del nodo
                    const newSpoilers = node.querySelectorAll ? node.querySelectorAll('.ql-spoiler') : [];
                    // Agrega event listeners a los nuevos spoilers
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
