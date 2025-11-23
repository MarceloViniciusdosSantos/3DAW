<section class="hero">
 <img src="<?php echo $assets_path; ?>/assets/images/banner.png" alt="banner" class="banner"/>
</section>
<section class="promo">
  <div class="promo-box">COMPRE ANTECIPADO E GANHE 10% DE DESCONTO</div>
</section>

<div class="box">
  <h2>Rotas Disponíveis</h2>
  <p>Confira nossas rotas disponíveis e escolha a que melhor atende suas necessidades.</p>
  
  <div class="routes-grid">
    <?php foreach($routes as $r): ?>
      <div class="route-card">
        <img src="<?php echo $assets_path; ?>/assets/images/lista.png" alt="dest"/>
        <h3><?php echo htmlspecialchars($r['destination']); ?></h3>
        <p>Origem: <?php echo htmlspecialchars($r['origin']); ?></p>
        <p>A partir de R$ <?php echo number_format($r['base_price'],2,',','.'); ?></p>
        <a class="btn" href="<?php echo $base_path; ?>/viagens.php">Ver Opções</a>
      </div>
    <?php endforeach; ?>
  </div>
  
  <div style="text-align: center; margin-top: 20px;">
    <a href="<?php echo $base_path; ?>/viagens.php" class="btn-primary">Ver Todas as Opções de Viagem</a>
  </div>
</div>