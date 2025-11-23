<?php
require_once __DIR__ . '/../app/Auth.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

Auth::logout();
header('Location: index.php');
exit();
?>