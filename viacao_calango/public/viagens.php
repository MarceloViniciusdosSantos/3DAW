<?php
$script_path = dirname($_SERVER['SCRIPT_NAME']);
$base_path = rtrim($script_path, '/');

$title = 'Escolher Viagem';
require_once __DIR__.'/../database/connection.php';

// Iniciar sessão se não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$pdo = DB::pdo();

// Buscar rotas com JOIN para pegar coordenadas do banco
$routes = $pdo->query('
    SELECT r.*, 
           co.latitude as origin_lat, co.longitude as origin_lng,
           cd.latitude as dest_lat, cd.longitude as dest_lng
    FROM routes r
    LEFT JOIN cities co ON r.origin = co.name
    LEFT JOIN cities cd ON r.destination = cd.name
')->fetchAll();

$seat_types = $pdo->query('SELECT * FROM seat_types ORDER BY multiplier ASC')->fetchAll();

// Função para buscar ou criar coordenadas de uma cidade
function getCityCoordinates($pdo, $cityName) {
    // Primeiro tenta buscar do banco
    $stmt = $pdo->prepare('SELECT latitude, longitude FROM cities WHERE name = ?');
    $stmt->execute([$cityName]);
    $city = $stmt->fetch();
    
    if ($city && $city['latitude'] && $city['longitude']) {
        return ['lat' => (float)$city['latitude'], 'lng' => (float)$city['longitude']];
    }
    
    // Se não encontrou no banco, usa geocoding
    $coords = geocodeCity($cityName);
    
    // Insere no banco para uso futuro 
    try {
        $insert = $pdo->prepare('INSERT IGNORE INTO cities (name, latitude, longitude) VALUES (?, ?, ?)');
        $insert->execute([$cityName, $coords['lat'], $coords['lng']]);
    } catch (Exception $e) {
        // Ignora erro de duplicata
    }
    
    return $coords;
}

// Função de geocoding 
function geocodeCity($cityName) {
    // Cache em sessão para evitar múltiplas requisições
    if (isset($_SESSION['geocache'][$cityName])) {
        return $_SESSION['geocache'][$cityName];
    }
    
    $encoded_city = urlencode($cityName . ', Brasil');
    $url = "https://nominatim.openstreetmap.org/search?format=json&q={$encoded_city}&limit=1";
    
   
    $context = stream_context_create([
        'http' => [
            'header' => "User-Agent: ViacaoApp/1.0 (viacaoclaro@email.com)\r\n",
            'timeout' => 5
        ]
    ]);
    
    try {
        $response = @file_get_contents($url, false, $context);
        
        if ($response !== false) {
            $data = json_decode($response, true);
            
            if (!empty($data) && isset($data[0]['lat']) && isset($data[0]['lon'])) {
                $coords = [
                    'lat' => (float)$data[0]['lat'],
                    'lng' => (float)$data[0]['lon']
                ];
                
                // Salva no cache da sessão
                $_SESSION['geocache'][$cityName] = $coords;
                return $coords;
            }
        }
    } catch (Exception $e) {

    }
    
    // Fallback para cidades conhecidas se a API falhar
    $fallback_coords = [
        'São Paulo' => ['lat' => -23.5505, 'lng' => -46.6333],
        'Rio de Janeiro' => ['lat' => -22.9068, 'lng' => -43.1729],
        'Salvador' => ['lat' => -12.9714, 'lng' => -38.5014],
        'Jericoacoara' => ['lat' => -2.7933, 'lng' => -40.5131],
        'São Luís' => ['lat' => -2.5307, 'lng' => -44.3068],
        'Lençóis Maranhenses' => ['lat' => -2.5167, 'lng' => -43.1667],
        'Bonito' => ['lat' => -21.1300, 'lng' => -56.4822],
        'Campo Grande' => ['lat' => -20.4697, 'lng' => -54.6201],
        'default' => ['lat' => -15.7975, 'lng' => -47.8919] // Brasília como fallback geral
    ];
    
    $coords = $fallback_coords[$cityName] ?? $fallback_coords['default'];
    $_SESSION['geocache'][$cityName] = $coords;
    
    return $coords;
}

// Preparar coordenadas para cada rota
$route_coordinates = [];
foreach ($routes as $route) {
    // Usa coordenadas do banco se disponíveis, senão busca
    $origin_coords = ($route['origin_lat'] !== null && $route['origin_lng'] !== null) 
        ? ['lat' => (float)$route['origin_lat'], 'lng' => (float)$route['origin_lng']]
        : getCityCoordinates($pdo, $route['origin']);
        
    $dest_coords = ($route['dest_lat'] !== null && $route['dest_lng'] !== null)
        ? ['lat' => (float)$route['dest_lat'], 'lng' => (float)$route['dest_lng']]
        : getCityCoordinates($pdo, $route['destination']);
    
    $route_coordinates[$route['id']] = [
        'origin' => $origin_coords,
        'destination' => $dest_coords
    ];
}

include __DIR__.'/views/header.php';
include __DIR__.'/views/viagens.php';
include __DIR__.'/views/footer.php';
?>