<?php
require_once __DIR__.'/../config.php';

$title = 'Início';
require_once __DIR__.'/../database/connection.php';

$base_path = BASE_PATH;

try {
    $pdo = DB::pdo();
    $routes = $pdo->query('SELECT * FROM routes')->fetchAll();
} catch(PDOException $e) {
    echo '<div class="error">Erro ao carregar rotas: ' . htmlspecialchars($e->getMessage()) . '</div>';
    $routes = [];
}

include __DIR__.'/views/header.php';
include __DIR__.'/views/index.php';
include __DIR__.'/views/footer.php';
?>