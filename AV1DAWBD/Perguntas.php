<?php
$servidor = "localhost";
$username = "root";
$senha = "";
$database = "AV1DAW";

function carregarPerguntas(){
    global $servidor, $username, $senha, $database;
    
    $conn = new mysqli($servidor, $username, $senha, $database);
    if ($conn->connect_error) {
        die("Conexao falhou, avise o administrador do sistema");
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

function salvarPerguntas($perguntas){

    $arq = fopen(Perguntas_FILE, 'a');
    if(!$arq) return "erro ao abrir o arquivo";

    $ultimaIndex = count($perguntas) - 1;
    $p = $perguntas[$ultimaIndex];
    
    if($p['tipo'] == 'mE'){

        $respostas = is_array($p['respostas']) ? implode(',', $p['respostas']) : '';
        
        $linha = '|mE|'.$p['pergunta'].'|'.$respostas.'|'.$p['correta'];
    } else {
        $linha = '|texto|'.$p['pergunta'];
    }

    fwrite($arq, $linha . PHP_EOL);
    
    fclose($arq);
    return "Pergunta salva com sucesso!";
}

function criarPerguntaME($pergunta, $respostas, $correta){
    global $servidor, $username, $senha, $database;
    
    $conn = new mysqli($servidor, $username, $senha, $database);
    if ($conn->connect_error) {
        die("Conexao falhou, avise o administrador do sistema");
    }
    
    // Converte array de respostas para string separada por vírgulas
    $respostas_str = is_array($respostas) ? implode(',', $respostas) : $respostas;
    
    // Previne SQL injection
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
        die("Conexao falhou, avise o administrador do sistema");
    }
    
    $comandoSQL = "INSERT INTO Perguntas (tipo, pergunta) VALUES ('texto', '$pergunta')";
    $resultado = $conn->query($comandoSQL);
    $conn->close();
    
    if($resultado === true) {
        return "Pergunta de texto cadastrada com sucesso";
    } else {
        return "Erro ao cadastrar pergunta: " . $conn->error;
    }
}


function alterarPerguntaMC(&$perguntas, $index, $pergunta, $respostas, $correta){
    if (!isset($perguntas[$index]) || $perguntas[$index]['tipo'] != 'mE') {
        return "Pergunta não encontrada ou tipo incorreto";
    }
    
    $perguntas[$index] = [
        'tipo' => 'mE', 
        'pergunta' => $pergunta, 
        'respostas'=> $respostas,
        'correta'=> $correta
    ];
    
    return sobrescreverPerguntas($perguntas);
}

function alterarPerguntaTexto(&$perguntas, $index, $pergunta){
    if (!isset($perguntas[$index]) || $perguntas[$index]["tipo"] != "texto") {
        return "Pergunta não encontrada ou tipo errado";
    }
    
    $perguntas[$index] = [
        'tipo' => 'texto', 
        'pergunta' => $pergunta
    ];
    
   
    return sobrescreverPerguntas($perguntas);
}

function excluirPergunta(&$perguntas, $index){
    if(isset($perguntas[$index])){
        unset($perguntas[$index]);
        
        $perguntas = array_values($perguntas);
        
        return sobrescreverPerguntas($perguntas);
    } else {
        return "Pergunta não encontrada!";
    }
}


function sobrescreverPerguntas($perguntas){
    $arq = fopen(Perguntas_FILE, 'w');
    if(!$arq) return "erro ao abrir o arquivo";

    foreach($perguntas as $p){
        if($p['tipo'] == 'mE'){
            $respostas = is_array($p['respostas']) ? implode(',', $p['respostas']) : '';
            $linha = '|mE|'.$p['pergunta'].'|'.$respostas.'|'.$p['correta'];
        } else {
            $linha = '|texto|'.$p['pergunta'];
        }

        fwrite($arq, $linha . PHP_EOL);
    }
    
    fclose($arq);
    return "Operação realizada com sucesso!";
}
?>