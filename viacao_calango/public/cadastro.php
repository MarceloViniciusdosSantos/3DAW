<?php
$script_path = dirname($_SERVER['SCRIPT_NAME']);
$base_path = rtrim($script_path, '/');
require_once __DIR__.'/../config.php';
$title = 'Cadastro';
require_once __DIR__.'/../app/User.php';

$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $pass = $_POST['password'] ?? '';
    $phone = $_POST['phone'] ?? '';
    
    if(!$name || !$email || !$pass) $errors[] = 'Preencha todos os campos';
    
    if(empty($errors)) {
        try {
            if(User::findByEmail($email)) {
                $errors[] = 'Email já cadastrado';
            } else {
                User::create($name,$email,$pass,$phone);
                header('Location: ' . $base_path . '/login.php?registered=1'); 
                exit;
            }
        } catch(Exception $e) {
            $errors[] = 'Erro: '.$e->getMessage();
        }
    }
}

include __DIR__.'/views/header.php';
include __DIR__.'/views/cadastro.php';
include __DIR__.'/views/footer.php';
?>