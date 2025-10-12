<?php
include "Perguntas.php";

$perguntas = carregarPerguntas();
$index = isset($_GET['index']) ? intval($_GET['index']) : -1;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($index >= 0 && isset($perguntas[$index])) {
        $mensagem = excluirPergunta($perguntas, $index);
    } else {
        $mensagem = "Pergunta não encontrada!!";
    }
    
    // Para requisições AJAX, apenas retorna a mensagem
    echo $mensagem;
    exit;
}
?>