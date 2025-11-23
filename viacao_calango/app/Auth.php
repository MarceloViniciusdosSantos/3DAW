<?php
require_once __DIR__.'/User.php';
class Auth {
    public static function attempt($email, $password) {
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    $user = User::findByEmail($email);
    if(!$user) {
        return false;
    }
    
    if(password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        
        // Redirecionamento
        header('Location: index.php');
        exit();
        
        return true;
    }
    
    return false;
}
    
    public static function user() {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if(!empty($_SESSION['user_id'])) {
            return User::findById($_SESSION['user_id']);
        }
        
        return null;
    }
    
    public static function check() {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        return !empty($_SESSION['user_id']);
    }
    
   public static function logout() {
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Limpa todas as variáveis de sessão
    $_SESSION = array();
    
    // Por fim, destrói a sessão
    session_destroy();
}
}
?>