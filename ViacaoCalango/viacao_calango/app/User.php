<?php
require_once __DIR__.'/../database/connection.php';
class User {
    public static function create($name,$email,$password,$phone='') {
        $pdo = DB::pdo();
        $sql = "INSERT INTO users (name,email,password_hash,phone) VALUES (?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT),$phone]);
    }
    
    public static function findByEmail($email) {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    public static function findById($id) {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('SELECT id,name,email,phone,created_at FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
?>