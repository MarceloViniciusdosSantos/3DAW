<?php
$script_path = dirname($_SERVER['SCRIPT_NAME']);
$base_path = rtrim($script_path, '/');

$title = 'Perfil';
require_once __DIR__.'/../app/Auth.php';
require_once __DIR__.'/../app/Ticket.php';
require_once __DIR__.'/../database/connection.php';

if(!Auth::check()) { 
    header('Location: ' . $base_path . '/login.php'); 
    exit; 
}

$user = Auth::user();
$tickets = Ticket::listByUser($user['id']);

// Busca informações adicionais do usuário
$pdo = DB::pdo();
$stmt = $pdo->prepare('SELECT created_at, phone FROM users WHERE id = ?');
$stmt->execute([$user['id']]);
$user_details = $stmt->fetch();

// Estatísticas do usuário
$total_tickets = count($tickets);
$total_gasto = array_sum(array_column($tickets, 'price'));

include __DIR__.'/views/header.php';
include __DIR__.'/views/perfil.php';
include __DIR__.'/views/footer.php';
?>