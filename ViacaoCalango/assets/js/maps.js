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
    const originLat = parseFloat(mapElement.dataset.originLat);
    const originLng = parseFloat(mapElement.dataset.originLng);
    const destLat = parseFloat(mapElement.dataset.destLat);
    const destLng = parseFloat(mapElement.dataset.destLng);
    const originName = mapElement.dataset.originName;
    const destName = mapElement.dataset.destName;
    const duration = parseInt(mapElement.dataset.duration);
    const routeId = mapElement.id.replace('map-', '');
    
    console.log('Inicializando mapa para rota:', routeId, originName, '→', destName);
    
    // Calcular ponto médio para o zoom inicial
    const centerLat = (originLat + destLat) / 2;
    const centerLng = (originLng + destLng) / 2;
    
    try {
        const map = new google.maps.Map(mapElement, {
            zoom: 6,
            center: { lat: centerLat, lng: centerLng },
            mapTypeId: 'roadmap',
            styles: [
                {
                    "featureType": "all",
                    "elementType": "geometry",
                    "stylers": [{ "color": "#242f3e" }]
                },
                {
                    "featureType": "all",
                    "elementType": "labels.text.stroke",
                    "stylers": [{ "color": "#242f3e" }]
                },
                {
                    "featureType": "all",
                    "elementType": "labels.text.fill",
                    "stylers": [{ "color": "#746855" }]
                },
                {
                    "featureType": "water",
                    "elementType": "geometry",
                    "stylers": [{ "color": "#17263c" }]
                }
            ]
        });
        
        // Marcador de Origem
        const originMarker = new google.maps.Marker({
            position: { lat: originLat, lng: originLng },
            map: map,
            title: originName,
            icon: {
                url: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png',
                scaledSize: new google.maps.Size(32, 32)
            }
        });
        
        // Marcador de Destino
        const destMarker = new google.maps.Marker({
            position: { lat: destLat, lng: destLng },
            map: map,
            title: destName,
            icon: {
                url: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png',
                scaledSize: new google.maps.Size(32, 32)
            }
        });
        
        // Info windows
        const originInfo = new google.maps.InfoWindow({
            content: `
                <div class="map-info-window">
                    <h4>${originName}</h4>
                    <p><strong> Origem da viagem</strong></p>
                    <small>Embarque neste local</small>
                </div>
            `
        });
        
        const destInfo = new google.maps.InfoWindow({
            content: `
                <div class="map-info-window">
                    <h4>${destName}</h4>
                    <p><strong>Destino da viagem</strong></p>
                    <small>Chegada aproximada: ${Math.round(duration / 60)}h ${duration % 60}min</small>
                </div>
            `
        });
        
        originMarker.addListener('click', () => {
            originInfo.open(map, originMarker);
        });
        
        destMarker.addListener('click', () => {
            destInfo.open(map, destMarker);
        });
        
        // Calcular e exibir a rota
        calculateAndDisplayRoute(map, originLat, originLng, destLat, destLng, routeId);
        
    } catch (error) {
        console.error('Erro ao inicializar mapa:', error);
        showMapError(mapElement, 'Erro ao carregar mapa');
    }
}

function calculateAndDisplayRoute(map, originLat, originLng, destLat, destLng, routeId) {
    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer({
        map: map,
        suppressMarkers: true,
        preserveViewport: true,
        polylineOptions: {
            strokeColor: '#FFCC00',
            strokeOpacity: 1.0,
            strokeWeight: 4
        }
    });
    
    const request = {
        origin: { lat: originLat, lng: originLng },
        destination: { lat: destLat, lng: destLng },
        travelMode: 'DRIVING',
        unitSystem: google.maps.UnitSystem.METRIC
    };
    
    directionsService.route(request, function(result, status) {
        const distanceElement = document.getElementById(`distance-${routeId}`);
        
        if (status === 'OK') {
            directionsRenderer.setDirections(result);
            
            const route = result.routes[0];
            const leg = route.legs[0];
            
            if (distanceElement && leg.distance) {
                distanceElement.textContent = leg.distance.text;
            }
            
            // Ajustar o zoom para mostrar toda a rota
            const bounds = new google.maps.LatLngBounds();
            bounds.extend(leg.start_location);
            bounds.extend(leg.end_location);
            
            if (leg.steps && leg.steps.length > 0) {
                for (let i = 0; i < leg.steps.length; i += Math.floor(leg.steps.length / 4)) {
                    bounds.extend(leg.steps[i].start_location);
                }
            }
            
            map.fitBounds(bounds, { padding: 20 });
            
        } else {
            console.warn('Falha ao calcular rota:', status);
            displayFallbackRoute(map, originLat, originLng, destLat, destLng, routeId);
        }
    });
}

function displayFallbackRoute(map, originLat, originLng, destLat, destLng, routeId) {
    // Linha reta como fallback
    const routePath = new google.maps.Polyline({
        path: [
            { lat: originLat, lng: originLng },
            { lat: destLat, lng: destLng }
        ],
        geodesic: true,
        strokeColor: '#FFCC00',
        strokeOpacity: 0.7,
        strokeWeight: 3,
        strokeDashArray: [5, 5]
    });
    
    routePath.setMap(map);
    
    // Calcular distância aproximada em linha reta
    const distance = google.maps.geometry.spherical.computeDistanceBetween(
        new google.maps.LatLng(originLat, originLng),
        new google.maps.LatLng(destLat, destLng)
    );
    
    const distanceElement = document.getElementById(`distance-${routeId}`);
    if (distanceElement) {
        const distanceKm = (distance / 1000).toFixed(1);
        distanceElement.textContent = `${distanceKm} km (aproximado)`;
    }
    
    // Ajustar zoom para mostrar os dois pontos
    const bounds = new google.maps.LatLngBounds();
    bounds.extend(new google.maps.LatLng(originLat, originLng));
    bounds.extend(new google.maps.LatLng(destLat, destLng));
    map.fitBounds(bounds, { padding: 20 });
}

function showMapError(mapElement, message) {
    mapElement.innerHTML = `
        <div class="map-fallback">
            <p> ${message}</p>
            <small>Tente recarregar a página</small>
        </div>
    `;
}

// Fallback se a API não carregar
setTimeout(() => {
    if (!mapsInitialized) {
        console.log('Google Maps API não carregou dentro do timeout');
        document.querySelectorAll('.route-map').forEach(mapElement => {
            if (!mapElement.querySelector('.map-fallback')) {
                mapElement.innerHTML = `
                    <div class="map-fallback">
                        <p>⚠️ Mapa não carregado</p>
                        <small>Verifique sua conexão com a internet</small>
                        <br>
                        <small>Ou o serviço do Google Maps pode estar indisponível</small>
                    </div>
                `;
            }
        });
    }
}, 10000);

// Tratamento de erro global para Google Maps
window.gm_authFailure = function() {
    console.error('Erro de autenticação do Google Maps');
    document.querySelectorAll('.route-map').forEach(mapElement => {
        showMapError(mapElement, 'Erro de autenticação do Google Maps');
    });
};