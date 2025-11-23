<div class="box">
  <h2>Finalizar Compra</h2>
  
  <div class="purchase-summary">
    <h3>Resumo da Viagem</h3>
    <div class="summary-details">
      <p><strong>Rota:</strong> <?php echo htmlspecialchars($route['origin'] . ' → ' . $route['destination']); ?></p>
      <p><strong>Tipo de Assento:</strong> <?php echo htmlspecialchars($seat_type['name']); ?></p>
      <p><strong>Descrição:</strong> <?php echo htmlspecialchars($seat_type['description']); ?></p>
      <p><strong>Multiplicador:</strong> <?php echo $seat_type['multiplier']; ?>x</p>
      <p><strong>Preço Base:</strong> R$ <?php echo number_format($route['base_price'], 2, ',', '.'); ?></p>
      <p class="final-price"><strong>Preço Final:</strong> R$ <?php echo number_format($final_price, 2, ',', '.'); ?></p>
    </div>
  </div>
  
  <?php if($error): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>
  
  <form method="post" action="<?php echo $base_path; ?>/comprar.php?route_id=<?php echo $route_id; ?>&seat_type=<?php echo $seat_type_id; ?>">
    <input type="hidden" name="route_id" value="<?php echo htmlspecialchars($route_id); ?>"/>
    <input type="hidden" name="seat_type_id" value="<?php echo htmlspecialchars($seat_type_id); ?>"/>
    
    <div class="form-group">
      <label><strong>Selecione o ônibus:</strong>
        <select name="bus_id" id="busSelect" required>
          <option value="">-- Selecione um ônibus --</option>
          <?php foreach($buses as $b): ?>
            <option value="<?php echo $b['id']; ?>">
              <?php echo htmlspecialchars($b['name']); ?> (<?php echo $b['seats']; ?> assentos)
            </option>
          <?php endforeach; ?>
        </select>
      </label>
    </div>
    
    <div class="form-group" id="seatSelection" style="display: none;">
      <label><strong>Selecione seu assento:</strong></label>
      <div id="seatsList" class="seats-list">
        <!-- carrega assentos via JavaScript -->
      </div>
    </div>
    
    <div class="actions">
      <button type="submit" class="btn-primary" id="submitBtn" disabled>Confirmar Compra - R$ <?php echo number_format($final_price, 2, ',', '.'); ?></button>
      <a href="<?php echo $base_path; ?>/viagens.php" class="btn-secondary">Escolher Outra Viagem</a>
    </div>
  </form>
</div>
