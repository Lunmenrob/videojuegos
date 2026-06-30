// Funciones compartidas para agregar_juego.php y editar_juego.php

// Event listener que se ejecuta cuando el DOM está completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    // Función para corregir los tamaños de las imágenes de trofeos
    function fixTrophyImageSizes() {
        // Selecciona todos los contenedores de trofeos
        const containers = document.querySelectorAll('.trophy-item-info, .dlc-trophy-form, .trophy-form-inline, .trophy-item-edit, #trophies-list, #dlc-trophies-list, .dlc-form-inline');
        // Itera sobre cada contenedor
        containers.forEach(container => {
            // Obtiene todas las imágenes del contenedor
            const images = container.querySelectorAll('img');
            // Itera sobre cada imagen
            images.forEach(img => {
                img.style.setProperty('width', '50px', 'important');
                img.style.setProperty('height', '50px', 'important');
                img.style.setProperty('max-width', '50px', 'important');
                img.style.setProperty('max-height', '50px', 'important');
                img.style.setProperty('object-fit', 'cover', 'important');
            });
        });
        // También corrige las imágenes en el header del formulario de edición de DLC
        const dlcEditHeaders = document.querySelectorAll('#dlcs-list > div > div[style*="display: flex"]');
        dlcEditHeaders.forEach(header => {
            // Obtiene la imagen del header
            const img = header.querySelector('img');
            if (img) {
                img.style.setProperty('width', '50px', 'important');
                img.style.setProperty('height', '50px', 'important');
                img.style.setProperty('max-width', '50px', 'important');
                img.style.setProperty('max-height', '50px', 'important');
            }
        });
    }
    fixTrophyImageSizes();// Ejecuta la función inmediatamente
    // Ejecuta la función nuevamente después de 1 segundo (para asegurar que se aplique a elementos dinámicos)
    setTimeout(fixTrophyImageSizes, 1000);
});

// Funciones para manejar mapas interactivos
let mapasData = [];// Array para almacenar los datos de los mapas
let editingMapaId = null;// Variable para almacenar el ID del mapa que se está editando

// Función para agregar o editar un mapa
function addMapaItem() {
    const urlInput = document.getElementById('mapa-url');// Obtiene el input de URL
    const nombreInput = document.getElementById('mapa-nombre');// Obtiene el input de nombre
    const url = urlInput.value.trim();// Obtiene y limpia el valor de la URL
    const nombre = nombreInput.value.trim();// Obtiene y limpia el valor del nombre

    // Valida que ambos campos estén llenos
    if (!url || !nombre) {
        alert('Por favor, ingresa tanto la URL como el nombre del mapa');
        return;
    }

    // Valida que la URL comience con http:// o https://
    if (!/^https?:\/\//i.test(url)) {
        alert('La URL debe comenzar con http:// o https://');
        return;
    }

    // Si se está editando un mapa existente
    if (editingMapaId) {
        // Busca el índice del mapa en el array
        const index = mapasData.findIndex(m => m.id === editingMapaId);
        if (index !== -1) {
            mapasData[index].url = url;// Actualiza la URL del mapa
            mapasData[index].nombre = nombre;// Actualiza el nombre del mapa
        }
        editingMapaId = null;// Limpia el ID de edición
    } else {
        // Agregar nuevo mapa
        const mapa = {
            // Genera un ID único usando timestamp
            id: Date.now(),
            url: url,
            nombre: nombre
        };
        mapasData.push(mapa);// Agrega el mapa al array
    }

    // Renderiza la lista de mapas
    renderMapasList();
    // Actualiza el JSON de mapas
    updateMapasJson();

    // Limpia los inputs
    urlInput.value = '';
    nombreInput.value = '';
}

// Función para eliminar un mapa
function removeMapaItem(id) {
    // Filtra el array para eliminar el mapa con el ID especificado
    mapasData = mapasData.filter(m => m.id !== id);
    // Renderiza la lista de mapas
    renderMapasList();
    // Actualiza el JSON de mapas
    updateMapasJson();
}

// Función para editar un mapa existente
function editMapaItem(id) {
    // Busca el mapa por su ID
    const mapa = mapasData.find(m => m.id === id);
    // Si no existe el mapa, retorna
    if (!mapa) return;

    // Obtiene el input de URL
    const urlInput = document.getElementById('mapa-url');
    // Obtiene el input de nombre
    const nombreInput = document.getElementById('mapa-nombre');
    
    // Establece la URL del mapa en el input
    urlInput.value = mapa.url;
    // Establece el nombre del mapa en el input
    nombreInput.value = mapa.nombre;
    editingMapaId = id;// Establece el ID del mapa que se está editando
}

// Función para renderizar la lista de mapas
function renderMapasList() {
    const container = document.getElementById('mapas-list');// Obtiene el contenedor de la lista
    if (!container) return;// Si no existe el contenedor, retorna

    // Limpia el contenido del contenedor
    container.innerHTML = '';

    // Itera sobre cada mapa en el array
    mapasData.forEach(mapa => {
        const item = document.createElement('div');// Crea un elemento div para el item
        // Establece la clase del item
        item.className = 'mapa-item';
        // Establece el HTML del item
        item.innerHTML = `
            <span class="mapa-item-name" title="${mapa.nombre}">${mapa.nombre}</span>
            <span class="mapa-item-url" title="${mapa.url}">${mapa.url}</span>
            <button type="button" class="mapa-item-edit" onclick="editMapaItem(${mapa.id})">
                <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="mapa-item-delete" onclick="removeMapaItem(${mapa.id})">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(item);// Agrega el item al contenedor
    });
}

// Función para actualizar el input JSON con los datos de mapas
function updateMapasJson() {
    const jsonInput = document.getElementById('mapas-json');// Obtiene el input JSON
    if (jsonInput) {// Si existe el input
        jsonInput.value = JSON.stringify(mapasData);// Convierte el array de mapas a JSON y lo establece en el input
    }
}

// Función para cargar mapas desde una cadena JSON
function loadMapasFromJson(jsonString) {
    try {
        mapasData = JSON.parse(jsonString);// Parsea el JSON y lo asigna al array de mapas
        renderMapasList();// Renderiza la lista de mapas
    } catch (e) {
        console.error('Error cargando mapas:', e);// Log de error si falla el parseo
        mapasData = [];// Establece el array vacío en caso de error
    }
}
