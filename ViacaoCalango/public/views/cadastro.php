<div class="box form">
  <h2>Cadastre-se</h2>
  <?php foreach($errors as $err): ?>
    <p class="error"><?php echo htmlspecialchars($err); ?></p>
  <?php endforeach; ?>
  <form method="post" action="<?php echo $base_path; ?>/cadastro.php">
    <label>Nome <input name="name" required/></label>
    <label>Email <input name="email" type="email" required/></label>
    <label>Telefone <input name="phone"/></label>
    <label>Senha <input name="password" type="password" required/></label>
    <button type="submit">Cadastrar</button>
  </form>
</div>