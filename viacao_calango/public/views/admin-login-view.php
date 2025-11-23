
<style>
/* Reutilizando os mesmos estilos do login padrão */
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

.admin-notice {
    text-align: center;
    margin-top: 20px;
    padding: 15px;
    background: rgba(212, 175, 55, 0.1);
    border: 1px solid #D4AF37;
    border-radius: 6px;
    color: #D4AF37;
    font-size: 14px;
}

.back-link {
    text-align: center;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #333;
}

.back-link a {
    color: #D4AF37;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.back-link a:hover {
    color: #FFD700;
    text-decoration: underline;
}

@media (max-width: 480px) {
    .form-container {
        padding: 30px 25px;
        margin: 20px;
    }
}
</style>

<div class="form-container">
  <h2>Login Administrativo</h2>
  <?php if(isset($error) && $error): ?>
    <div class="form-message error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>
  
  <form method="post" action="<?php echo $base_path; ?>/admin-login.php">
    <label>Usuário</label>
    <input name="username" type="text" placeholder="Digite o usuário admin" required/>
    
    <label>Senha</label>
    <input name="password" type="password" placeholder="Digite a senha admin" required/>
    
    <button type="submit">ACESSAR PAINEL ADMIN</button>
  </form>
  
  <div class="admin-notice">
    <strong>Acesso restrito:</strong> Esta área é exclusiva para administradores do sistema.
  </div>
  
  <div class="back-link">
    <a href="<?php echo $base_path; ?>/login.php">← Voltar para login comum</a>
  </div>
</div>
