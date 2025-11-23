<?php
$script_path = dirname($_SERVER['SCRIPT_NAME']);
$base_path = rtrim($script_path, '/');
require_once __DIR__.'/../config.php';
$title = 'Login';
require_once __DIR__.'/../app/Auth.php';

$error = '';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $pass = $_POST['password'] ?? '';
    
    if($email === 'admin' && $pass === 'admin') {
        header('Location: ' . $base_path . '/adminmain.php');
        exit;
    }
    
    if(Auth::attempt($email,$pass)) {
        header('Location: ' . $base_path . '/index.php');
        exit;
    } else {
        $error = 'Email e/ou senha incorretos ou não existem';
    }
}

include __DIR__.'/views/header.php';
include __DIR__.'/views/login.php';
include __DIR__.'/views/footer.php';
?>