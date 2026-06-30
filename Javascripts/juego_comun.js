// Funciones compartidas para agregar_juego.php y editar_juego.php

document.addEventListener('DOMContentLoaded', function() {
    function fixTrophyImageSizes() {
        const containers = document.querySelectorAll('.trophy-item-info, .dlc-trophy-form, .trophy-form-inline, .trophy-item-edit, #trophies-list, #dlc-trophies-list, .dlc-form-inline');
        containers.forEach(container => {
            const images = container.querySelectorAll('img');
            images.forEach(img => {
                img.style.setProperty('width', '50px', 'important');
                img.style.setProperty('height', '50px', 'important');
                img.style.setProperty('max-width', '50px', 'important');
                img.style.setProperty('max-height', '50px', 'important');
                img.style.setProperty('object-fit', 'cover', 'important');
            });
        });
        // Also fix images in DLC edit form header
        const dlcEditHeaders = document.querySelectorAll('#dlcs-list > div > div[style*="display: flex"]');
        dlcEditHeaders.forEach(header => {
            const img = header.querySelector('img');
            if (img) {
                img.style.setProperty('width', '50px', 'important');
                img.style.setProperty('height', '50px', 'important');
                img.style.setProperty('max-width', '50px', 'important');
                img.style.setProperty('max-height', '50px', 'important');
            }
        });
    }
    fixTrophyImageSizes();
    setTimeout(fixTrophyImageSizes, 1000);
});

// Funciones para manejar mapas interactivos
let mapasData = [];
let editingMapaId = null;

function addMapaItem() {
    const urlInput = document.getElementById('mapa-url');
    const nombreInput = document.getElementById('mapa-nombre');
    const url = urlInput.value.trim();
    const nombre = nombreInput.value.trim();

    if (!url || !nombre) {
        alert('Por favor, ingresa tanto la URL como el nombre del mapa');
        return;
    }

    if (!/^https?:\/\//i.test(url)) {
        alert('La URL debe comenzar con http:// o https://');
        return;
    }

    if (editingMapaId) {
        // Editar mapa existente
        const index = mapasData.findIndex(m => m.id === editingMapaId);
        if (index !== -1) {
            mapasData[index].url = url;
            mapasData[index].nombre = nombre;
        }
        editingMapaId = null;
    } else {
        // Agregar nuevo mapa
        const mapa = {
            id: Date.now(),
            url: url,
            nombre: nombre
        };
        mapasData.push(mapa);
    }

    renderMapasList();
    updateMapasJson();

    urlInput.value = '';
    nombreInput.value = '';
}

function removeMapaItem(id) {
    mapasData = mapasData.filter(m => m.id !== id);
    renderMapasList();
    updateMapasJson();
}

function editMapaItem(id) {
    const mapa = mapasData.find(m => m.id === id);
    if (!mapa) return;

    const urlInput = document.getElementById('mapa-url');
    const nombreInput = document.getElementById('mapa-nombre');
    
    urlInput.value = mapa.url;
    nombreInput.value = mapa.nombre;
    editingMapaId = id;
}

function renderMapasList() {
    const container = document.getElementById('mapas-list');
    if (!container) return;

    container.innerHTML = '';

    mapasData.forEach(mapa => {
        const item = document.createElement('div');
        item.className = 'mapa-item';
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
        container.appendChild(item);
    });
}

function updateMapasJson() {
    const jsonInput = document.getElementById('mapas-json');
    if (jsonInput) {
        jsonInput.value = JSON.stringify(mapasData);
    }
}

function loadMapasFromJson(jsonString) {
    try {
        mapasData = JSON.parse(jsonString);
        renderMapasList();
    } catch (e) {
        console.error('Error cargando mapas:', e);
        mapasData = [];
    }
}
