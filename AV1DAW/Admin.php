<?php
    include "User.php";
    include "Perguntas.php";

    $usuarios = carregarUser();
    $perguntas = carregarPerguntas();
    $mensagem = '';

    if(isset($_POST['action'])){
        $action = $_POST['action'];

        switch ($action) {
            case 'criar_perguntaME':
            $respostas = array_map('trim', explode(',', $_POST['respostas']));
            $mensagem = criarPerguntaMC($perguntas, $_POST['pergunta'], $respostas, $_POST['correta']);
            break;
                
    case 'criar_perguntaTex':
            $mensagem = criarPerguntaTexto($perguntas, $_POST['pergunta']);
            break;

        case 'alterar_mc':
            $respostas = array_map('trim', explode(',', $_POST['respostas']));
            $mensagem = alterarPerguntaMC($perguntas, $_POST['index'], $_POST['pergunta'], $respostas, $_POST['correta']);
            break;

        case 'alterar_texto':
            $mensagem = alterarPerguntaTexto($perguntas, $_POST['index'], $_POST['pergunta']);
            break;

        case 'excluir':
            $mensagem = excluirPergunta($perguntas, $_POST['index']);
            break;

        case 'listar_uma':
            $idx = $_POST['index'];
            if(isset($perguntas[$idx])) $perguntaSelecionada = $perguntas[$idx];
            else $mensagem = "Pergunta não encontrada";
            break;
        }
          if($action !== 'listar_uma') {
        header("Location: ".$_SERVER['PHP_SELF']."?mensagem=".urlencode($mensagem));
        exit();
    }
    }

  

if(isset($_GET['mensagem'])) $mensagem = $_GET['mensagem'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>CRUD Perguntas</title>
</head>
<body>

<h2>Criar Pergunta MC</h2>
<form method="POST">
    <input type="hidden" name="action" value="criar_perguntaME">
    <label>Pergunta:</label><br><input type="text" name="pergunta" required><br>
    <label>Respostas:</label><br><input type="text" name="respostas" required><br>
    <label>Resposta correta:</label><br><input type="text" name="correta" required><br>
    <button type="submit">Criar MC</button>
</form>

<h2>Criar Pergunta Texto</h2>
<form method="POST">
    <input type="hidden" name="action" value="criar_perguntaTex">
    <label>Pergunta:</label><br><input type="text" name="pergunta" required><br>
    <button type="submit">Criar Texto</button>
</form>

<h2>Lista de Todas as Perguntas</h2>
<?php
if($mensagem) echo "<p><strong>$mensagem</strong></p>";

if($perguntas){
    foreach($perguntas as $i=>$p){
        echo "<p>#{$i} | Tipo: {$p['tipo']} | Pergunta: {$p['pergunta']}";
        if($p['tipo']=='mc'){
            echo " | Respostas: ".implode(', ', $p['respostas']);
            echo " | Correta: {$p['correta']}";
        }
        echo "</p>";

        // Formulário para excluir
        echo '<form method="POST" style="display:inline;">
            <input type="hidden" name="action" value="excluir">
            <input type="hidden" name="index" value="'.$i.'">
            <button type="submit">Excluir</button>
        </form>';

        // Formulário para listar pergunta
        echo '<form method="POST" style="display:inline;">
            <input type="hidden" name="action" value="listar_uma">
            <input type="hidden" name="index" value="'.$i.'">
            <button type="submit">Ver Pergunta</button>
        </form>';

        echo "<hr>";
    }
} else {
    echo "<p>Nenhuma pergunta cadastrada.</p>";
}

// Exibe a pergunta selecionada (listar uma)
if($perguntaSelecionada){
    echo "<h3>Pergunta Selecionada</h3>";
    echo "<p>Tipo: {$perguntaSelecionada['tipo']}<br>";
    echo "Pergunta: {$perguntaSelecionada['pergunta']}<br>";
    if($perguntaSelecionada['tipo']=='mc'){
        echo "Respostas: ".implode(', ', $perguntaSelecionada['respostas'])."<br>";
        echo "Correta: {$perguntaSelecionada['correta']}</p>";
    } else {
        echo "Resposta: {$perguntaSelecionada['correta']}</p>";
    }
}
?>
    </body>
</html>

