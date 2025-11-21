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

<!-- Google Maps API com fallback -->
<script>
function loadGoogleMaps() {
    const script = document.createElement('script');
    script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCwxpz7QFYLUai2f2k6uOfD9RXJyjgiHpc&callback=initMaps&libraries=geometry';
    script.async = true;
    script.defer = true;
    script.onerror = function() {
        console.error('Falha ao carregar Google Maps API');
        document.querySelectorAll('.route-map').forEach(mapElement => {
            if (!mapElement.querySelector('.map-fallback')) {
                mapElement.innerHTML = `
                    <div class="map-fallback">
                        <p>⚠️ Falha ao carregar mapas</p>
                        <small>Serviço temporariamente indisponível</small>
                    </div>
                `;
            }
        });
    };
    document.head.appendChild(script);
}

// Carrega o Google Maps quando a página estiver pronta
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadGoogleMaps);
} else {
    loadGoogleMaps();
}
</script>

<!-- Inclui o arquivo JavaScript dos mapas -->
<script src="<?php echo $base_path; ?>/assets/js/maps.js"></script>