<?php
session_start();
include "Perguntas.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $mensagem = excluirPergunta($id);
    
    // Usar sessão para evitar problemas com URL longa
    $_SESSION['mensagem'] = $mensagem;
    header("Location: listar.php");
    exit;
} else {
    $_SESSION['mensagem'] = "ID não especificado";
    header("Location: listar.php");
    exit;
}
?>