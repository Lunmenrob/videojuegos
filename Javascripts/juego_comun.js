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
                // Establece el ancho a 50px con !important
                img.style.setProperty('width', '50px', 'important');
                // Establece el alto a 50px con !important
                img.style.setProperty('height', '50px', 'important');
                // Establece el ancho máximo a 50px con !important
                img.style.setProperty('max-width', '50px', 'important');
                // Establece el alto máximo a 50px con !important
                img.style.setProperty('max-height', '50px', 'important');
                // Establece object-fit a cover con !important
                img.style.setProperty('object-fit', 'cover', 'important');
            });
        });
        // También corrige las imágenes en el header del formulario de edición de DLC
        const dlcEditHeaders = document.querySelectorAll('#dlcs-list > div > div[style*="display: flex"]');
        dlcEditHeaders.forEach(header => {
            // Obtiene la imagen del header
            const img = header.querySelector('img');
            if (img) {
                // Establece el ancho a 50px con !important
                img.style.setProperty('width', '50px', 'important');
                // Establece el alto a 50px con !important
                img.style.setProperty('height', '50px', 'important');
                // Establece el ancho máximo a 50px con !important
                img.style.setProperty('max-width', '50px', 'important');
                // Establece el alto máximo a 50px con !important
                img.style.setProperty('max-height', '50px', 'important');
            }
        });
    }
    // Ejecuta la función inmediatamente
    fixTrophyImageSizes();
    // Ejecuta la función nuevamente después de 1 segundo (para asegurar que se aplique a elementos dinámicos)
    setTimeout(fixTrophyImageSizes, 1000);
});

// Funciones para manejar mapas interactivos
// Array para almacenar los datos de los mapas
let mapasData = [];
// Variable para almacenar el ID del mapa que se está editando
let editingMapaId = null;

// Función para agregar o editar un mapa
function addMapaItem() {
    // Obtiene el input de URL
    const urlInput = document.getElementById('mapa-url');
    // Obtiene el input de nombre
    const nombreInput = document.getElementById('mapa-nombre');
    // Obtiene y limpia el valor de la URL
    const url = urlInput.value.trim();
    // Obtiene y limpia el valor del nombre
    const nombre = nombreInput.value.trim();

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
            // Actualiza la URL del mapa
            mapasData[index].url = url;
            // Actualiza el nombre del mapa
            mapasData[index].nombre = nombre;
        }
        // Limpia el ID de edición
        editingMapaId = null;
    } else {
        // Agregar nuevo mapa
        const mapa = {
            // Genera un ID único usando timestamp
            id: Date.now(),
            url: url,
            nombre: nombre
        };
        // Agrega el mapa al array
        mapasData.push(mapa);
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
    // Establece el ID del mapa que se está editando
    editingMapaId = id;
}

// Función para renderizar la lista de mapas
function renderMapasList() {
    // Obtiene el contenedor de la lista
    const container = document.getElementById('mapas-list');
    // Si no existe el contenedor, retorna
    if (!container) return;

    // Limpia el contenido del contenedor
    container.innerHTML = '';

    // Itera sobre cada mapa en el array
    mapasData.forEach(mapa => {
        // Crea un elemento div para el item
        const item = document.createElement('div');
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
        // Agrega el item al contenedor
        container.appendChild(item);
    });
}

// Función para actualizar el input JSON con los datos de mapas
function updateMapasJson() {
    // Obtiene el input JSON
    const jsonInput = document.getElementById('mapas-json');
    // Si existe el input
    if (jsonInput) {
        // Convierte el array de mapas a JSON y lo establece en el input
        jsonInput.value = JSON.stringify(mapasData);
    }
}

// Función para cargar mapas desde una cadena JSON
function loadMapasFromJson(jsonString) {
    try {
        // Parsea el JSON y lo asigna al array de mapas
        mapasData = JSON.parse(jsonString);
        // Renderiza la lista de mapas
        renderMapasList();
    } catch (e) {
        // Log de error si falla el parseo
        console.error('Error cargando mapas:', e);
        // Establece el array vacío en caso de error
        mapasData = [];
    }
}
