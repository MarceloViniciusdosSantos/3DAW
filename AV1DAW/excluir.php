<?php
include "Perguntas.php";

$perguntas = carregarPerguntas();
$index = isset($_GET['index']) ? intval($_GET['index']) : -1;

if ($index >= 0 && isset($perguntas[$index])) {
    $mensagem = excluirPergunta($perguntas, $index);
} else {
    $mensagem = "Pergunta não encontrada!!";
}

header("Location: listar.php?mensagem=" . urlencode($mensagem));
exit;
