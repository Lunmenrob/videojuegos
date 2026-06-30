// Carrusel de medios - Archivo separado para facilitar el mantenimiento porque se jode cada poco

// Función para obtener el código embed de YouTube desde una URL
function getYouTubeEmbed(url) {
  // Si no hay URL, retorna cadena vacía
  if (!url) return '';

  // Intenta extraer el ID del video de diferentes formatos de URL de YouTube
  const videoIdMatch = url.match(/[?&]v=([^&]+)/) ||
    url.match(/youtu\.be\/([^?&]+)/) ||
    url.match(/embed\/([^?&]+)/) ||
    url.match(/shorts\/([^?&]+)/);

  // Obtiene el ID del video del match o cadena vacía si no hay match
  const videoId = videoIdMatch?.[1] || '';

  // Si el ID del video tiene 11 caracteres (longitud estándar de YouTube)
  if (videoId.length === 11) {
    // Retorna el iframe embed de YouTube
    return `<iframe src="https://www.youtube.com/embed/${videoId}?rel=0" frameborder="0" allowfullscreen style="width: 100%; height: 100%; border: none;"></iframe>`;
  }
  // Si no es un ID válido, retorna cadena vacía
  return '';
}

// Función para renderizar el carrusel de medios
function renderMediaCarousel(containerId, mediaItems) {
  // Obtiene el contenedor por su ID
  const container = document.getElementById(containerId);
  // Si no existe el contenedor, retorna
  if (!container) return;

  // Obtiene los elementos del carrusel (track, dots, botones prev/next)
  const track = container.querySelector('.media-carousel-track') || container.querySelector('[data-track]');
  const dots = container.querySelector('.media-carousel-dots') || container.querySelector('[data-dots]');
  const prev = container.querySelector('.media-carousel-btn--prev') || container.querySelector('[data-prev]');
  const next = container.querySelector('.media-carousel-btn--next') || container.querySelector('[data-next]');

  // Si no existen track o dots, retorna
  if (!track || !dots) return;

  // Si no hay items de media o no es un array, muestra mensaje de vacío
  if (!Array.isArray(mediaItems) || mediaItems.length === 0) {
    track.innerHTML = '<div class="media-empty">No hay contenido multimedia disponible</div>';
    dots.innerHTML = '';
    return;
  }

  // Filtra los items que tienen URL válida
  const safeItems = mediaItems.filter(item => item && item.url);

  // Si no hay items válidos después del filtro, muestra mensaje de vacío
  if (safeItems.length === 0) {
    track.innerHTML = '<div class="media-empty">No hay contenido multimedia disponible</div>';
    dots.innerHTML = '';
    return;
  }

  // Configuración del carrusel
  const visibleSlides = 1; // Mostrar 1 slide a la vez
  let currentIndex = 0; // Índice del slide actual
  const maxIndex = Math.max(0, safeItems.length - visibleSlides); // Índice máximo

  // Genera el HTML para cada slide
  track.innerHTML = safeItems.map((item, index) => {
    // Verifica si el item es un video
    const isVideo = item.tipo === 'video';
    // Si es video, obtiene el embed de YouTube
    const embedHtml = isVideo ? getYouTubeEmbed(item.url) : '';

    // Log para depuración
    console.log(`Rendering item ${index}:`, { tipo: item.tipo, url: item.url, isVideo, hasEmbed: !!embedHtml });

    // Genera el contenido del media (video o imagen)
    const mediaContent = isVideo
      ? (embedHtml || `<video controls src="${item.url}" preload="metadata" style="width: 100%; height: 100%; object-fit: contain;" onerror="console.error('Error loading video:', this.src); this.style.display='none'; this.parentElement.innerHTML='<span style=color:#fff>Video no disponible</span>'"></video>`)
      : `<img src="${item.url}" alt="Media" style="width: 100%; height: 100%; object-fit: contain;" onerror="console.error('Error loading image:', this.src); this.style.display='none'; this.parentElement.innerHTML='<span style=color:#fff>Imagen no disponible</span>'" onload="console.log('Image loaded successfully:', this.src)">`;

    // Retorna el HTML del slide
    return `
      <div class="media-slide" data-index="${index}" style="min-width: 100%;">
        ${mediaContent}
      </div>
    `;
  }).join('');

  // Genera los botones de navegación (dots)
  dots.innerHTML = safeItems.map((_, index) => `
    <button type="button" class="${index === 0 ? 'active' : ''}" data-index="${index}" aria-label="Ir al slide ${index + 1}"></button>
  `).join('');

  // Función para actualizar la posición del carrusel
  const updateCarousel = () => {
    // Calcula el offset de desplazamiento (100% por slide)
    const offset = Math.min(currentIndex, maxIndex) * 100; // 100% para 1 slide a la vez
    // Aplica la transformación al track
    track.style.transform = `translateX(-${offset}%)`;

    // Actualiza la clase active en los dots
    dots.querySelectorAll('button').forEach((dot, index) => {
      dot.classList.toggle('active', index === currentIndex);
    });
    
    // Log para depuración
    console.log('Carousel update:', { currentIndex, maxIndex, offset, totalSlides: safeItems.length });
  };

  // Event listener para el botón anterior
  prev?.addEventListener('click', () => {
    // Decrementa el índice actual (mínimo 0)
    currentIndex = Math.max(0, currentIndex - 1);
    // Actualiza el carrusel
    updateCarousel();
  });

  // Event listener para el botón siguiente
  next?.addEventListener('click', () => {
    // Incrementa el índice actual (máximo maxIndex)
    currentIndex = Math.min(maxIndex, currentIndex + 1);
    // Actualiza el carrusel
    updateCarousel();
  });

  // Event listeners para los botones de navegación (dots)
  dots.querySelectorAll('button').forEach((dot) => {
    dot.addEventListener('click', () => {
      // Establece el índice actual al índice del dot clickeado
      currentIndex = Math.min(maxIndex, Number(dot.dataset.index));
      // Actualiza el carrusel
      updateCarousel();
    });
  });

  // Inicializa el carrusel en la primera posición
  updateCarousel();
}

// Función asíncrona para cargar media de un juego
async function loadGameMedia(gameId, containerId) {
  try {
    // Log para depuración
    console.log(`Cargando media del juego ${gameId} en contenedor ${containerId}`);
    // Realiza la petición fetch a la API
    const response = await fetch(`api/game_media.php?game_id=${gameId}`);
    // Parsea la respuesta JSON
    const media = await response.json();
    // Log para depuración
    console.log(`Media del juego ${gameId}:`, media);
    // Renderiza el carrusel con la media obtenida
    renderMediaCarousel(containerId, media);
  } catch (error) {
    // Log de error si falla la carga
    console.error('Error cargando media del juego:', error);
  }
}

// Función asíncrona para cargar media de un DLC
async function loadDlcMedia(dlcId, containerId) {
  try {
    // Log para depuración
    console.log(`Cargando media del DLC ${dlcId} en contenedor ${containerId}`);
    // Realiza la petición fetch a la API
    const response = await fetch(`api/game_media.php?dlc_id=${dlcId}`);
    // Parsea la respuesta JSON
    const media = await response.json();
    // Log para depuración
    console.log(`Media del DLC ${dlcId}:`, media);
    // Renderiza el carrusel con la media obtenida
    renderMediaCarousel(containerId, media);
  } catch (error) {
    // Log de error si falla la carga
    console.error('Error cargando media del DLC:', error);
  }
}
