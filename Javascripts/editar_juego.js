// Función para procesar imágenes en editores Quill
function setupImageSizing() {
    console.log('setupImageSizing called');
    
    // Función para procesar una imagen individual
    function processImage(img) {
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
            console.log('Skipping controlled image');
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
        '[id^="new-dlc-trophy-instrucciones-editor"] img',
        '[id^="edit-dlc-trophy-instrucciones-editor"] img'
    ];
    
    selectors.forEach(selector => {
        const images = document.querySelectorAll(selector);
        console.log(`Images found for ${selector}:`, images.length);
        images.forEach(processImage);
    });
    
    // Configurar MutationObserver para detectar imágenes nuevas en editores
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.nodeType === 1) { // Element node
                    // Buscar imágenes en el nodo agregado
                    const images = node.querySelectorAll ? node.querySelectorAll('img') : [];
                    images.forEach(processImage);
                    
                    // Si el nodo mismo es una imagen
                    if (node.tagName === 'IMG') {
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
        selectors.forEach(selector => {
            const images = document.querySelectorAll(selector);
            console.log(`Periodic check for ${selector}:`, images.length);
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
                    console.log('Skipping controlled image in periodic check');
                    return;
                }
                
                img.removeAttribute('width');
                img.removeAttribute('height');
                img.style.setProperty('width', 'auto', 'important');
                img.style.setProperty('height', 'auto', 'important');
                img.style.setProperty('max-width', '800px', 'important');
                img.style.setProperty('display', 'inline-block', 'important');
            });
        });
    }, 500);
}

// Ejecutar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('editar_juego.js loaded');
    setupImageSizing();
    setupBannerCropper();
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

// Función para configurar el recorte de banner
function setupBannerCropper() {
    const cropBtn = document.getElementById('crop-banner-btn');
    const cropModal = document.getElementById('banner-crop-modal');
    const closeCropModal = document.getElementById('close-crop-modal');
    const cancelCrop = document.getElementById('cancel-crop');
    const applyCrop = document.getElementById('apply-crop');
    const bannerCropImage = document.getElementById('banner-crop-image');
    const currentBanner = document.getElementById('current-banner');
    const bannerUrlInput = document.getElementById('banner-url');
    
    let cropper = null;
    
    // Abrir modal de recorte
    cropBtn.addEventListener('click', function() {
        const bannerSrc = currentBanner.src || bannerUrlInput.value;
        if (!bannerSrc || bannerSrc === window.location.href) {
            alert('Por favor, selecciona una imagen de banner primero.');
            return;
        }
        
        bannerCropImage.src = bannerSrc;
        cropModal.style.display = 'flex';
        
        // Inicializar Cropper.js
        if (cropper) {
            cropper.destroy();
        }
        
        cropper = new Cropper(bannerCropImage, {
            aspectRatio: NaN,
            viewMode: 1,
            autoCropArea: 0.8,
            responsive: true,
            restore: false,
            guides: true,
            center: true,
            highlight: true,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
        });
        
        // Cargar coordenadas existentes si están disponibles
        const cropX = document.getElementById('banner-crop-x').value;
        const cropY = document.getElementById('banner-crop-y').value;
        const cropWidth = document.getElementById('banner-crop-width').value;
        const cropHeight = document.getElementById('banner-crop-height').value;
        
        if (cropX && cropY && cropWidth && cropHeight) {
            cropper.setData({
                x: (parseFloat(cropX) / 100) * bannerCropImage.naturalWidth,
                y: (parseFloat(cropY) / 100) * bannerCropImage.naturalHeight,
                width: (parseFloat(cropWidth) / 100) * bannerCropImage.naturalWidth,
                height: (parseFloat(cropHeight) / 100) * bannerCropImage.naturalHeight,
            });
        }
    });
    
    // Cerrar modal
    closeCropModal.addEventListener('click', function() {
        cropModal.style.display = 'none';
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    });
    
    cancelCrop.addEventListener('click', function() {
        cropModal.style.display = 'none';
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    });
    
    // Aplicar recorte
    applyCrop.addEventListener('click', function() {
        if (!cropper) return;
        
        const cropData = cropper.getData(true);
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
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        
        console.log('Coordenadas de recorte guardadas:', {
            x: cropXPercent.toFixed(2),
            y: cropYPercent.toFixed(2),
            width: cropWidthPercent.toFixed(2),
            height: cropHeightPercent.toFixed(2)
        });
    });
    
    // Cerrar modal al hacer clic fuera
    cropModal.addEventListener('click', function(e) {
        if (e.target === cropModal) {
            cropModal.style.display = 'none';
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        }
    });
}
