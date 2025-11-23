<style>
/* ESTILOS DO FORMULÁRIO - TEMA ESCURO */
.form-container {
    max-width: 450px;
    width: 100%;
    margin: 40px auto;
    padding: 40px;
    background: #000000;
    border-radius: 8px;
    border: 2px solid #D4AF37;
    box-shadow: 0 0 20px rgba(212, 175, 55, 0.3);
}

.form-container h2 {
    text-align: center;
    margin-bottom: 30px;
    color: #FFFFFF;
    font-size: 24px;
    font-weight: 600;
}

.form-container form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-container label {
    display: block;
    font-weight: 600;
    color: #FFFFFF;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-container input {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #D4AF37;
    border-radius: 6px;
    font-size: 15px;
    transition: all 0.3s ease;
    background: #1a1a1a;
    color: #FFFFFF;
    font-family: inherit;
}

.form-container input:focus {
    outline: none;
    border-color: #FFD700;
    background: #2a2a2a;
    box-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
}

.form-container input::placeholder {
    color: #999;
    font-size: 14px;
}

.form-container button {
    background: linear-gradient(135deg, #D4AF37, #FFD700);
    color: #000000;
    padding: 15px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-family: inherit;
    width: 100%;
}

.form-container button:hover {
    background: linear-gradient(135deg, #FFD700, #D4AF37);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4);
}

.form-container button:active {
    transform: translateY(0);
}

.form-message {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 20px;
    font-size: 14px;
    text-align: center;
}

.form-message.error {
    background: rgba(192, 57, 43, 0.2);
    color: #ff6b6b;
    border: 1px solid #c0392b;
}

.form-message.success {
    background: rgba(39, 174, 96, 0.2);
    color: #2ecc71;
    border: 1px solid #27ae60;
}

@media (max-width: 480px) {
    .form-container {
        padding: 30px 25px;
        margin: 20px;
    }
}
</style>

<div class="form-container">
  <h2>Criar conta</h2>
  <?php if(isset($errors)): ?>
    <?php foreach($errors as $err): ?>
      <div class="form-message error"><?php echo htmlspecialchars($err); ?></div>
    <?php endforeach; ?>
  <?php endif; ?>
  
  <form method="post" action="<?php echo $base_path; ?>/cadastro.php">
    <label>Nome</label>
    <input name="name" placeholder="Digite seu nome completo" required/>
    
    <label>CPF ou Email</label>
    <input name="email" type="text" placeholder="seu@email.com ou 000.000.000-00" required/>
    
    <label>Senha</label>
    <input name="password" type="password" placeholder="Mínimo 6 caracteres" required/>
    
    <label>Confirmar senha</label>
    <input name="confirm_password" type="password" placeholder="Digite a senha novamente" required/>
    
    <button type="submit">CADASTRAR-SE</button>
  </form>
</div>