<?php
require_once __DIR__.'/../config.php';
require_once __DIR__.'/../database/connection.php';

$title = 'Ônibus';
$pdo = DB::pdo();

$check = $pdo->query("
    SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'buses'
    AND COLUMN_NAME = 'problem'
")->fetchColumn();

if ($check == 0) {
    $pdo->exec("ALTER TABLE buses ADD COLUMN problem TINYINT(1) DEFAULT 0");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header('Content-Type: application/json; charset=utf-8');
    $action = $_POST["action"] ?? "";

    if ($action === "add") {
        $name = trim($_POST["name"] ?? "");
        $seats = (int)($_POST["seats"] ?? 0);

        if ($name === "" || $seats <= 0) {
            echo json_encode(["ok"=>false, "msg"=>"Dados inválidos"]);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO buses (name, seats, problem) VALUES (?,?,0)");
        $stmt->execute([$name,$seats]);
        $id = $pdo->lastInsertId();

        echo json_encode(["ok"=>true,"id"=>$id]);
        exit;
    }

    if ($action === "toggle") {
        $id = (int)($_POST["id"] ?? 0);
        $value = (int)($_POST["value"] ?? 0);

        if ($id <= 0) {
            echo json_encode(["ok"=>false]);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE buses SET problem=? WHERE id=?");
        $stmt->execute([$value,$id]);

        echo json_encode(["ok"=>true]);
        exit;
    }

    if ($action === "delete") {
        $id = (int)($_POST["id"] ?? 0);

        if ($id <= 0) {
            echo json_encode(["ok"=>false]);
            exit;
        }

        $pdo->prepare("DELETE FROM buses WHERE id=?")->execute([$id]);
        echo json_encode(["ok"=>true]);
        exit;
    }

    echo json_encode(["ok"=>false]);
    exit;

    if ($action === 'edit') {
    $id = intval($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $seats = intval($_POST['seats'] ?? 0);

    if ($id <= 0 || $name === '' || $seats <= 0) {
        echo json_encode(['ok'=>false, 'msg'=>'Dados inválidos']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE buses SET name = ?, seats = ? WHERE id = ?");
    $stmt->execute([$name, $seats, $id]);

    echo json_encode(['ok'=>true]);
    exit;
}
}

$stmt = $pdo->prepare("SELECT id,name,seats,problem FROM buses ORDER BY id DESC");
$stmt->execute();
$buses = $stmt->fetchAll();

include __DIR__.'/views/adminhead.php';
include __DIR__.'/views/onibus.php';
include __DIR__.'/views/footer.php';
?>