<?php
session_start();
include "User.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $mensagem = excluirUser($id);
    
    // Usar sessão para evitar problemas com URL longa
    $_SESSION['mensagem'] = $mensagem;
    header("Location: listar_user.php");
    exit;
} else {
    $_SESSION['mensagem'] = "ID não especificado";
    header("Location: listar_user.php");
    exit;
}
?>