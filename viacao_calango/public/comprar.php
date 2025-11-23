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
    $final_price = $route['base_price'] * $seat_type['multiplier']; // ✅ use $route e $seat_type
    
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
?>
 
 <script>
console.log('Script de assentos carregado');

function loadAvailableSeats() {
  console.log('Carregando assentos...');
  
  const busSelect = document.getElementById('busSelect');
  const seatSelection = document.getElementById('seatSelection');
  const seatsList = document.getElementById('seatsList');
  const submitBtn = document.getElementById('submitBtn');
  const busId = busSelect.value;
  
  console.log('Bus ID selecionado:', busId);
  
  if (!busId) {
    seatSelection.style.display = 'none';
    submitBtn.disabled = true;
    seatsList.innerHTML = '';
    return;
  }
  
  // Mostrar seção de assentos com mensagem de carregamento
  seatSelection.style.display = 'block';
  seatsList.innerHTML = '<div class="loading">Carregando assentos disponíveis...</div>';
  submitBtn.disabled = true;
  
  // ✅ URL CORRIGIDA - Relativa
  const url = 'get_seats.php?bus_id=' + busId;
  console.log('Fazendo requisição para:', url);
  
  // Carregar assentos disponíveis via AJAX
  fetch(url)
    .then(response => {
      console.log('Status da resposta:', response.status);
      if (!response.ok) {
        throw new Error('Erro na rede: ' + response.status);
      }
      return response.json();
    })
    .then(data => {
      console.log('Dados recebidos:', data);
      if (data.success) {
        displaySeats(data.availableSeats, data.takenSeats, data.totalSeats);
      } else {
        seatsList.innerHTML = '<div class="error">Erro ao carregar assentos: ' + (data.message || 'Erro desconhecido') + '</div>';
      }
    })
    .catch(error => {
      console.error('Erro no fetch:', error);
      seatsList.innerHTML = '<div class="error">Erro ao carregar assentos: ' + error.message + '</div>';
    });
}

function displaySeats(availableSeats, takenSeats, totalSeats = 40) {
  const seatsList = document.getElementById('seatsList');
  const submitBtn = document.getElementById('submitBtn');
  
  console.log('Display seats - Disponíveis:', availableSeats, 'Ocupados:', takenSeats, 'Total:', totalSeats);
  
  if (!availableSeats || availableSeats.length === 0) {
    seatsList.innerHTML = '<div class="error">Nenhum assento disponível para este ônibus.</div>';
    submitBtn.disabled = true;
    return;
  }
  
  let html = '<div class="seats-container">';
  html += '<div class="seats-header">';
  html += '<span class="available-indicator">● Disponível</span>';
  html += '<span class="taken-indicator">● Ocupado</span>';
  html += '<span class="selected-indicator">● Selecionado</span>';
  html += '</div>';
  html += '<div class="seats-grid">';
  
  for (let i = 1; i <= totalSeats; i++) {
    const seatNumber = i.toString();
    const isAvailable = availableSeats.includes(seatNumber);
    const isTaken = takenSeats.includes(seatNumber);
    
    html += `
      <div class="seat-option">
        <input type="radio" name="seat" value="${seatNumber}" id="seat${seatNumber}" 
               ${isTaken ? 'disabled' : ''}>
        <label for="seat${seatNumber}" class="seat-label ${isTaken ? 'taken' : 'available'}">
          ${seatNumber}
        </label>
      </div>
    `;
  }
  
  html += '</div></div>';
  seatsList.innerHTML = html;
  
  // Adicionar event listeners aos radios
  document.querySelectorAll('input[name="seat"]').forEach(radio => {
    radio.addEventListener('change', updateSeatSelection);
  });
  
  submitBtn.disabled = true;
}

function updateSeatSelection() {
  const submitBtn = document.getElementById('submitBtn');
  const selectedSeat = document.querySelector('input[name="seat"]:checked');
  
  if (selectedSeat && !selectedSeat.disabled) {
    submitBtn.disabled = false;
    console.log('Assento selecionado:', selectedSeat.value);
  } else {
    submitBtn.disabled = true;
  }
}

// Adicionar event listener ao select de ônibus
document.addEventListener('DOMContentLoaded', function() {
  console.log('DOM carregado, adicionando event listeners...');
  const busSelect = document.getElementById('busSelect');
  
  if (busSelect) {
    busSelect.addEventListener('change', loadAvailableSeats);
    console.log('Event listener adicionado ao busSelect');
    
    // Carregar assentos se já houver um ônibus selecionado
    if (busSelect.value) {
      loadAvailableSeats();
    }
  } else {
    console.error('Elemento busSelect não encontrado!');
  }
});
</script>

<?php
include __DIR__.'/views/header.php';
include __DIR__.'/views/comprar.php';
include __DIR__.'/views/footer.php';
?>