<div class="profile-container">
  <div class="profile-header">
    <div class="profile-avatar">
      <div class="avatar-circle">
        <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
      </div>
    </div>
    <div class="profile-info">
      <h1><?php echo htmlspecialchars($user['name']); ?></h1>
      <p class="user-email"><?php echo htmlspecialchars($user['email']); ?></p>
      <div class="profile-stats">
        <div class="stat-item">
          <span class="stat-number"><?php echo $total_tickets; ?></span>
          <span class="stat-label">Passagens</span>
        </div>
        <div class="stat-item">
          <span class="stat-number">R$ <?php echo number_format($total_gasto, 2, ',', '.'); ?></span>
          <span class="stat-label">Total Gasto</span>
        </div>
        <div class="stat-item">
          <span class="stat-number"><?php echo $user_details ? date('m/Y', strtotime($user_details['created_at'])) : '--'; ?></span>
          <span class="stat-label">Membro desde</span>
        </div>
      </div>
    </div>
  </div>

  <?php if(!empty($_GET['bought'])): ?>
    <div class="success-message">
       Passagem comprada com sucesso!
    </div>
  <?php endif; ?>

  <div class="profile-content">
    <div class="info-section">
      <h2>Informações Pessoais</h2>
      <div class="info-grid">
        <div class="info-item">
          <label>Nome Completo</label>
          <div class="info-value"><?php echo htmlspecialchars($user['name']); ?></div>
        </div>
        <div class="info-item">
          <label>Email</label>
          <div class="info-value"><?php echo htmlspecialchars($user['email']); ?></div>
        </div>
        <div class="info-item">
          <label>Telefone</label>
          <div class="info-value">
            <?php echo $user_details && $user_details['phone'] ? htmlspecialchars($user_details['phone']) : '<span class="empty-info">Não informado</span>'; ?>
          </div>
        </div>
        <div class="info-item">
          <label>Data de Cadastro</label>
          <div class="info-value"><?php echo $user_details ? date('d/m/Y H:i', strtotime($user_details['created_at'])) : '--'; ?></div>
        </div>
        <div class="info-item">
          <label>ID do Usuário</label>
          <div class="info-value user-id">#<?php echo $user['id']; ?></div>
        </div>
      </div>
    </div>

    <div class="tickets-section">
      <div class="section-header">
        <h2>Minhas Passagens</h2>
        <span class="ticket-count"><?php echo $total_tickets; ?> passagem<?php echo $total_tickets != 1 ? 's' : ''; ?></span>
      </div>

      <?php if(!$tickets): ?>
        <div class="empty-state">
          <div class="empty-icon">🎫</div>
          <h3>Nenhuma passagem comprada</h3>
          <p>Que tal dar uma olhada nas nossas rotas disponíveis?</p>
          <a href="<?php echo $base_path; ?>/viagens.php" class="btn-primary">Explorar Viagens</a>
        </div>
      <?php else: ?>
        <div class="tickets-list">
          <?php foreach($tickets as $ticket): ?>
            <div class="ticket-card">
              <div class="ticket-header">
                <div class="route-info">
                  <h3><?php echo htmlspecialchars($ticket['origin'] . ' → ' . $ticket['destination']); ?></h3>
                  <span class="ticket-type <?php echo strtolower($ticket['seat_type_name']); ?>">
                    <?php echo htmlspecialchars($ticket['seat_type_name']); ?>
                  </span>
                </div>
                <div class="ticket-price">
                  R$ <?php echo number_format($ticket['price'], 2, ',', '.'); ?>
                </div>
              </div>
              
              <div class="ticket-details">
                <div class="detail-item">
                  <span class="detail-label">Ônibus:</span>
                  <span class="detail-value"><?php echo htmlspecialchars($ticket['bus_name']); ?></span>
                </div>
                <div class="detail-item">
                  <span class="detail-label">Assento:</span>
                  <span class="detail-value seat-number">Nº <?php echo htmlspecialchars($ticket['seat_number']); ?></span>
                </div>
                <div class="detail-item">
                  <span class="detail-label">Data da Compra:</span>
                  <span class="detail-value"><?php echo date('d/m/Y H:i', strtotime($ticket['created_at'])); ?></span>
                </div>
                <div class="detail-item">
                  <span class="detail-label">Status:</span>
                  <span class="status-badge <?php echo $ticket['status']; ?>">
                    <?php 
                    $status_text = [
                      'booked' => 'Reservado',
                      'paid' => 'Pago',
                      'cancelled' => 'Cancelado'
                    ];
                    echo $status_text[$ticket['status']] ?? $ticket['status'];
                    ?>
                  </span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>