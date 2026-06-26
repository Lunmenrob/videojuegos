// Carrusel de medios - Archivo separado para facilitar el mantenimiento porque se jode cada poco

function getYouTubeEmbed(url) {
  if (!url) return '';

  const videoIdMatch = url.match(/[?&]v=([^&]+)/) ||
    url.match(/youtu\.be\/([^?&]+)/) ||
    url.match(/embed\/([^?&]+)/) ||
    url.match(/shorts\/([^?&]+)/);

  const videoId = videoIdMatch?.[1] || '';

  if (videoId.length === 11) {
    return `<iframe src="https://www.youtube.com/embed/${videoId}?rel=0" frameborder="0" allowfullscreen style="width: 100%; height: 100%; border: none;"></iframe>`;
  }
  return '';
}

function renderMediaCarousel(containerId, mediaItems) {
  const container = document.getElementById(containerId);
  if (!container) return;

  const track = container.querySelector('.media-carousel-track') || container.querySelector('[data-track]');
  const dots = container.querySelector('.media-carousel-dots') || container.querySelector('[data-dots]');
  const prev = container.querySelector('.media-carousel-btn--prev') || container.querySelector('[data-prev]');
  const next = container.querySelector('.media-carousel-btn--next') || container.querySelector('[data-next]');

  if (!track || !dots) return;

  if (!Array.isArray(mediaItems) || mediaItems.length === 0) {
    track.innerHTML = '<div class="media-empty">No hay contenido multimedia disponible</div>';
    dots.innerHTML = '';
    return;
  }

  const safeItems = mediaItems.filter(item => item && item.url);

  if (safeItems.length === 0) {
    track.innerHTML = '<div class="media-empty">No hay contenido multimedia disponible</div>';
    dots.innerHTML = '';
    return;
  }

  const visibleSlides = 1; // Mostrar 1 slide a la vez
  let currentIndex = 0;
  const maxIndex = Math.max(0, safeItems.length - visibleSlides);

  track.innerHTML = safeItems.map((item, index) => {
    const isVideo = item.tipo === 'video';
    const embedHtml = isVideo ? getYouTubeEmbed(item.url) : '';

    console.log(`Rendering item ${index}:`, { tipo: item.tipo, url: item.url, isVideo, hasEmbed: !!embedHtml });

    const mediaContent = isVideo
      ? (embedHtml || `<video controls src="${item.url}" preload="metadata" style="width: 100%; height: 100%; object-fit: contain;" onerror="console.error('Error loading video:', this.src); this.style.display='none'; this.parentElement.innerHTML='<span style=color:#fff>Video no disponible</span>'"></video>`)
      : `<img src="${item.url}" alt="Media" style="width: 100%; height: 100%; object-fit: contain;" onerror="console.error('Error loading image:', this.src); this.style.display='none'; this.parentElement.innerHTML='<span style=color:#fff>Imagen no disponible</span>'" onload="console.log('Image loaded successfully:', this.src)">`;

    return `
      <div class="media-slide" data-index="${index}" style="min-width: 100%;">
        ${mediaContent}
      </div>
    `;
  }).join('');

  dots.innerHTML = safeItems.map((_, index) => `
    <button type="button" class="${index === 0 ? 'active' : ''}" data-index="${index}" aria-label="Ir al slide ${index + 1}"></button>
  `).join('');

  const updateCarousel = () => {
    const offset = Math.min(currentIndex, maxIndex) * 100; // 100% para 1 slide a la vez
    track.style.transform = `translateX(-${offset}%)`;

    dots.querySelectorAll('button').forEach((dot, index) => {
      dot.classList.toggle('active', index === currentIndex);
    });
    
    console.log('Carousel update:', { currentIndex, maxIndex, offset, totalSlides: safeItems.length });
  };

  prev?.addEventListener('click', () => {
    currentIndex = Math.max(0, currentIndex - 1);
    updateCarousel();
  });

  next?.addEventListener('click', () => {
    currentIndex = Math.min(maxIndex, currentIndex + 1);
    updateCarousel();
  });

  dots.querySelectorAll('button').forEach((dot) => {
    dot.addEventListener('click', () => {
      currentIndex = Math.min(maxIndex, Number(dot.dataset.index));
      updateCarousel();
    });
  });

  updateCarousel();
}

async function loadGameMedia(gameId, containerId) {
  try {
    console.log(`Cargando media del juego ${gameId} en contenedor ${containerId}`);
    const response = await fetch(`api/game_media.php?game_id=${gameId}`);
    const media = await response.json();
    console.log(`Media del juego ${gameId}:`, media);
    renderMediaCarousel(containerId, media);
  } catch (error) {
    console.error('Error cargando media del juego:', error);
  }
}

async function loadDlcMedia(dlcId, containerId) {
  try {
    console.log(`Cargando media del DLC ${dlcId} en contenedor ${containerId}`);
    const response = await fetch(`api/game_media.php?dlc_id=${dlcId}`);
    const media = await response.json();
    console.log(`Media del DLC ${dlcId}:`, media);
    renderMediaCarousel(containerId, media);
  } catch (error) {
    console.error('Error cargando media del DLC:', error);
  }
}
