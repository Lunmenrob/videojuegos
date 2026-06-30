<?php
// Carrusel de medios - Archivo PHP separado para facilitar el mantenimiento
?>
<style>
/* ==================== ESTILOS DEL CARRUSEL ==================== */

/* Contenedor principal del carrusel - Franja negra de fondo */
.media-carousel {
    background: #000000;
    width: 100%;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1rem 0;
    gap: 0.5rem;
}

/* Contenedor visible del carrusel */
.media-carousel-viewport {
    width: 100%;
    max-width: 600px;
    height: 340px;
    position: relative;
    background: #000000;
    border-radius: 8px;
    overflow: hidden;
    margin-top: 0;
}

/* Track del carrusel */
.media-carousel-track {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    transition: transform 0.35s ease;
    flex-wrap: nowrap;
}

/* Slides */
.media-slide {
    flex: 0 0 100%;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    min-width: 100%;
    flex-shrink: 0;
}

/* Medios dentro del slide - Tamaño fijo enmarcado */
.media-slide img,
.media-slide video,
.media-slide iframe {
    max-width: 600px;
    max-height: 340px;
    width: auto;
    height: auto;
    object-fit: contain;
    border-radius: 4px;
}

/* Botones de navegación */
.media-carousel-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 2;
    width: 42px;
    height: 42px;
    border: 1px solid rgba(255, 255, 255, 0.12);
    background: rgba(0, 0, 0, 0.55);
    color: #fff;
    border-radius: 999px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.media-carousel-btn:hover {
    background: rgba(255, 255, 255, 0.1);
}

.media-carousel-btn--prev {
    left: 1rem;
}

.media-carousel-btn--next {
    right: 1rem;
}

/* Dots de navegación */
.media-carousel-dots {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 0;
    box-sizing: border-box;
}

.media-carousel-dots button {
    width: 48px;
    height: 5px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.3);
    border: none;
    cursor: pointer;
    transition: background 0.2s ease;
}

.media-carousel-dots button.active {
    background: #ffffff;
}

/* Mensaje de vacío */
.media-empty {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #888;
    font-size: 1rem;
}
</style>

<script>
// Carrusel de medios - JavaScript
// Función para obtener la ruta base de la API según el contexto
function getApiBasePath() {
  // Si está en la carpeta publico, usa '../api/', sino 'api/'
  return window.location.pathname.includes('/publico/') ? '../api/' : 'api/';
}

// Función para construir la URL completa de la API
function getApiUrl(path) {
  return new URL(`${getApiBasePath()}${path}`, window.location.href);
}

// Función para extraer el ID de video de YouTube y generar el embed
function getYouTubeEmbed(url) {
  // Si no hay URL, retorna vacío
  if (!url) return '';

  // Intenta extraer el ID del video de diferentes formatos de URL
  const videoIdMatch = url.match(/[?&]v=([^&]+)/) ||
    url.match(/youtu\.be\/([^?&]+)/) ||
    url.match(/embed\/([^?&]+)/) ||
    url.match(/shorts\/([^?&]+)/);

  // Obtiene el ID del video
  const videoId = videoIdMatch?.[1] || '';

  // Si el ID tiene 11 caracteres (longitud estándar de YouTube), genera el embed
  if (videoId.length === 11) {
    console.log('YouTube video ID extracted:', videoId);
    return `<iframe src="https://www.youtube.com/embed/${videoId}?rel=0&autoplay=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="width: 600px; height: 340px; border: none;"></iframe>`;
  }
  console.log('Invalid YouTube video ID:', videoId);
  return '';
}

