<?php
include "User.php";

$usuarios = carregarUser();
$id = isset($_GET['id']) ? $_GET['id'] : '';

if ($id != '' && isset($usuarios[$id])) {
    $mensagem = excluirUser($usuarios, $id);
} else {
    $mensagem = "Usuário não encontrado!";
}

header("Location: listar_user.php?mensagem=" . urlencode($mensagem));
exit;
?>