<?php
include "User.php";

$usuarios = carregarUser();
$id = isset($_GET['id']) ? $_GET['id'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($id != '' && isset($usuarios[$id])) {
        $mensagem = excluirUser($usuarios, $id);
    } else {
        $mensagem = "Usuário não encontrado!";
    }
    

    echo $mensagem;
    exit;
}
?>