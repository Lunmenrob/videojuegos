// Variables globales para almacenar todos los trofeos
let allTrophies = [];
let dlcTrophies = [];
let gameData = null;

const isPublicPage = window.location.pathname.includes('/publico/');
const apiBasePath = isPublicPage ? '../api/' : 'api/';
const assetBasePath = isPublicPage ? '../' : '';

function getApiUrl(path) {
  return new URL(`${apiBasePath}${path}`, window.location.href);
}

function getAssetUrl(path) {
  return `${assetBasePath}${path}`;
}

console.log('detalles.js loaded');

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
  const editLinkHeader = document.getElementById('edit-link-header');
  const editLinkActions = document.getElementById('edit-link-actions');
  if (editLinkHeader) {
    editLinkHeader.href = `editar_juego.php?id=${gameId}`;
  }
  if (editLinkActions) {
    editLinkActions.href = `editar_juego.php?id=${gameId}`;
  }

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
      if (data.clasificacion_1_url && data.show_clas1 === true) {
        document.getElementById('banner-1').src = data.clasificacion_1_url;
        document.getElementById('banner-1-container').style.display = 'block';
      } else {
        document.getElementById('banner-1-container').style.display = 'none';
      }
      if (data.clasificacion_2_url && data.show_clas2 === true) {
        document.getElementById('banner-2').src = data.clasificacion_2_url;
        document.getElementById('banner-2-container').style.display = 'block';
      } else {
        document.getElementById('banner-2-container').style.display = 'none';
      }
      if (data.clasificacion_3_url && data.show_clas3 === true) {
        document.getElementById('banner-3').src = data.clasificacion_3_url;
        document.getElementById('banner-3-container').style.display = 'block';
      } else {
        document.getElementById('banner-3-container').style.display = 'none';
      }

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
        document.getElementById('trofeos-ocultos').innerHTML = data.trofeos_ocultos;
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

      gameData = data;
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
    console.log('loadTrophies - URL:', apiUrl.toString());
    const response = await fetch(apiUrl);
    console.log('loadTrophies - Response status:', response.status);
    const trophies = await response.json();
    console.log('loadTrophies - Trophies received:', trophies);
    allTrophies = trophies; // Almacenar trofeos del juego base
    renderTrophies(trophies);
    
    // Actualizar contadores iniciales
    updateTrophyCounters(trophies);
    
    // Añadir event listener al desplegable de filtro
    document.getElementById('trophy-filter').addEventListener('change', (e) => {
      const filter = e.target.value;
      filterTrophies(trophies, filter);
    });
    
    document.querySelectorAll('.trophy-checkbox input').forEach(checkbox => {
      console.log('Añadiendo event listener a checkbox:', checkbox.dataset.trophyId);
      checkbox.addEventListener('change', (e) => {
        const trophyId = e.target.dataset.trophyId;
        const isConseguido = e.target.checked;

        console.log('Checkbox cambiado:', trophyId, isConseguido);

        // Actualizar el data attribute del card
        const card = e.target.closest('.trophy-card');
        card.dataset.conseguido = isConseguido ? 'true' : 'false';

        // Actualizar contadores
        updateTrophyCounters(trophies);

        // Guardar el cambio en la base de datos
        const payload = {
          id: trophyId,
          conseguido: isConseguido
        };
        console.log('Enviando payload a API:', payload);

        const updateApiUrl = getApiUrl('trophies.php');
        fetch(updateApiUrl, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(payload)
        })
        .then(res => {
          console.log('Respuesta de API:', res.status, res.statusText);
          return res.json();
        })
        .then(data => {
          console.log('Trofeo actualizado:', data);
        })
        .catch(err => {
          console.error('Error al actualizar trofeo:', err);
        });
      });
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
    const checkbox = document.querySelector(`input[data-trophy-id="${trophy.id}"]`);
    const isConseguido = checkbox ? checkbox.checked : trophy.conseguido;
    
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
    const onlineBadge = trophy.online ? '<i class="fas fa-globe trophy-online-icon" title="Online"></i>' : '';
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
          ${!window.detailsPage ? `
          <label class="trophy-checkbox">
            <input type="checkbox" ${trophy.conseguido ? 'checked' : ''} data-trophy-id="${trophy.id}">
            <span class="checkmark"></span>
          </label>
          ` : ''}
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
      <button type="button" onclick="scrollToDLC('${dlc.id}')" class="dlc-icon-btn" title="${dlc.nombre}">
        <img src="${dlc.imagen_url || getAssetUrl('interfaz/trofeos/default.png')}" alt="${dlc.nombre}">
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
          style="background: #000; border-radius: 8px; overflow: hidden; padding: 0; height: 420px; display: block; margin-top: 2rem;">
        <div class="media-carousel" id="media-carousel-${dlc.id}" style="height: 420px;"></div>
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
    const onlineBadge = trophy.online ? '<i class="fas fa-globe trophy-online-icon" title="Online"></i>' : '';
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
          ${!window.detailsPage ? `
          <label class="trophy-checkbox">
            <input type="checkbox" ${trophy.conseguido ? 'checked' : ''} data-trophy-id="${trophy.id}" data-dlc-trophy="true">
            <span class="checkmark"></span>
          </label>
          ` : ''}
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

  // Añadir event listeners a los checkboxes de los trofeos de DLC
  container.querySelectorAll('.trophy-checkbox input').forEach(checkbox => {
    console.log('Añadiendo event listener a checkbox de DLC:', checkbox.dataset.trophyId);
    checkbox.addEventListener('change', (e) => {
      const trophyId = e.target.dataset.trophyId;
      const isConseguido = e.target.checked;

      console.log('Checkbox de DLC cambiado:', trophyId, isConseguido);

      // Actualizar el data attribute del card
      const card = e.target.closest('.trophy-card');
      card.dataset.conseguido = isConseguido ? 'true' : 'false';

      // Actualizar contadores
      updateTrophyCounters(allTrophies);

      // Guardar el cambio en la base de datos
      const payload = {
        id: trophyId,
        conseguido: isConseguido
      };
      console.log('Enviando payload de DLC a API:', payload);

      const updateApiUrl = getApiUrl('trophies.php');
      fetch(updateApiUrl, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload)
      })
      .then(res => {
        console.log('Respuesta de API para DLC:', res.status, res.statusText);
        return res.json();
      })
      .then(data => {
        console.log('Trofeo de DLC actualizado:', data);
      })
      .catch(err => {
        console.error('Error al actualizar trofeo de DLC:', err);
      });
    });
  });
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
