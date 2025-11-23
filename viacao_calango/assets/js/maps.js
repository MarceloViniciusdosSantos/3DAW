let mapsInitialized = false;
const routeRenderers = {};

function initMaps() {
    console.log('Google Maps API carregada');
    mapsInitialized = true;
    
    document.querySelectorAll('.route-map').forEach(mapElement => {
        // Só inicializa o mapa se tiver coordenadas
        if (mapElement.dataset.originLat && mapElement.dataset.originLng) {
            initMap(mapElement);
        } else {
            console.log('Mapa sem coordenadas:', mapElement.id);
        }
    });
}

function initMap(mapElement) {
    try {
        const originLat = parseFloat(mapElement.dataset.originLat);
        const originLng = parseFloat(mapElement.dataset.originLng);
        const destLat = parseFloat(mapElement.dataset.destLat);
        const destLng = parseFloat(mapElement.dataset.destLng);
        const originName = mapElement.dataset.originName;
        const destName = mapElement.dataset.destName;
        const routeId = mapElement.id.replace('map-', '');
        
        console.log('Inicializando mapa:', routeId);
        
        // Ponto médio para centralização
        const centerLat = (originLat + destLat) / 2;
        const centerLng = (originLng + destLng) / 2;
        
        const map = new google.maps.Map(mapElement, {
            zoom: 6,
            center: { lat: centerLat, lng: centerLng },
            mapTypeId: 'roadmap',
            gestureHandling: 'cooperative',
            styles: [
                {
                    featureType: "all",
                    stylers: [{ saturation: -100 }]
                }
            ]
        });
        
        // Marcadores simples
        new google.maps.Marker({
            position: { lat: originLat, lng: originLng },
            map: map,
            title: 'Origem: ' + originName,
            label: 'O'
        });
        
        new google.maps.Marker({
            position: { lat: destLat, lng: destLng },
            map: map,
            title: 'Destino: ' + destName,
            label: 'D'
        });
        
        // Linha reta entre os pontos 
        const routeLine = new google.maps.Polyline({
            path: [
                { lat: originLat, lng: originLng },
                { lat: destLat, lng: destLng }
            ],
            geodesic: true,
            strokeColor: '#FF6B00',
            strokeOpacity: 1.0,
            strokeWeight: 3
        });
        
        routeLine.setMap(map);
        
        // Calcular distância aproximada
        const distance = google.maps.geometry.spherical.computeDistanceBetween(
            new google.maps.LatLng(originLat, originLng),
            new google.maps.LatLng(destLat, destLng)
        );
        
        const distanceElement = document.getElementById(`distance-${routeId}`);
        if (distanceElement) {
            const distanceKm = (distance / 1000).toFixed(0);
            distanceElement.textContent = `${distanceKm} km (aproximado)`;
        }
        
        // Ajustar zoom para mostrar ambos os pontos
        const bounds = new google.maps.LatLngBounds();
        bounds.extend(new google.maps.LatLng(originLat, originLng));
        bounds.extend(new google.maps.LatLng(destLat, destLng));
        map.fitBounds(bounds, { padding: 30 });
        
        // Remove o placeholder
        const placeholder = mapElement.querySelector('.map-placeholder');
        if (placeholder) {
            placeholder.style.display = 'none';
        }
        
    } catch (error) {
        console.error('Erro no mapa ' + mapElement.id + ':', error);
        showMapError(mapElement);
    }
}

function showMapError(mapElement) {
    mapElement.innerHTML = `
        <div class="map-fallback" style="padding: 20px; text-align: center; color: #666;">
            <p> Mapa não pôde ser carregado</p>
            <small>Tente atualizar a página</small>
        </div>
    `;
}

// Função para tratamento de erros da API
window.gm_authFailure = function() {
    console.error('Erro de autenticação do Google Maps');
    document.querySelectorAll('.route-map').forEach(mapElement => {
        showMapError(mapElement);
    });
};
