<?php
require_once __DIR__.'/../database/connection.php';
require_once __DIR__.'/../app/Ticket.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Log para debug
error_log("get_seats.php chamado com bus_id: " . ($_GET['bus_id'] ?? 'NULL'));

$bus_id = $_GET['bus_id'] ?? null;

if (!$bus_id || !is_numeric($bus_id)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Bus ID inválido']);
    exit;
}

try {
    // Verificar se o ônibus existe
    $pdo = DB::pdo();
    $stmt = $pdo->prepare('SELECT * FROM buses WHERE id = ?');
    $stmt->execute([$bus_id]);
    $bus = $stmt->fetch();
    
    if (!$bus) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Ônibus não encontrado']);
        exit;
    }
    
    $takenSeats = Ticket::takenSeats($bus_id);
    $totalSeats = $bus['seats'] ?? 40;
    
    // Gerar lista de todos os assentos possíveis
    $allSeats = [];
    for ($i = 1; $i <= $totalSeats; $i++) {
        $allSeats[] = (string)$i;
    }
    
    // Assentos disponíveis
    $availableSeats = array_diff($allSeats, $takenSeats);
    
    $response = [
        'success' => true,
        'availableSeats' => array_values($availableSeats),
        'takenSeats' => array_values($takenSeats),
        'totalSeats' => $totalSeats,
        'bus_id' => (int)$bus_id
    ];
    
    error_log("Resposta get_seats: " . json_encode($response));
    
    echo json_encode($response);
    
} catch (Exception $e) {
    error_log("Erro em get_seats: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
}
?>