// Función para procesar imágenes en editores Quill
function setupImageSizing() {
    // Log para depuración
    console.log('setupImageSizing called');
    
    // Función para procesar una imagen individual
    function processImage(img) {
        // Log para depuración
        console.log('Processing image:', img);
        
        // No procesar imágenes que deben respetar su sizing propio
        if (
            img.classList.contains('trophy-card-icon') ||
            img.classList.contains('trophy-card-type-image') ||
            img.classList.contains('trophy-type-img') ||
            img.classList.contains('trophy-mini') ||
            img.classList.contains('trophy-img') ||
            img.classList.contains('classification-img') ||
            img.classList.contains('game-icon-img') ||
            img.id === 'game-icon-img' ||
            img.id === 'game-banner-img' ||
            img.id === 'banner-pegi' ||
            img.id === 'banner-1' ||
            img.id === 'banner-2' ||
            img.id === 'banner-3' ||
            img.classList.contains('dlc-banner-img') ||
            img.classList.contains('dlc-icon') ||
            img.closest('.dlc-item-icon') ||
            img.closest('.image-inline-preview') ||
            img.closest('.media-gallery-editor') ||
            img.id.startsWith('edit-dlc-preview') ||
            img.id.startsWith('edit-dlc-banner-preview') ||
            img.id.startsWith('new-dlc-preview') ||
            img.id.startsWith('new-dlc-banner-preview') ||
            img.closest('.game-header') ||
            img.closest('.game-banner')
        ) {
            // Log para depuración
            console.log('Skipping controlled image');
            // Retorna sin procesar la imagen
            return;
        }
        
        // Eliminar estilos inline que puedan causar problemas
        img.removeAttribute('width'); // Eliminar atributo width
        img.removeAttribute('height'); // Eliminar atributo height
        img.style.setProperty('width', 'auto', 'important'); // Establecer width automático
        img.style.setProperty('height', 'auto', 'important'); // Establecer height automático
        img.style.setProperty('max-width', '800px', 'important'); // Establecer max-width
        img.style.setProperty('display', 'inline-block', 'important'); // Establecer display inline-block
    }
    
    // Buscar imágenes en todos los editores Quill
    const selectors = [
        '#comentario-editor img', // Editor de comentario
        '#trofeos-perdibles-editor img', // Editor de trofeos perdibles
        '[id^="new-trophy-instrucciones-editor"] img', // Editor de instrucciones de nuevo trofeo
        '[id^="edit-instrucciones-editor"] img', // Editor de instrucciones de trofeo existente
        '[id^="new-dlc-desc-editor"] img', // Editor de descripción de nuevo DLC
        '[id^="edit-dlc-desc-editor"] img', // Editor de descripción de DLC existente
        '[id^="new-dlc-trophy-instrucciones-editor"] img', // Editor de instrucciones de trofeo de nuevo DLC
        '[id^="edit-dlc-trophy-instrucciones-editor"] img' // Editor de instrucciones de trofeo de DLC existente
    ];
    
    // Itera sobre cada selector
    selectors.forEach(selector => {
        // Obtiene todas las imágenes que coinciden con el selector
        const images = document.querySelectorAll(selector);
        // Log para depuración
        console.log(`Images found for ${selector}:`, images.length);
        // Procesa cada imagen
        images.forEach(processImage);
    });
    
    // Configurar MutationObserver para detectar imágenes nuevas en editores
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                // Si el nodo es un elemento
                if (node.nodeType === 1) { // Element node
                    // Buscar imágenes en el nodo agregado
                    const images = node.querySelectorAll ? node.querySelectorAll('img') : [];
                    // Procesa cada imagen encontrada
                    images.forEach(processImage);
                    
                    // Si el nodo mismo es una imagen
                    if (node.tagName === 'IMG') {
                        // Procesa la imagen
                        processImage(node);
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
    
    // Verificar periódicamente las imágenes para asegurar tamaño correcto
    setInterval(() => {
        // Itera sobre cada selector
        selectors.forEach(selector => {
            // Obtiene todas las imágenes que coinciden con el selector
            const images = document.querySelectorAll(selector);
            // Log para depuración
            console.log(`Periodic check for ${selector}:`, images.length);
            // Procesa cada imagen
            images.forEach(img => {
                // No procesar imágenes que deben respetar su sizing propio
                if (
                    img.classList.contains('trophy-card-icon') ||
                    img.classList.contains('trophy-card-type-image') ||
                    img.classList.contains('trophy-type-img') ||
                    img.classList.contains('trophy-mini') ||
                    img.classList.contains('trophy-img') ||
                    img.classList.contains('classification-img') ||
                    img.classList.contains('game-icon-img') ||
                    img.id === 'game-icon-img' ||
                    img.id === 'game-banner-img' ||
                    img.id === 'banner-pegi' ||
                    img.id === 'banner-1' ||
                    img.id === 'banner-2' ||
                    img.id === 'banner-3' ||
                    img.classList.contains('dlc-banner-img') ||
                    img.classList.contains('dlc-icon') ||
                    img.closest('.dlc-item-icon') ||
                    img.closest('.image-inline-preview') ||
                    img.id.startsWith('edit-dlc-preview') ||
                    img.id.startsWith('edit-dlc-banner-preview') ||
                    img.id.startsWith('new-dlc-preview') ||
                    img.id.startsWith('new-dlc-banner-preview') ||
                    img.closest('.game-header') ||
                    img.closest('.game-banner')
                ) {
                    // Log para depuración
                    console.log('Skipping controlled image in periodic check');
                    // Retorna sin procesar la imagen
                    return;
                }
                
                // Elimina atributos y establece estilos
                img.removeAttribute('width');
                img.removeAttribute('height');
                img.style.setProperty('width', 'auto', 'important');
                img.style.setProperty('height', 'auto', 'important');
                img.style.setProperty('max-width', '800px', 'important');
                img.style.setProperty('display', 'inline-block', 'important');
            });
        });
    }, 500); // Ejecuta cada 500ms
}

// Ejecutar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Log para depuración
    console.log('editar_juego.js loaded');
    // Configura el sizing de imágenes
    setupImageSizing();
    // Configura el recorte de banner
    setupBannerCropper();
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
                            // Alterna la clase revealed al hacer clic
                            this.classList.toggle('revealed');
                        });
                    });

                    // Si el nodo mismo es un spoiler
                    if (node.classList && node.classList.contains('ql-spoiler')) {
                        node.addEventListener('click', function() {
                            // Alterna la clase revealed al hacer clic
                            this.classList.toggle('revealed');
                        });
                    }
                }
            });
        });
    });

    // Observar cambios en todo el documento
    observer.observe(document.body, {
        // Observa cambios en la lista de hijos
        childList: true,
        // Observa cambios en todos los descendientes
        subtree: true
    });
}

