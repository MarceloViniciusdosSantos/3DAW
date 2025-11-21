<?php
class DB {
    private static $host = '127.0.0.1';
    private static $db   = 'viacao';
    private static $user = 'root';
    private static $pass = '';

    public static function pdo() {
        static $pdo = null;
        if ($pdo === null) {
            try {
                $dsn = 'mysql:host='.self::$host.';dbname='.self::$db.';charset=utf8mb4';
                $pdo = new PDO($dsn, self::$user, self::$pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch(PDOException $e) {
                // Erro mais específico
                if($e->getCode() == 1049) { // Database não existe
                    die("Erro: Banco de dados 'viacao' não existe. Execute o SQL.sql primeiro.");
                } else if($e->getCode() == 1045) { // Acesso negado
                    die("Erro: Acesso negado. Verifique usuário e senha do MySQL.");
                } else {
                    die("Erro de conexão: " . $e->getMessage());
                }
            }
        }
        return $pdo;
    }
}
?>