// Función principal para renderizar el carrusel de medios
function renderMediaCarousel(containerId, mediaItems) {
  // Obtiene el contenedor por ID
  const container = document.getElementById(containerId);
  if (!container) return;

  // Crear elementos del carrusel si no existen
  let viewport = container.querySelector('.media-carousel-viewport');
  let track = container.querySelector('.media-carousel-track') || container.querySelector('[data-track]');
  let dots = container.querySelector('.media-carousel-dots') || container.querySelector('[data-dots]');
  let prev = container.querySelector('.media-carousel-btn--prev') || container.querySelector('[data-prev]');
  let next = container.querySelector('.media-carousel-btn--next') || container.querySelector('[data-next]');

  // Crear viewport si no existe
  if (!viewport) {
    viewport = document.createElement('div');
    viewport.className = 'media-carousel-viewport';
    container.appendChild(viewport);
  }

  // Crear track si no existe
  if (!track) {
    track = document.createElement('div');
    track.className = 'media-carousel-track';
    viewport.appendChild(track);
  } else if (!viewport.contains(track)) {
    // Si el track existe pero no está en el viewport, moverlo
    viewport.appendChild(track);
  }

  // Crear dots si no existen
  if (!dots) {
    dots = document.createElement('div');
    dots.className = 'media-carousel-dots';
    container.appendChild(dots);
  }
  // Crear botón anterior si no existe
  if (!prev) {
    prev = document.createElement('button');
    prev.className = 'media-carousel-btn media-carousel-btn--prev';
    prev.type = 'button';
    prev.setAttribute('aria-label', 'Anterior');
    prev.innerHTML = '<i class="fas fa-chevron-left"></i>';
    container.appendChild(prev);
  }
  // Crear botón siguiente si no existe
  if (!next) {
    next = document.createElement('button');
    next.className = 'media-carousel-btn media-carousel-btn--next';
    next.type = 'button';
    next.setAttribute('aria-label', 'Siguiente');
    next.innerHTML = '<i class="fas fa-chevron-right"></i>';
    container.appendChild(next);
  }

  // Si no hay items o no es un array, muestra mensaje vacío
  if (!Array.isArray(mediaItems) || mediaItems.length === 0) {
    track.innerHTML = '<div class="media-empty">No hay contenido multimedia disponible</div>';
    dots.innerHTML = '';
    return;
  }

  // Filtra items válidos (que tengan URL)
  const safeItems = mediaItems.filter(item => item && item.url);

  // Si no hay items válidos, muestra mensaje vacío
  if (safeItems.length === 0) {
    track.innerHTML = '<div class="media-empty">No hay contenido multimedia disponible</div>';
    dots.innerHTML = '';
    return;
  }

  const visibleSlides = 1; // Mostrar 1 slide a la vez
  let currentIndex = 0;
  const maxIndex = Math.max(0, safeItems.length - visibleSlides);

  // Genera el HTML de los slides
  track.innerHTML = safeItems.map((item, index) => {
    const isVideo = item.tipo === 'video';
    const embedHtml = isVideo ? getYouTubeEmbed(item.url) : '';

    console.log(`Rendering item ${index}:`, { tipo: item.tipo, url: item.url, isVideo, embedHtml: embedHtml ? 'generated' : 'empty' });

    // Genera el contenido del media (video o imagen)
    const mediaContent = isVideo
      ? (embedHtml || `<video controls src="${item.url}" preload="metadata" style="width: 600px; height: 340px; object-fit: contain;"></video>`)
      : `<img src="${item.url}" alt="Media" style="width: 600px; height: 340px; object-fit: contain;" onerror="console.error('Error loading image:', this.src); this.style.display='none'; this.parentElement.innerHTML='<span style=color:#fff>Imagen no disponible</span>'" onload="console.log('Image loaded successfully:', this.src)">`;

    return `
      <div class="media-slide" data-index="${index}" style="min-width: 100%;">
        ${mediaContent}
      </div>
    `;
  }).join('');

  // Genera los dots de navegación
  dots.innerHTML = safeItems.map((_, index) => `
    <button type="button" class="${index === 0 ? 'active' : ''}" data-index="${index}" aria-label="Ir al slide ${index + 1}"></button>
  `).join('');

  // Función para actualizar la posición del carrusel
  const updateCarousel = () => {
    const offset = Math.min(currentIndex, maxIndex) * 100; // 100% para 1 slide a la vez
    track.style.transform = `translateX(-${offset}%)`;

    // Actualiza el estado activo de los dots
    dots.querySelectorAll('button').forEach((dot, index) => {
      dot.classList.toggle('active', index === currentIndex);
    });

    console.log('Carousel update:', { currentIndex, maxIndex, offset, totalSlides: safeItems.length, trackTransform: track.style.transform });
  };

  // Event listener para botón anterior
  prev?.addEventListener('click', () => {
    currentIndex = Math.max(0, currentIndex - 1);
    updateCarousel();
  });

  // Event listener para botón siguiente
  next?.addEventListener('click', () => {
    currentIndex = Math.min(maxIndex, currentIndex + 1);
    updateCarousel();
  });

  // Event listeners para los dots
  dots.querySelectorAll('button').forEach((dot) => {
    dot.addEventListener('click', () => {
      currentIndex = Math.min(maxIndex, Number(dot.dataset.index));
      updateCarousel();
    });
  });

  // Inicializa el carrusel
  updateCarousel();
}

// Función asíncrona para cargar media de un juego
async function loadGameMedia(gameId, containerId) {
  try {
    const apiUrl = getApiUrl('game_media.php');
    apiUrl.searchParams.set('game_id', gameId);
    const response = await fetch(apiUrl);
    const media = await response.json();
    renderMediaCarousel(containerId, media);
  } catch (error) {
    console.error('Error cargando media del juego:', error);
  }
}

// Función asíncrona para cargar media de un DLC
async function loadDlcMedia(dlcId, containerId) {
  try {
    console.log(`Cargando media del DLC ${dlcId} en contenedor ${containerId}`);
    const apiUrl = getApiUrl('game_media.php');
    apiUrl.searchParams.set('dlc_id', dlcId);
    const response = await fetch(apiUrl);
    const media = await response.json();
    console.log(`Media del DLC ${dlcId}:`, media);
    renderMediaCarousel(containerId, media);
  } catch (error) {
    console.error('Error cargando media del DLC:', error);
  }
}
</script>