// Función para configurar el recorte de banner
function setupBannerCropper() {
    // Obtiene los elementos del DOM
    const cropBtn = document.getElementById('crop-banner-btn');
    const cropModal = document.getElementById('banner-crop-modal');
    const closeCropModal = document.getElementById('close-crop-modal');
    const cancelCrop = document.getElementById('cancel-crop');
    const applyCrop = document.getElementById('apply-crop');
    const bannerCropImage = document.getElementById('banner-crop-image');
    const currentBanner = document.getElementById('current-banner');
    const bannerUrlInput = document.getElementById('banner-url');
    
    // Variable para almacenar la instancia de Cropper.js
    let cropper = null;
    
    // Abrir modal de recorte
    cropBtn.addEventListener('click', function() {
        // Obtiene la fuente del banner actual
        const bannerSrc = currentBanner.src || bannerUrlInput.value;
        // Valida que haya una imagen seleccionada
        if (!bannerSrc || bannerSrc === window.location.href) {
            alert('Por favor, selecciona una imagen de banner primero.');
            return;
        }
        
        // Establece la imagen en el modal
        bannerCropImage.src = bannerSrc;
        // Muestra el modal
        cropModal.style.display = 'flex';
        
        // Inicializar Cropper.js
        if (cropper) {
            // Destruye la instancia anterior si existe
            cropper.destroy();
        }
        
        // Crea una nueva instancia de Cropper.js
        cropper = new Cropper(bannerCropImage, {
            // Sin relación de aspecto fija
            aspectRatio: NaN,
            // Modo de vista restringido
            viewMode: 1,
            // Área de recorte automática al 80%
            autoCropArea: 0.8,
            // Responsive
            responsive: true,
            // No restaurar datos anteriores
            restore: false,
            // Mostrar guías
            guides: true,
            // Centrar la imagen
            center: true,
            // Resaltar el área de recorte
            highlight: true,
            // Permitir mover el área de recorte
            cropBoxMovable: true,
            // Permitir redimensionar el área de recorte
            cropBoxResizable: true,
            // No alternar modo de arrastre al doble clic
            toggleDragModeOnDblclick: false,
        });
        
        // Cargar coordenadas existentes si están disponibles
        const cropX = document.getElementById('banner-crop-x').value;
        const cropY = document.getElementById('banner-crop-y').value;
        const cropWidth = document.getElementById('banner-crop-width').value;
        const cropHeight = document.getElementById('banner-crop-height').value;
        
        // Si hay coordenadas guardadas, las carga en el cropper
        if (cropX && cropY && cropWidth && cropHeight) {
            cropper.setData({
                // Convierte de porcentaje a píxeles
                x: (parseFloat(cropX) / 100) * bannerCropImage.naturalWidth,
                y: (parseFloat(cropY) / 100) * bannerCropImage.naturalHeight,
                width: (parseFloat(cropWidth) / 100) * bannerCropImage.naturalWidth,
                height: (parseFloat(cropHeight) / 100) * bannerCropImage.naturalHeight,
            });
        }
    });
    
    // Cerrar modal con el botón de cerrar
    closeCropModal.addEventListener('click', function() {
        // Oculta el modal
        cropModal.style.display = 'none';
        // Destruye la instancia de cropper
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    });
    
    // Cancelar recorte
    cancelCrop.addEventListener('click', function() {
        // Oculta el modal
        cropModal.style.display = 'none';
        // Destruye la instancia de cropper
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    });
    
    // Aplicar recorte
    applyCrop.addEventListener('click', function() {
        // Valida que exista la instancia de cropper
        if (!cropper) return;
        
        // Obtiene los datos del recorte
        const cropData = cropper.getData(true);
        // Obtiene los datos de la imagen
        const imageData = cropper.getImageData();
        
        // Convertir a porcentajes
        const cropXPercent = (cropData.x / imageData.naturalWidth) * 100;
        const cropYPercent = (cropData.y / imageData.naturalHeight) * 100;
        const cropWidthPercent = (cropData.width / imageData.naturalWidth) * 100;
        const cropHeightPercent = (cropData.height / imageData.naturalHeight) * 100;
        
        // Guardar en campos ocultos
        document.getElementById('banner-crop-x').value = cropXPercent.toFixed(2);
        document.getElementById('banner-crop-y').value = cropYPercent.toFixed(2);
        document.getElementById('banner-crop-width').value = cropWidthPercent.toFixed(2);
        document.getElementById('banner-crop-height').value = cropHeightPercent.toFixed(2);
        
        // Cerrar modal
        cropModal.style.display = 'none';
        // Destruye la instancia de cropper
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        
        // Log para depuración
        console.log('Coordenadas de recorte guardadas:', {
            x: cropXPercent.toFixed(2),
            y: cropYPercent.toFixed(2),
            width: cropWidthPercent.toFixed(2),
            height: cropHeightPercent.toFixed(2)
        });
    });
    
    // Cerrar modal al hacer clic fuera
    cropModal.addEventListener('click', function(e) {
        // Si el clic es en el modal (no en su contenido)
        if (e.target === cropModal) {
            // Oculta el modal
            cropModal.style.display = 'none';
            // Destruye la instancia de cropper
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        }
    });
}
