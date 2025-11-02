<?php
$servidor = "localhost";
$username = "root";
$senha = "";
$database = "AV1DAW";

function carregarPerguntas(){
    global $servidor, $username, $senha, $database;
    
    $conn = new mysqli($servidor, $username, $senha, $database);
    if ($conn->connect_error) {
        die("Conexão falhou!");
    }
    
    $comandoSQL = "SELECT * FROM Perguntas";
    $resultado = $conn->query($comandoSQL);
    
    $perguntas = [];
    if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
            if($row['tipo'] == 'mE' && !empty($row['respostas'])) {
                $row['respostas'] = explode(',', $row['respostas']);
            } else {
                $row['respostas'] = [];
            }
            $perguntas[] = $row;
        }
    }
    $conn->close();
    return $perguntas;
}

function criarPerguntaME($pergunta, $respostas, $correta){
    global $servidor, $username, $senha, $database;
    
    $conn = new mysqli($servidor, $username, $senha, $database);
    if ($conn->connect_error) {
        die("Conexão falhou!");
    }
    
    $respostas_str = is_array($respostas) ? implode(',', $respostas) : $respostas;
    
    $pergunta = $conn->real_escape_string($pergunta);
    $respostas_str = $conn->real_escape_string($respostas_str);
    $correta = $conn->real_escape_string($correta);
    
    $comandoSQL = "INSERT INTO Perguntas (tipo, pergunta, respostas, correta) VALUES ('mE', '$pergunta', '$respostas_str', '$correta')";
    $resultado = $conn->query($comandoSQL);
    $conn->close();
    
    if($resultado === true) {
        return "Pergunta de múltipla escolha cadastrada com sucesso";
    } else {
        return "Erro ao cadastrar pergunta: " . $conn->error;
    }
}

function criarPerguntaTexto($pergunta){
    global $servidor, $username, $senha, $database;
    
    $conn = new mysqli($servidor, $username, $senha, $database);
    if ($conn->connect_error) {
        die("Conexão falhou!");
    }
    
    $pergunta = $conn->real_escape_string($pergunta);
    
    $comandoSQL = "INSERT INTO Perguntas (tipo, pergunta) VALUES ('texto', '$pergunta')";
    $resultado = $conn->query($comandoSQL);
    $conn->close();
    
    if($resultado === true) {
        return "Pergunta de texto cadastrada com sucesso";
    } else {
        return "Erro ao cadastrar pergunta: " . $conn->error;
    }
}
function alterarPerguntaMC($id, $pergunta, $respostas, $correta){
    global $servidor, $username, $senha, $database;
    
    $conn = new mysqli($servidor, $username, $senha, $database);
    if ($conn->connect_error) {
        die("Conexão falhou!");
    }
    
    // Verifica se a pergunta é do tipo mE
    $comandoSQL = "SELECT id FROM Perguntas WHERE id = $id AND tipo = 'mE'";
    $resultado = $conn->query($comandoSQL);
    
    if($resultado->num_rows == 0) {
        $conn->close();
        return "Pergunta não encontrada ou tipo incorreto";
    }
    
    $respostas_str = is_array($respostas) ? implode(',', $respostas) : $respostas;
    
    $pergunta = $conn->real_escape_string($pergunta);
    $respostas_str = $conn->real_escape_string($respostas_str);
    $correta = $conn->real_escape_string($correta);
    
    $comandoSQL = "UPDATE Perguntas SET pergunta = '$pergunta', respostas = '$respostas_str', correta = '$correta' WHERE id = $id";
    $resultado = $conn->query($comandoSQL);
    $conn->close();
    
    if($resultado === true) {
        return "Pergunta alterada com sucesso!";
    } else {
        return "Erro ao alterar pergunta";
    }
}

function alterarPerguntaTexto($id, $pergunta){
    global $servidor, $username, $senha, $database;
    
    $conn = new mysqli($servidor, $username, $senha, $database);
    if ($conn->connect_error) {
        die("Conexão falhou!");
    }
    
    // Verifica se a pergunta é do tipo texto
    $comandoSQL = "SELECT id FROM Perguntas WHERE id = $id AND tipo = 'texto'";
    $resultado = $conn->query($comandoSQL);
    
    if($resultado->num_rows == 0) {
        $conn->close();
        return "Pergunta não encontrada ou tipo incorreto";
    }
    
    $pergunta = $conn->real_escape_string($pergunta);
    
    $comandoSQL = "UPDATE Perguntas SET pergunta = '$pergunta' WHERE id = $id";
    $resultado = $conn->query($comandoSQL);
    $conn->close();
    
    if($resultado === true) {
        return "Pergunta alterada com sucesso!";
    } else {
        return "Erro ao alterar pergunta";
    }
}


function excluirPergunta($id){
    global $servidor, $username, $senha, $database;
    
    $conn = new mysqli($servidor, $username, $senha, $database);
    if ($conn->connect_error) {
        die("Conexão falhou!");
    }
    
    $comandoSQL = "DELETE FROM Perguntas WHERE id = $id";
    $resultado = $conn->query($comandoSQL);
       if($resultado === true && $conn->affected_rows > 0) {
        return "Pergunta excluída com sucesso!";
    } else {
        return "Pergunta não encontrada!";
    }
    $conn->close();
    
 
}
?>