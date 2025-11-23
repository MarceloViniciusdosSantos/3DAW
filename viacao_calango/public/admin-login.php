<?php
$script_path = dirname($_SERVER['SCRIPT_NAME']);
$base_path = rtrim($script_path, '/');
require_once __DIR__.'/../config.php';
$title = 'Admin Login';

$error = '';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    
    if($user === 'Admin' && $pass === '123456') {
        header('Location: ' . $base_path . '/adminmain.php');
        exit;
    } else {
        $error = 'Usuário e/ou senha incorretos';
    }
}

include __DIR__.'/views/header.php';
include __DIR__.'/views/admin-login-view.php';
include __DIR__.'/views/footer.php';
?>
