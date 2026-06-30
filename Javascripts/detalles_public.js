// Variables globales para almacenar todos los trofeos
let allTrophies = [];
let dlcTrophies = [];
<<<<<<< HEAD
let gameData = null;
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a

const isPublicPage = true;
const apiBasePath = '../api/';
const assetBasePath = '../';

function getApiUrl(path) {
  return new URL(`${apiBasePath}${path}`, window.location.href);
}

function getAssetUrl(path) {
  return `${assetBasePath}${path}`;
}

console.log('detalles_public.js loaded');

// Función para decodificar entidades HTML
function decodeHTMLEntities(text) {
    const textArea = document.createElement('textarea');
    textArea.innerHTML = text;
    return textArea.value;
}

// Función para abrir lightbox
function openLightbox(imgSrc) {
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    lightboxImg.src = imgSrc;
    lightbox.classList.add('active');
}

// Función para cerrar lightbox
function closeLightbox() {
    const lightbox = document.getElementById('lightbox');
    lightbox.classList.remove('active');
}

// Agregar event listeners para imágenes después de cargar contenido
function setupImageLightbox() {
    console.log('setupImageLightbox called');
    
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
            img.closest('.game-header') ||
            img.closest('.game-banner')
        ) {
            console.log('Skipping controlled image');
            return;
        }
        
        // Eliminar estilos inline que puedan causar problemas
        img.removeAttribute('width');
        img.removeAttribute('height');
        img.style.setProperty('width', 'auto', 'important');
        img.style.setProperty('height', 'auto', 'important');
        img.style.setProperty('max-width', '800px', 'important');
        img.style.setProperty('display', 'inline-block', 'important');
        
        // Agregar evento de click para lightbox
        if (!img.hasAttribute('data-lightbox-setup')) {
            img.setAttribute('data-lightbox-setup', 'true');
            img.addEventListener('click', () => openLightbox(img.src));
        }
    }
    
    // Buscar imágenes solo en contenedores de contenido rico
    const selectors = [
        '#comentario img',
        '#trofeos-perdibles img',
        '.trophy-card-instructions img',
        '.dlc-container .info-comment img'
    ];
    
    selectors.forEach(selector => {
        const images = document.querySelectorAll(selector);
        console.log(`Images found for ${selector}:`, images.length);
        images.forEach(processImage);
    });
    
    // Configurar MutationObserver para detectar imágenes nuevas
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
    
    // Verificar periódicamente solo las imágenes que realmente deben abrirse con lightbox
    setInterval(() => {
        selectors.forEach(selector => {
            const images = document.querySelectorAll(selector);
            console.log(`Periodic check for ${selector}:`, images.length);
            images.forEach(img => {
                img.removeAttribute('width');
                img.removeAttribute('height');
                img.style.setProperty('width', 'auto', 'important');
                img.style.setProperty('height', 'auto', 'important');
                img.style.setProperty('max-width', '800px', 'important');
                img.style.setProperty('display', 'inline-block', 'important');
            });
        });
    }, 1000);
}

// Configurar spoilers para colapsar/expandir al hacer clic
function setupSpoilers() {
    const spoilers = document.querySelectorAll('.ql-spoiler');
    spoilers.forEach(spoiler => {
        spoiler.addEventListener('click', function() {
            this.classList.toggle('revealed');
        });
    });
}

