<?php
require_once __DIR__.'/../database/connection.php';
class Ticket {
    public static function buy($user_id, $route_id, $bus_id, $seat, $price, $seat_type_id) {
        $pdo = DB::pdo();
        
        // Verificar se assento está disponível
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM tickets WHERE bus_id = ? AND seat_number = ? AND status = "paid"');
        $stmt->execute([$bus_id, $seat]);
        if($stmt->fetchColumn() > 0) return false;
        
        // Insere ticket com tipo de assento
        $ins = $pdo->prepare('INSERT INTO tickets (user_id, route_id, bus_id, seat_number, seat_type_id, price, status) VALUES (?,?,?,?,?,?,?)');
        return $ins->execute([$user_id, $route_id, $bus_id, $seat, $seat_type_id, $price, 'paid']);
    }
    
    public static function listByUser($user_id) {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('SELECT t.*, r.origin, r.destination, b.name as bus_name, st.name as seat_type_name FROM tickets t JOIN routes r ON t.route_id = r.id JOIN buses b ON t.bus_id = b.id JOIN seat_types st ON t.seat_type_id = st.id WHERE t.user_id = ? ORDER BY t.created_at DESC');
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }
    
    public static function takenSeats($bus_id) {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('SELECT seat_number FROM tickets WHERE bus_id = ? AND status = "paid"');
        $stmt->execute([$bus_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    public static function getBusInfo($bus_id) {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare('SELECT * FROM buses WHERE id = ?');
        $stmt->execute([$bus_id]);
        return $stmt->fetch();
    }
}
?>