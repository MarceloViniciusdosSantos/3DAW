<?php
$script_path = dirname($_SERVER['SCRIPT_NAME']);
$base_path = rtrim($script_path, '/');

$title = 'Comprar Passagem';
require_once __DIR__.'/../app/Auth.php';
require_once __DIR__.'/../database/connection.php';

if(!Auth::check()) { 
    header('Location: ' . $base_path . '/login.php'); 
    exit; 
}

$pdo = DB::pdo();
$route_id = $_GET['route_id'] ?? null;
$seat_type_id = $_GET['seat_type'] ?? null;

// Validações
if(!$route_id || !is_numeric($route_id)) {
    header('Location: ' . $base_path . '/viagens.php');
    exit;
}

if(!$seat_type_id || !is_numeric($seat_type_id)) {
    header('Location: ' . $base_path . '/viagens.php');
    exit;
}

// Buscar dados
$stmt = $pdo->prepare('SELECT * FROM routes WHERE id = ?');
$stmt->execute([$route_id]);
$route = $stmt->fetch();

$stmt = $pdo->prepare('SELECT * FROM seat_types WHERE id = ?');
$stmt->execute([$seat_type_id]);
$seat_type = $stmt->fetch();

if(!$route || !$seat_type) {
    header('Location: ' . $base_path . '/viagens.php');
    exit;
}

// Calcular preço final
$final_price = $route['base_price'] * $seat_type['multiplier'];
$buses = $pdo->query('SELECT * FROM buses')->fetchAll();
$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = Auth::user();
    $route_id = (int)$_POST['route_id'];
    $bus_id = (int)$_POST['bus_id'];
    $seat = trim($_POST['seat']);
    $seat_type_id = (int)$_POST['seat_type_id'];
 
    $final_price = $route_data['base_price'] * $seat_type_data['multiplier'];
    
    if(empty($seat)) {
        $error = 'Por favor, selecione um assento';
    } else {
        require_once __DIR__.'/../app/Ticket.php';
        $ok = Ticket::buy($user['id'], $route_id, $bus_id, $seat, $final_price, $seat_type_id);
        
        if($ok) { 
            header('Location: ' . $base_path . '/perfil.php?bought=1'); 
            exit; 
        } else {
            $error = 'Assento já reservado. Por favor, escolha outro assento.';
        }
    }
}

include __DIR__.'/views/header.php';
include __DIR__.'/views/comprar.php';
include __DIR__.'/views/footer.php';
?>