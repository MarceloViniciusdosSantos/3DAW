<div class="box">
  <h2>Escolha Sua Viagem</h2>
  <p>Selecione o destino e o tipo de assento desejado</p>

  <div class="travels-grid">
    <?php foreach($routes as $route): ?>
      <?php $coords = $route_coordinates[$route['id']] ?? null; ?>
      <div class="travel-card">
        <div class="travel-header">
          <h3><?php echo htmlspecialchars($route['origin'] . ' → ' . $route['destination']); ?></h3>
          <span class="duration"><?php echo $route['duration_minutes']; ?> min</span>
        </div>
        
        <!-- Mapa da Rota -->
        <?php if($coords): ?>
        <div class="route-map" id="map-<?php echo $route['id']; ?>" 
             data-origin-lat="<?php echo $coords['origin']['lat']; ?>"
             data-origin-lng="<?php echo $coords['origin']['lng']; ?>"
             data-dest-lat="<?php echo $coords['destination']['lat']; ?>"
             data-dest-lng="<?php echo $coords['destination']['lng']; ?>"
             data-origin-name="<?php echo htmlspecialchars($route['origin']); ?>"
             data-dest-name="<?php echo htmlspecialchars($route['destination']); ?>"
             data-duration="<?php echo $route['duration_minutes']; ?>">
          <div class="map-placeholder">
            <div class="loading-spinner"></div>
            Carregando mapa...
          </div>
        </div>
        <?php else: ?>
        <div class="route-map">
          <div class="map-fallback">
            <p> Mapa indisponível</p>
            <small>Coordenadas não encontradas</small>
          </div>
        </div>
        <?php endif; ?>
        
        <div class="travel-details">
          <p><strong>Origem:</strong> <?php echo htmlspecialchars($route['origin']); ?></p>
          <p><strong>Destino:</strong> <?php echo htmlspecialchars($route['destination']); ?></p>
          <p><strong>Duração:</strong> <?php echo $route['duration_minutes']; ?> minutos</p>
          <p><strong>Distância:</strong> <span id="distance-<?php echo $route['id']; ?>">Calculando...</span></p>
        </div>

        <div class="seat-types">
          <h4>Tipos de Assento:</h4>
          <?php foreach($seat_types as $type): ?>
            <?php 
            $final_price = $route['base_price'] * $type['multiplier'];
            $price_difference = $final_price - $route['base_price'];
            ?>
            <div class="seat-type-option">
              <div class="type-info">
                <strong><?php echo htmlspecialchars($type['name']); ?></strong>
                <span class="type-description"><?php echo htmlspecialchars($type['description']); ?></span>
              </div>
              <div class="type-price">
                <div class="final-price">R$ <?php echo number_format($final_price, 2, ',', '.'); ?></div>
                <?php if($price_difference > 0): ?>
                  <div class="price-diff">+ R$ <?php echo number_format($price_difference, 2, ',', '.'); ?></div>
                <?php endif; ?>
              </div>
              <a href="<?php echo $base_path; ?>/comprar.php?route_id=<?php echo $route['id']; ?>&seat_type=<?php echo $type['id']; ?>" class="btn-select">
                Selecionar
              </a>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="travel-footer">
          <div class="base-price">
            Preço base: R$ <?php echo number_format($route['base_price'], 2, ',', '.'); ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- API -->
<script>
let mapsInitialized = false;
let mapsLoadAttempted = false;

function debugLog(message) {
    console.log('[Google Maps Debug]', message);
}