function initializeDetailsPage() {
  const gameId = new URLSearchParams(window.location.search).get('id');
  console.log('ID desde URL:', gameId);
  console.log('URL completa:', window.location.href);

  if (!gameId) {
    return;
  }

  console.log('ID existe, cargando datos...');

  const apiUrl = getApiUrl('game.php');
  apiUrl.searchParams.set('id', gameId);

  fetch(apiUrl)
    .then(res => {
      console.log('Respuesta de game.php:', res);
      return res.json();
    })
    .then(data => {
      console.log('Datos del juego:', data);
      document.getElementById('titulo').textContent = data.titulo;
      const platformBadge = document.getElementById('plataforma');
      platformBadge.textContent = data.plataforma;
      platformBadge.classList.add((data.plataforma || '').toLowerCase());

      if (data.imagen_url) {
        document.getElementById('game-icon-img').src = data.imagen_url;
      }

      if (data.banner_url) {
        const bannerElement = document.getElementById('game-banner-img');
        bannerElement.src = data.banner_url;
        if (data.banner_crop_x !== null && data.banner_crop_y !== null) {
          const cropX = data.banner_crop_x || 50;
          const cropY = data.banner_crop_y || 37;
          bannerElement.style.objectPosition = `center ${cropY}%`;
          console.log('Coordenadas de recorte aplicadas:', { cropX, cropY });
        }
      }

      document.getElementById('banner-titulo').textContent = data.titulo;
      document.getElementById('banner-plataforma').textContent = data.plataforma;

      if (data.fecha_lanzamiento) {
        const fecha = new Date(data.fecha_lanzamiento);
        document.getElementById('banner-lanzamiento').textContent = fecha.toLocaleDateString('es-ES');
      }

      document.getElementById('banner-editor').textContent = data.desarrollador || '--';
      document.getElementById('banner-generos').textContent = data.genero || '--';

      if (data.pegi_url) {
        document.getElementById('banner-pegi').src = data.pegi_url;
      }
      if (data.clasificacion_1_url && data.show_clas1 !== false) {
        document.getElementById('banner-1').src = data.clasificacion_1_url;
        document.getElementById('banner-1-container').style.display = 'block';
      } else {
        document.getElementById('banner-1-container').style.display = 'none';
      }
      if (data.clasificacion_2_url && data.show_clas2 !== false) {
        document.getElementById('banner-2').src = data.clasificacion_2_url;
        document.getElementById('banner-2-container').style.display = 'block';
      } else {
        document.getElementById('banner-2-container').style.display = 'none';
      }
      if (data.clasificacion_3_url && data.show_clas3 !== false) {
        document.getElementById('banner-3').src = data.clasificacion_3_url;
        document.getElementById('banner-3-container').style.display = 'block';
      } else {
        document.getElementById('banner-3-container').style.display = 'none';
      }

<<<<<<< HEAD
      // Mostrar mapas interactivos
      if (data.mapas_interactivos && Array.isArray(data.mapas_interactivos) && data.mapas_interactivos.length > 0) {
        const mapasContainer = document.getElementById('mapas-interactivos-container');
        if (mapasContainer) {
          mapasContainer.innerHTML = '';
          mapasContainer.style.display = 'flex';
          
          data.mapas_interactivos.forEach(mapa => {
            const btn = document.createElement('a');
            btn.className = 'mapa-interactivo-btn';
            btn.href = mapa.url;
            btn.target = '_blank';
            btn.rel = 'noopener noreferrer';
            btn.textContent = mapa.nombre;
            mapasContainer.appendChild(btn);
          });
=======
      const mapButton = document.getElementById('mapa-interactivo-link');
      if (mapButton) {
        const mapUrl = (data.mapa_interactivo_url || '').trim();
        if (mapUrl && /^https?:\/\//i.test(mapUrl)) {
          mapButton.href = mapUrl;
          mapButton.style.display = 'inline-flex';
          mapButton.style.visibility = 'visible';
          mapButton.hidden = false;
        } else {
          mapButton.style.display = 'none';
          mapButton.style.visibility = 'hidden';
          mapButton.hidden = true;
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
        }
      }

      const ganados = (data.platino_conseguido ? 1 : 0) + (data.oro_conseguidos || 0) + (data.plata_conseguidos || 0) + (data.bronce_conseguidos || 0);
      const disponibles = data.total_trofeos || 0;
      const porcentaje = data.porcentaje_completado || 0;

      document.getElementById('trofeos-ganados').textContent = ganados;
      document.getElementById('trofeos-disponibles').textContent = disponibles;
      document.getElementById('porcentaje').textContent = porcentaje + '%';

      if (data.dificultad_platino) {
        document.getElementById('dificultad').textContent = data.dificultad_platino;
      }
      if (data.duracion_estimada) {
        document.getElementById('duracion').textContent = data.duracion_estimada;
      }
      if (data.trofeos_offline_platino !== null && data.trofeos_offline_platino !== undefined) {
        document.getElementById('offline-platino').textContent = data.trofeos_offline_platino;
        document.getElementById('offline-oro').textContent = data.trofeos_offline_oro;
        document.getElementById('offline-plata').textContent = data.trofeos_offline_plata;
        document.getElementById('offline-bronce').textContent = data.trofeos_offline_bronce;
      }
      if (data.trofeos_online_platino !== null && data.trofeos_online_platino !== undefined) {
        document.getElementById('online-platino').textContent = data.trofeos_online_platino;
        document.getElementById('online-oro').textContent = data.trofeos_online_oro;
        document.getElementById('online-plata').textContent = data.trofeos_online_plata;
        document.getElementById('online-bronce').textContent = data.trofeos_online_bronce;
      }
      if (data.pase_online !== null) {
        document.getElementById('pase-online').textContent = data.pase_online ? 'Sí' : 'No';
      }
      if (data.necesario_platino) {
        document.getElementById('necesario-platino').textContent = data.necesario_platino;
      }
      if (data.trofeos_ocultos) {
<<<<<<< HEAD
        document.getElementById('trofeos-ocultos').innerHTML = data.trofeos_ocultos;
=======
        document.getElementById('trofeos-ocultos').textContent = data.trofeos_ocultos;
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
      }
      if (data.min_partidas) {
        document.getElementById('min-partidas').textContent = data.min_partidas;
      }
      if (data.trofeos_perdibles) {
        document.getElementById('trofeos-perdibles').innerHTML = decodeHTMLEntities(data.trofeos_perdibles);
      }
      if (data.trucos_afectan !== null) {
        document.getElementById('trucos-afectan').textContent = data.trucos_afectan ? 'Sí' : 'No';
      }
      if (data.dificultad_afecta !== null) {
        document.getElementById('dificultad-afecta').textContent = data.dificultad_afecta ? 'Sí' : 'No';
      }

      if (data.trofeos_offline_platino !== null && data.trofeos_offline_platino !== undefined) {
        const totalPlatino = (data.trofeos_offline_platino || 0) + (data.trofeos_online_platino || 0);
        const totalOro = (data.trofeos_offline_oro || 0) + (data.trofeos_online_oro || 0);
        const totalPlata = (data.trofeos_offline_plata || 0) + (data.trofeos_online_plata || 0);
        const totalBronce = (data.trofeos_offline_bronce || 0) + (data.trofeos_online_bronce || 0);

        document.getElementById('total-platino').textContent = totalPlatino;
        document.getElementById('total-oro').textContent = totalOro;
        document.getElementById('total-plata').textContent = totalPlata;
        document.getElementById('total-bronce').textContent = totalBronce;
      }
      if (data.comentario) {
        document.getElementById('comentario').innerHTML = decodeHTMLEntities(data.comentario);
      }

      setTimeout(setupImageLightbox, 100);
      setTimeout(setupSpoilers, 100);

      document.getElementById('platino-count').textContent = data.platino_conseguido ? 1 : 0;
      document.getElementById('oro-count').textContent = data.oro_conseguidos || 0;
      document.getElementById('plata-count').textContent = data.plata_conseguidos || 0;
      document.getElementById('bronce-count').textContent = data.bronce_conseguidos || 0;

<<<<<<< HEAD
      gameData = data;
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
      loadGameMedia(gameId, 'media-carousel-container');
      loadTrophies(gameId);
      loadDLCs(gameId);
    })
    .catch(err => {
      console.error('Error al cargar juego:', err);
      document.getElementById('titulo').textContent = 'Error';
      document.getElementById('plataforma').textContent = 'Error al cargar';
    });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initializeDetailsPage);
} else {
  initializeDetailsPage();
}

async function loadTrophies(gameId) {
  try {
    const apiUrl = getApiUrl('trophies.php');
    apiUrl.searchParams.set('game_id', gameId);
    const response = await fetch(apiUrl);
    const trophies = await response.json();
    allTrophies = trophies; // Almacenar trofeos del juego base
    renderTrophies(trophies);
    
    // Actualizar contadores iniciales
    updateTrophyCounters(trophies);
    
    // Añadir event listener al desplegable de filtro
    document.getElementById('trophy-filter').addEventListener('change', (e) => {
      const filter = e.target.value;
      filterTrophies(trophies, filter);
    });
  } catch (error) {
    console.error('Error al cargar trofeos:', error);
  }
}

function updateTrophyCounters(trophies) {
  console.log('Actualizando contadores de trofeos...');
  let ganados = 0;
  let totalPlatino = 0;
  let totalOro = 0;
  let totalPlata = 0;
  let totalBronce = 0;
  let ganadosPlatino = 0;
  let ganadosOro = 0;
  let ganadosPlata = 0;
  let ganadosBronce = 0;
  
  // Combinar trofeos del juego base y DLCs
  const allTrophiesCombined = [...allTrophies, ...dlcTrophies];
  
  allTrophiesCombined.forEach(trophy => {
    const isConseguido = trophy.conseguido;
    
    console.log(`Trofeo ${trophy.id} (${trophy.tipo}): conseguido=${isConseguido}`);
    
    if (isConseguido) {
      ganados++;
    }
    
    // Contar totales por tipo
    const tipo = trophy.tipo.toLowerCase();
    if (tipo === 'platino') {
      totalPlatino++;
      if (isConseguido) ganadosPlatino++;
    }
    else if (tipo === 'oro') {
      totalOro++;
      if (isConseguido) ganadosOro++;
    }
    else if (tipo === 'plata') {
      totalPlata++;
      if (isConseguido) ganadosPlata++;
    }
    else if (tipo === 'bronce') {
      totalBronce++;
      if (isConseguido) ganadosBronce++;
    }
  });
  
  console.log('Contadores calculados:', { ganados, totalPlatino, totalOro, totalPlata, totalBronce });
  
  // Actualizar contador de ganados
  document.getElementById('trofeos-ganados').textContent = ganados;
  
  // Actualizar contadores de tipos (mostrar totales, no solo ganados)
  document.getElementById('platino-count').textContent = totalPlatino;
  document.getElementById('oro-count').textContent = totalOro;
  document.getElementById('plata-count').textContent = totalPlata;
  document.getElementById('bronce-count').textContent = totalBronce;
  
  // Configurar lightbox para imágenes de trofeos
  setTimeout(setupImageLightbox, 100);
  
  // Configurar spoilers
  setTimeout(setupSpoilers, 100);
  
  // Actualizar porcentaje
  const total = allTrophiesCombined.length;
  const porcentaje = total > 0 ? Math.round((ganados / total) * 100) : 0;
  document.getElementById('porcentaje').textContent = porcentaje + '%';
  
  // Actualizar total disponible
  document.getElementById('trofeos-disponibles').textContent = total;
}

function renderTrophies(trophies) {
  const container = document.getElementById('trophies-list');
  
  console.log('renderTrophies llamado con', trophies.length, 'trofeos');
  
  const trophiesHTML = trophies.map((trophy) => {
    const trophyImage = getTrophyImage(trophy.tipo);
    const videoEmbed = trophy.video_url ? getYouTubeEmbed(trophy.video_url) : '';
    const perdibleBadge = trophy.perdible ? '<span class="trophy-perdible-badge">PERDIBLE</span>' : '';
<<<<<<< HEAD
    const onlineBadge = trophy.online ? '<i class="fas fa-globe trophy-online-icon" title="Online"></i>' : '';
=======
    const onlineBadge = trophy.online ? '<span class="trophy-online-badge">ONLINE</span>' : '';
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
    console.log('Trofeo:', trophy.nombre_trofeo, 'conseguido:', trophy.conseguido);
    return `
    <div class="trophy-card" data-conseguido="${trophy.conseguido ? 'true' : 'false'}">
      <div class="trophy-card-header">
        <img src="${trophy.icono_url || 'interfaz/trofeos/default.png'}" alt="${trophy.nombre_trofeo}" class="trophy-card-icon">
        <div class="trophy-card-info">
          <h3 class="trophy-card-name">${trophy.nombre_trofeo} ${perdibleBadge} ${onlineBadge}</h3>
          <p class="trophy-card-description">${trophy.descripcion}</p>
          <p class="trophy-card-instructions">${trophy.instrucciones ? decodeHTMLEntities(trophy.instrucciones) : ''}</p>
          ${videoEmbed ? `<div class="trophy-card-video">${videoEmbed}</div>` : ''}
        </div>
        <div class="trophy-card-right">
          <img src="${trophyImage}" alt="${trophy.tipo}" class="trophy-card-type-image">
        </div>
      </div>
    </div>
    `;
  }).join('');

  container.innerHTML = trophiesHTML;
}

function getTrophyImage(tipo) {
  const tipoLower = tipo.toLowerCase();
  const images = {
    'platino': getAssetUrl('interfaz/trofeos/platino.png'),
    'oro': getAssetUrl('interfaz/trofeos/oro.png'),
    'plata': getAssetUrl('interfaz/trofeos/plata.png'),
    'bronce': getAssetUrl('interfaz/trofeos/bronce.png')
  };
  return images[tipoLower] || getAssetUrl('interfaz/trofeos/default.png');
}

function filterTrophies(trophies, filter) {
  const container = document.getElementById('trophies-list');
  const cards = container.querySelectorAll('.trophy-card');
  
  cards.forEach(card => {
    const conseguido = card.dataset.conseguido === 'true';
    let show = false;
    
    switch(filter) {
      case 'todos':
        show = true;
        break;
      case 'ganados':
        show = conseguido;
        break;
      case 'no-ganados':
        show = !conseguido;
        break;
    }
    
    card.style.display = show ? 'flex' : 'none';
  });
}

// ==================== FUNCIONES PARA DLCs ====================

// Función para hacer scroll hasta un DLC específico
window.scrollToDLC = function(dlcId) {
  const dlcElement = document.querySelector(`.dlc-container[data-dlc-id="${dlcId}"]`);
  if (dlcElement) {
    dlcElement.scrollIntoView({ behavior: 'smooth' });
  }
};

async function loadDLCs(gameId) {
  try {
    const apiUrl = getApiUrl('dlcs.php');
    apiUrl.searchParams.set('game_id', gameId);
    const response = await fetch(apiUrl);
    const dlcs = await response.json();
    renderDLCs(dlcs);
  } catch (error) {
    console.error('Error al cargar DLCs:', error);
  }
}

function renderDLCs(dlcs) {
  const container = document.getElementById('dlcs-list');
  const iconsContainer = document.getElementById('dlc-icons-container');
  
  if (!dlcs || dlcs.length === 0) {
    container.innerHTML = '<p style="color: #666; text-align: center; padding: 1rem;">No hay DLCs para este juego</p>';
    if (iconsContainer) {
      iconsContainer.innerHTML = '';
    }
    return;
  }
  
  // Añadir iconos de DLCs al contenedor del título
  if (iconsContainer) {
    iconsContainer.innerHTML = dlcs.map(dlc => `
<<<<<<< HEAD
      <button type="button" onclick="scrollToDLC('${dlc.id}')" class="dlc-icon-btn" title="${dlc.nombre}">
        <img src="${dlc.imagen_url || getAssetUrl('interfaz/trofeos/default.png')}" alt="${dlc.nombre}">
=======
      <button type="button" onclick="scrollToDLC('${dlc.id}')" style="background: #1f1b35; border: 2px solid #2d2847; border-radius: 8px; width: 100px; height: 100px; padding: 0; cursor: pointer; transition: all 0.3s ease; overflow: hidden; display: flex; align-items: center; justify-content: center;" title="${dlc.nombre}">
        <img src="${dlc.imagen_url || getAssetUrl('interfaz/trofeos/default.png')}" alt="${dlc.nombre}" style="max-width: 100%; max-height: 100%; width: auto; height: auto; object-fit: contain;">
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
      </button>
    `).join('');
  }
  
  const getDlcBannerUrl = (dlc) => {
    const raw = (dlc.banner_url || '').toString().trim();
    if (!raw || raw === 'null' || raw === 'undefined') return '';
    return raw;
  };

  container.innerHTML = dlcs.map(dlc => {
    const bannerUrl = getDlcBannerUrl(dlc);
    const iconUrl = (dlc.imagen_url || '').toString().trim();
<<<<<<< HEAD
    
    // Usar datos del juego principal para clasificaciones, editor y género
    const editor = gameData?.desarrollador || '--';
    const generos = gameData?.genero || '--';
    const pegiUrl = gameData?.pegi_url || '';
    const clas1Url = gameData?.clasificacion_1_url || '';
    const clas2Url = gameData?.clasificacion_2_url || '';
    const clas3Url = gameData?.clasificacion_3_url || '';
    const showClas1 = gameData?.show_clas1 === true;
    const showClas2 = gameData?.show_clas2 === true;
    const showClas3 = gameData?.show_clas3 === true;
    
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
    const bannerMarkup = bannerUrl ? `
      <div class="game-banner dlc-banner">
        <img src="${bannerUrl}" alt="Banner de ${dlc.nombre}" onerror="this.style.display='none'; this.closest('.game-banner').classList.add('dlc-banner--missing');">
        <div class="banner-info-overlay dlc-banner-overlay">
          <div class="banner-info-content dlc-banner-content">
            <h3 class="banner-info-title dlc-banner-title">${dlc.nombre}</h3>
            <div class="banner-info-platform dlc-banner-meta">
              <span>Lanzamiento: ${dlc.fecha_lanzamiento ? new Date(dlc.fecha_lanzamiento).toLocaleDateString('es-ES') : '--'}</span>
              <span>DLC</span>
            </div>
<<<<<<< HEAD
            <div class="banner-info-details">
              <div class="banner-info-item">
                <span class="banner-info-label">Editor:</span>
                <span class="banner-info-value">${editor}</span>
              </div>
              <div class="banner-info-item">
                <span class="banner-info-label">Géneros:</span>
                <span class="banner-info-value">${generos}</span>
              </div>
            </div>
            <div class="banner-info-classifications">
              <div class="classification-item">
                <img src="${pegiUrl}" alt="PEGI" class="classification-img">
              </div>
              <div class="classification-item" style="display: ${showClas1 ? 'block' : 'none'};">
                <img src="${clas1Url}" alt="Clas1" class="classification-img">
              </div>
              <div class="classification-item" style="display: ${showClas2 ? 'block' : 'none'};">
                <img src="${clas2Url}" alt="Clas2" class="classification-img">
              </div>
              <div class="classification-item" style="display: ${showClas3 ? 'block' : 'none'};">
                <img src="${clas3Url}" alt="Clas3" class="classification-img">
              </div>
            </div>
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
          </div>
        </div>
      </div>
    ` : `
      <div class="game-banner dlc-banner dlc-banner--missing">
        <div class="dlc-banner-placeholder">${dlc.nombre}</div>
        <div class="banner-info-overlay dlc-banner-overlay">
          <div class="banner-info-content dlc-banner-content">
            <h3 class="banner-info-title dlc-banner-title">${dlc.nombre}</h3>
            <div class="banner-info-platform dlc-banner-meta">
              <span>Lanzamiento: ${dlc.fecha_lanzamiento ? new Date(dlc.fecha_lanzamiento).toLocaleDateString('es-ES') : '--'}</span>
              <span>DLC</span>
            </div>
<<<<<<< HEAD
            <div class="banner-info-details">
              <div class="banner-info-item">
                <span class="banner-info-label">Editor:</span>
                <span class="banner-info-value">${editor}</span>
              </div>
              <div class="banner-info-item">
                <span class="banner-info-label">Géneros:</span>
                <span class="banner-info-value">${generos}</span>
              </div>
            </div>
            <div class="banner-info-classifications">
              <div class="classification-item">
                <img src="${pegiUrl}" alt="PEGI" class="classification-img">
              </div>
              <div class="classification-item" style="display: ${showClas1 ? 'block' : 'none'};">
                <img src="${clas1Url}" alt="Clas1" class="classification-img">
              </div>
              <div class="classification-item" style="display: ${showClas2 ? 'block' : 'none'};">
                <img src="${clas2Url}" alt="Clas2" class="classification-img">
              </div>
              <div class="classification-item" style="display: ${showClas3 ? 'block' : 'none'};">
                <img src="${clas3Url}" alt="Clas3" class="classification-img">
              </div>
            </div>
=======
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
          </div>
        </div>
      </div>
    `;

    return `
    <div class="dlc-container" data-dlc-id="${dlc.id}">
      <div class="game-header dlc-header">
        <div class="game-icon dlc-icon">
          <img src="${iconUrl || getAssetUrl('interfaz/trofeos/default.png')}" alt="${dlc.nombre}">
          <i class="fas fa-gamepad"></i>
        </div>
        <div class="game-info dlc-info">
          <h2 class="game-title dlc-title">${dlc.nombre}</h2>
          <div class="platform-badge dlc-platform-badge">DLC</div>
        </div>
      </div>
      ${bannerMarkup}
      </div>

<!-- Galería del DLC -->
        <div class="content-card dlc-content-card extra-card dlc-gallery-card" 
<<<<<<< HEAD
          style="background: #000; border-radius: 8px; overflow: hidden; padding: 0; height: 420px; display: block; margin-top: 2rem;">
        <div class="media-carousel" id="media-carousel-${dlc.id}" style="height: 420px;"></div>
=======
          style="background: #000; border-radius: 8px; overflow: hidden; padding: 0; height: 450px; display: block; margin-top: 2rem;">
        <div class="media-gallery-header" style="padding: 1rem;">
          <h2 class="trophies-title">Galería del DLC</h2>
        </div>
        <div class="media-carousel" id="media-carousel-${dlc.id}" style="height: 400px;"></div>
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
      </div>

      <div class="content-card dlc-content-card extra-card">
        <div class="game-info-content">
          ${dlc.descripcion ? `
          <div class="info-row full-width">
            <span class="info-label">Descripción:</span>
            <p class="info-comment">${decodeHTMLEntities(dlc.descripcion)}</p>
          </div>
          ` : ''}
          ${dlc.duracion_estimada ? `
          <div class="info-row">
            <span class="info-label">Duración estimada:</span>
            <span class="info-value">${dlc.duracion_estimada}</span>
          </div>
          ` : ''}
          ${dlc.dificultad_platino ? `
          <div class="info-row">
            <span class="info-label">Dificultad del Platino:</span>
            <span class="info-value">${dlc.dificultad_platino}</span>
          </div>
          ` : ''}
          <div class="info-row">
            <span class="info-label">Trofeos Offline:</span>
            <div class="trophy-summary">
              <div class="trophy-mini">
                <img src="${getAssetUrl('interfaz/trofeos/oro.png')}" alt="Oro">
                <span>${dlc.trofeos_offline_oro || 0}</span>
              </div>
              <div class="trophy-mini">
                <img src="${getAssetUrl('interfaz/trofeos/plata.png')}" alt="Plata">
                <span>${dlc.trofeos_offline_plata || 0}</span>
              </div>
              <div class="trophy-mini">
                <img src="${getAssetUrl('interfaz/trofeos/bronce.png')}" alt="Bronce">
                <span>${dlc.trofeos_offline_bronce || 0}</span>
              </div>
            </div>
          </div>
          <div class="info-row">
            <span class="info-label">Trofeos Online:</span>
            <div class="trophy-summary">
              <div class="trophy-mini">
                <img src="${getAssetUrl('interfaz/trofeos/oro.png')}" alt="Oro">
                <span>${dlc.trofeos_online_oro || 0}</span>
              </div>
              <div class="trophy-mini">
                <img src="${getAssetUrl('interfaz/trofeos/plata.png')}" alt="Plata">
                <span>${dlc.trofeos_online_plata || 0}</span>
              </div>
              <div class="trophy-mini">
                <img src="${getAssetUrl('interfaz/trofeos/bronce.png')}" alt="Bronce">
                <span>${dlc.trofeos_online_bronce || 0}</span>
              </div>
            </div>
          </div>
          ${dlc.trofeos_perdibles ? `
          <div class="info-row">
            <span class="info-label">Trofeos Perdibles:</span>
            <span class="info-value">${dlc.trofeos_perdibles}</span>
          </div>
          ` : ''}
      </div>
        <div class="dlc-trophies-list" id="dlc-trophies-${dlc.id}">
          <!-- Los trofeos del DLC se cargarán aquí -->
        </div>
      </div>
      
      </div>
    </div>
  `;
  }).join('');

  console.log('DLCs renderizados. Contenedores de galería:', dlcs.map(dlc => `media-carousel-${dlc.id}`));

  // Verificar si los contenedores de galería existen en el DOM
  setTimeout(() => {
    dlcs.forEach(dlc => {
      const galleryContainer = document.getElementById(`media-carousel-${dlc.id}`);
      console.log(`Contenedor de galería para DLC ${dlc.id}:`, galleryContainer ? 'EXISTS' : 'NOT FOUND');
      if (galleryContainer) {
        console.log('Contenedor de galería HTML:', galleryContainer.outerHTML);
      }
    });
  }, 500);

  // Configurar lightbox para imágenes de DLCs
  setTimeout(setupImageLightbox, 100);

  // Configurar spoilers
  setTimeout(setupSpoilers, 100);

  // Cargar media y trofeos de cada DLC
  dlcs.forEach(dlc => {
    console.log(`Cargando media y trofeos para DLC ${dlc.id}`);
    loadDlcMedia(dlc.id, `media-carousel-${dlc.id}`);
    loadDLCTrophies(dlc.id);
  });
}

async function loadDLCTrophies(dlcId) {
  try {
    const apiUrl = getApiUrl('dlc_trophies.php');
    apiUrl.searchParams.set('dlc_id', dlcId);
    const response = await fetch(apiUrl);
    const trophies = await response.json();
    
    // Almacenar trofeos del DLC en la variable global
    dlcTrophies = [...dlcTrophies, ...trophies];
    
    renderDLCTrophies(dlcId, trophies);
    
    // Actualizar contadores después de cargar trofeos del DLC
    updateTrophyCounters(allTrophies);
  } catch (error) {
    console.error('Error al cargar trofeos del DLC:', error);
  }
}

function renderDLCTrophies(dlcId, trophies) {
  const container = document.getElementById(`dlc-trophies-${dlcId}`);
  
  if (!trophies || trophies.length === 0) {
    container.innerHTML = '<p style="color: #666; font-size: 0.9rem; padding: 0.5rem;">No hay trofeos en este DLC</p>';
    return;
  }
  
  const trophiesHTML = trophies.map((trophy) => {
    const trophyImage = getTrophyImage(trophy.tipo);
    const videoEmbed = trophy.video_url ? getYouTubeEmbed(trophy.video_url) : '';
    const perdibleBadge = trophy.perdible ? '<span class="trophy-perdible-badge">PERDIBLE</span>' : '';
<<<<<<< HEAD
    const onlineBadge = trophy.online ? '<i class="fas fa-globe trophy-online-icon" title="Online"></i>' : '';
=======
    const onlineBadge = trophy.online ? '<span class="trophy-online-badge">ONLINE</span>' : '';
>>>>>>> 31e3254f6c608c81655c7380abbf9d2b1baf435a
    console.log('Trofeo DLC:', trophy.nombre_trofeo, 'conseguido:', trophy.conseguido);
    return `
    <div class="trophy-card" data-conseguido="${trophy.conseguido ? 'true' : 'false'}">
      <div class="trophy-card-header">
        <img src="${trophy.icono_url || 'interfaz/trofeos/default.png'}" alt="${trophy.nombre_trofeo}" class="trophy-card-icon">
        <div class="trophy-card-info">
          <h3 class="trophy-card-name">${trophy.nombre_trofeo} ${perdibleBadge} ${onlineBadge}</h3>
          <p class="trophy-card-description">${trophy.descripcion}</p>
          <p class="trophy-card-instructions">${trophy.instrucciones ? decodeHTMLEntities(trophy.instrucciones) : ''}</p>
          ${videoEmbed ? `<div class="trophy-card-video">${videoEmbed}</div>` : ''}
        </div>
        <div class="trophy-card-right">
          <img src="${trophyImage}" alt="${trophy.tipo}" class="trophy-card-type-image">
        </div>
      </div>
    </div>
    `;
  }).join('');

  container.innerHTML = `
    <div class="dlc-trophies-section">
      <div class="trophies-header dlc-trophies-header">
        <h2 class="trophies-title">Trofeos del DLC</h2>
        <div class="filter-dropdown">
          <select id="dlc-trophy-filter-${dlcId}">
            <option value="todos">Todos</option>
            <option value="ganados">Ganados</option>
            <option value="no-ganados">No ganados</option>
          </select>
          <i class="fas fa-chevron-down dropdown-icon"></i>
        </div>
      </div>
      <div class="trophies-list dlc-trophies-list" id="dlc-trophies-list-${dlcId}">
        ${trophiesHTML}
      </div>
    </div>
  `;

  // Añadir event listener al desplegable de filtro del DLC
  document.getElementById(`dlc-trophy-filter-${dlcId}`).addEventListener('change', (e) => {
    const filter = e.target.value;
    const trophiesList = document.getElementById(`dlc-trophies-list-${dlcId}`);
    const cards = trophiesList.querySelectorAll('.trophy-card');
    
    cards.forEach(card => {
      const conseguido = card.dataset.conseguido === 'true';
      let show = false;
      
      switch(filter) {
        case 'todos':
          show = true;
          break;
        case 'ganados':
          show = conseguido;
          break;
        case 'no-ganados':
          show = !conseguido;
          break;
      }
      
      card.style.display = show ? 'flex' : 'none';
    });
  });
}

// Funciones del carrusel (incluidas desde carousel.php)
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

  // Crear elementos del carrusel si no existen
  let track = container.querySelector('.media-carousel-track') || container.querySelector('[data-track]');
  let dots = container.querySelector('.media-carousel-dots') || container.querySelector('[data-dots]');
  let prev = container.querySelector('.media-carousel-btn--prev') || container.querySelector('[data-prev]');
  let next = container.querySelector('.media-carousel-btn--next') || container.querySelector('[data-next]');

  // Crear elementos si no existen
  if (!track) {
    track = document.createElement('div');
    track.className = 'media-carousel-track';
    container.appendChild(track);
  }
  if (!dots) {
    dots = document.createElement('div');
    dots.className = 'media-carousel-dots';
    container.appendChild(dots);
  }
  if (!prev) {
    prev = document.createElement('button');
    prev.className = 'media-carousel-btn media-carousel-btn--prev';
    prev.type = 'button';
    prev.setAttribute('aria-label', 'Anterior');
    prev.innerHTML = '<i class="fas fa-chevron-left"></i>';
    container.appendChild(prev);
  }
  if (!next) {
    next = document.createElement('button');
    next.className = 'media-carousel-btn media-carousel-btn--next';
    next.type = 'button';
    next.setAttribute('aria-label', 'Siguiente');
    next.innerHTML = '<i class="fas fa-chevron-right"></i>';
    container.appendChild(next);
  }

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
    
    console.log(`Rendering item ${index}:`, { tipo: item.tipo, url: item.url, isVideo });
    
    const mediaContent = isVideo
      ? (embedHtml || `<video controls src="${item.url}" preload="metadata" style="width: 100%; height: 100%; object-fit: contain;"></video>`)
      : `<img src="${item.url}" alt="Media" style="width: 100%; height: 100%; object-fit: contain;" onerror="console.error('Error loading image:', this.src); this.style.display='none'; this.parentElement.innerHTML='<span style=color:#fff>Imagen no disponible</span>'" onload="console.log('Image loaded successfully:', this.src)">`;

    return `
      <div class="media-slide" data-index="${index}">
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
    const response = await fetch(`../api/game_media.php?game_id=${gameId}`);
    const media = await response.json();
    renderMediaCarousel(containerId, media);
  } catch (error) {
    console.error('Error cargando media del juego:', error);
  }
}

async function loadDlcMedia(dlcId, containerId) {
  try {
    console.log(`Cargando media del DLC ${dlcId} en contenedor ${containerId}`);
    const response = await fetch(`../api/game_media.php?dlc_id=${dlcId}`);
    const media = await response.json();
    console.log(`Media del DLC ${dlcId}:`, media);
    renderMediaCarousel(containerId, media);
  } catch (error) {
    console.error('Error cargando media del DLC:', error);
  }
}
