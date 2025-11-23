<?php
require_once __DIR__.'/../config.php';
require_once __DIR__.'/../database/connection.php';
$title = 'Motoristas';

$pdo = DB::pdo();

$drivers = $pdo->query("
    SELECT d.*, b.name AS bus_name
    FROM drivers d
    LEFT JOIN buses b ON d.bus_id = b.id
    ORDER BY d.id DESC
")->fetchAll();

$buses = $pdo->query("SELECT id, name FROM buses ORDER BY name")->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header('Content-Type: application/json; charset=utf-8');
    $action = $_POST["action"] ?? "";

    /* -------- ADICIONAR -------- */
    if ($action === "add_driver") {

        $name   = trim($_POST["name"] ?? "");
        $exp    = (int)($_POST["years_experience"] ?? -1);
        $phone  = trim($_POST["phone"] ?? "");
        $bus_id = $_POST["bus_id"] !== "" ? (int)$_POST["bus_id"] : null;

        if ($name === "" || $exp < 0) {
            echo json_encode(["ok"=>false, "msg"=>"Dados inválidos"]);
            exit;
        }

        if (!isset($_FILES["photo"]) || $_FILES["photo"]["error"] !== 0) {
            echo json_encode(["ok"=>false, "msg"=>"Foto obrigatória"]);
            exit;
        }

        $allowed = ["image/jpeg","image/png","image/webp"];
        if (!in_array($_FILES["photo"]["type"], $allowed)) {
            echo json_encode(["ok"=>false, "msg"=>"Imagem inválida"]);
            exit;
        }

        $ext = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
        $fileName = uniqid("driver_") . "." . $ext;

        $uploadDir = __DIR__ . "/uploads/drivers/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $uploadPath = $uploadDir . $fileName;
        move_uploaded_file($_FILES["photo"]["tmp_name"], $uploadPath);

        $publicPath = "uploads/drivers/" . $fileName;

        $stmt = $pdo->prepare("
            INSERT INTO drivers (name, years_experience, phone, bus_id, photo)
            VALUES (?,?,?,?,?)
        ");
        $stmt->execute([$name,$exp,$phone,$bus_id,$publicPath]);

        $busName = null;
        if ($bus_id) {
            $b = $pdo->prepare("SELECT name FROM buses WHERE id=?");
            $b->execute([$bus_id]);
            $busName = $b->fetchColumn();
        }

        echo json_encode([
            "ok"=>true,
            "bus_name"=>$busName,
            "photo_path"=>$publicPath
        ]);
        exit;
    }

    /* -------- EDITAR -------- */
    if ($action === "edit_driver") {

        $id = (int)($_POST["id"] ?? 0);
        $name   = trim($_POST["name"] ?? "");
        $exp    = (int)($_POST["years_experience"] ?? -1);
        $phone  = trim($_POST["phone"] ?? "");
        $bus_id = $_POST["bus_id"] !== "" ? (int)$_POST["bus_id"] : null;

        if ($id <= 0 || $name === "" || $exp < 0) {
            echo json_encode(["ok"=>false, "msg"=>"Dados inválidos"]);
            exit;
        }

        /* foto só troca se enviada */
        $photoSql = "";
        $photoPath = null;

        if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] === 0) {
            $allowed = ["image/jpeg","image/png","image/webp"];
            if (!in_array($_FILES["photo"]["type"], $allowed)) {
                echo json_encode(["ok"=>false, "msg"=>"Imagem inválida"]);
                exit;
            }

            $ext = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
            $fileName = uniqid("driver_") . "." . $ext;

            $uploadDir = __DIR__ . "/uploads/drivers/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $uploadPath = $uploadDir . $fileName;
            move_uploaded_file($_FILES["photo"]["tmp_name"], $uploadPath);

            $photoPath = "uploads/drivers/" . $fileName;
            $photoSql = ", photo = '$photoPath'";
        }

        $pdo->query("
            UPDATE drivers
            SET name = '$name',
                years_experience = $exp,
                phone = '$phone',
                bus_id = " . ($bus_id ? $bus_id : "NULL") . "
                $photoSql
            WHERE id = $id
        ");

        $busName = null;
        if ($bus_id) {
            $b = $pdo->prepare("SELECT name FROM buses WHERE id=?");
            $b->execute([$bus_id]);
            $busName = $b->fetchColumn();
        }

        echo json_encode([
            "ok"=>true,
            "bus_name"=>$busName,
            "photo_path"=>$photoPath
        ]);
        exit;
    }

    /* -------- EXCLUIR -------- */
    if ($action === "delete_driver") {

        $id = (int)($_POST["id"] ?? 0);
        if ($id <= 0) {
            echo json_encode(["ok"=>false]);
            exit;
        }

        $pdo->prepare("DELETE FROM drivers WHERE id=?")->execute([$id]);

        echo json_encode(["ok"=>true]);
        exit;
    }

    echo json_encode(["ok"=>false, "msg"=>"Ação inválida"]);
    exit;
}

include __DIR__.'/views/adminhead.php';
include __DIR__.'/views/motorista.php';
include __DIR__.'/views/footer.php';