window.initMap = function() {
    if (mapsInitialized) {
        debugLog('Mapas já inicializados');
        return;
    }
    
    if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
        debugLog('API não carregou');
        showAllMapErrors('API não carregada');
        return;
    }
    
    mapsInitialized = true;
    debugLog('API carregada');
    
    // Inicializa os mapas
    const mapElements = document.querySelectorAll('.route-map');
    debugLog(`Encontrados ${mapElements.length} mapas para inicializar`);
    
    mapElements.forEach((mapElement, index) => {
        debugLog(`Inicializando mapa ${index + 1}: ${mapElement.id}`);
        
        if (mapElement.dataset.originLat && mapElement.dataset.originLng) {
            setTimeout(() => window.initSingleMap(mapElement), index * 500);
        } else {
            debugLog(`Mapa ${mapElement.id} sem coordenadas`);
            showMapError(mapElement, 'Coordenadas não disponíveis');
        }
    });
};
window.initSingleMap = function(mapElement) {
    try {
        debugLog(`Iniciando mapa: ${mapElement.id}`);
        
        const originLat = parseFloat(mapElement.dataset.originLat);
        const originLng = parseFloat(mapElement.dataset.originLng);
        const destLat = parseFloat(mapElement.dataset.destLat);
        const destLng = parseFloat(mapElement.dataset.destLng);
        const originName = mapElement.dataset.originName;
        const destName = mapElement.dataset.destName;
        const routeId = mapElement.id.replace('map-', '');
        
        debugLog(`Coordenadas - Origem: ${originLat}, ${originLng} | Destino: ${destLat}, ${destLng}`);
        
        // Ponto médio para centralização
        const centerLat = (originLat + destLat) / 2;
        const centerLng = (originLng + destLng) / 2;
        
        const mapOptions = {
            zoom: 6,
            center: { lat: centerLat, lng: centerLng },
            mapTypeId: 'roadmap',
            gestureHandling: 'cooperative',
            zoomControl: true,
            mapTypeControl: false,
            scaleControl: true,
            streetViewControl: false,
            rotateControl: false,
            fullscreenControl: true
        };
        
        debugLog('Criando instância do mapa');
        const map = new google.maps.Map(mapElement, mapOptions);
        
        // Marcador de origem
        debugLog('Adicionando marcador de origem');
        new google.maps.Marker({
            position: { lat: originLat, lng: originLng },
            map: map,
            title: 'Origem: ' + originName,
            icon: {
                url: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMTIiIGN5PSIxMiIgcj0iMTAiIGZpbGw9IiMyN0FFNjAiLz4KPC9zdmc+',
                scaledSize: new google.maps.Size(20, 20),
                anchor: new google.maps.Point(10, 10)
            }
        });
        
        // Marcador de destino
        debugLog('Adicionando marcador de destino');
        new google.maps.Marker({
            position: { lat: destLat, lng: destLng },
            map: map,
            title: 'Destino: ' + destName,
            icon: {
                url: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMTIiIGN5PSIxMiIgcj0iMTAiIGZpbGw9IiNFRjQ0MzUiLz4KPC9zdmc+',
                scaledSize: new google.maps.Size(20, 20),
                anchor: new google.maps.Point(10, 10)
            }
        });
        
        // Linha reta entre os pontos
        debugLog('Desenhando rota');
        const routePath = new google.maps.Polyline({
            path: [
                { lat: originLat, lng: originLng },
                { lat: destLat, lng: destLng }
            ],
            geodesic: true,
            strokeColor: '#3498db',
            strokeOpacity: 1.0,
            strokeWeight: 3
        });
        
        routePath.setMap(map);
        
        // Calcular distância
        debugLog('Calculando distância');
        const distance = google.maps.geometry.spherical.computeDistanceBetween(
            new google.maps.LatLng(originLat, originLng),
            new google.maps.LatLng(destLat, destLng)
        );
        
        const distanceElement = document.getElementById(`distance-${routeId}`);
        if (distanceElement) {
            const distanceKm = (distance / 1000).toFixed(0);
            distanceElement.textContent = `${distanceKm} km`;
            debugLog(`Distância calculada: ${distanceKm} km`);
        }
        
        // Ajustar zoom
        debugLog('Ajustando zoom do mapa');
        const bounds = new google.maps.LatLngBounds();
        bounds.extend(new google.maps.LatLng(originLat, originLng));
        bounds.extend(new google.maps.LatLng(destLat, destLng));
        map.fitBounds(bounds, { padding: 30 });
        
        // Remover placeholder
        const placeholder = mapElement.querySelector('.map-placeholder');
        if (placeholder) {
            placeholder.style.display = 'none';
            debugLog('Placeholder removido');
        }
        
        debugLog(`Mapa ${mapElement.id} inicializado com sucesso`);
        
    } catch (error) {
        console.error(`Erro no mapa ${mapElement.id}:`, error);
        debugLog(`ERRO no mapa ${mapElement.id}: ${error.message}`);
        showMapError(mapElement, 'Erro na inicialização');
    }
};

function loadGoogleMaps() {
    if (mapsLoadAttempted) {
        debugLog('Tentativa de carregamento já realizada');
        return;
    }
    
    mapsLoadAttempted = true;
    debugLog('Iniciando carregamento do Google Maps API');
    
    // Verifica se a API já foi carregada
    if (typeof google !== 'undefined' && google.maps) {
        debugLog('Google Maps já está carregado');
        window.initMap();
        return;
    }
    
    const script = document.createElement('script');
    const apiKey = 'AIzaSyCwxpz7QFYLUai2f2k6uOfD9RXJyjgiHpc';
    script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&callback=initMap&libraries=geometry`;
    script.async = true;
    script.defer = true;
    
    script.onload = function() {
        debugLog('Script Google Maps carregado com sucesso');
    };
    
    script.onerror = function() {
        debugLog('ERRO: Falha ao carregar script Google Maps');
        console.error('Detalhes do erro:', this.src);
        showAllMapErrors('Falha no carregamento da API');
    };
    
    document.head.appendChild(script);
    debugLog('Script adicionado ao HEAD');
}

function showMapError(mapElement, message) {
    mapElement.innerHTML = `
        <div class="map-error">
            <p>❌ ${message}</p>
            <small>ID: ${mapElement.id}</small>
        </div>
    `;
}

function showAllMapErrors(message) {
    document.querySelectorAll('.route-map').forEach(mapElement => {
        if (!mapElement.querySelector('.map-error')) {
            showMapError(mapElement, message);
        }
    });
}

// Inicialização
debugLog('Página carregada, iniciando processo...');

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        debugLog('DOM Content Loaded');
        loadGoogleMaps();
    });
} else {
    debugLog('DOM já carregado');
    loadGoogleMaps();
}

// Timeout de fallback
setTimeout(() => {
    if (!mapsInitialized) {
        debugLog('TIMEOUT: Mapas não inicializados após 10 segundos');
        showAllMapErrors('Timeout no carregamento');
    }
}, 10000);

// Tratamento de erro de autenticação
window.gm_authFailure = function() {
    debugLog('ERRO DE AUTENTICAÇÃO: API Key inválida ou sem permissões');
    showAllMapErrors('Erro de autenticação - Verifique a API Key');
};
</script>