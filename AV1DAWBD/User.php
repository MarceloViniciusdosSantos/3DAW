<?php

$servidor = "localhost";
$username = "root";
$senha = "";
$database = "AV1DAW";

 $conn = new mysqli($servidor, $username, $senha, $database);
    if ($conn->connect_error) {
        die("Conexao falhou, avise o administrador do sistema");
    }

function carregarUser() {
    global $servidor, $username, $senha, $database;
    
    $conn = new mysqli($servidor, $username, $senha, $database);
    if ($conn->connect_error) {
        die("Conexao falhou, avise o administrador do sistema");
    }
    
    $comandoSQL = "SELECT * FROM User";
    $resultado = $conn->query($comandoSQL);
    
    $usuarios = [];
    if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
            $usuarios[$row['id']] = $row;
        }
    }
    $conn->close();
    return $usuarios;
}

function salvarUser($user){
    $arq = fopen(Usuarios_FILE, 'w');
    foreach ($user as $usuario) {
        fputcsv($arq, [$usuario['id'], $usuario['nome']]);
    }
    fclose($arq);
}

function criarUser(&$usuario, $id, $nome){
       global $servidor, $username, $senha, $database;
    
    $conn = new mysqli($servidor, $username, $senha, $database);
    if ($conn->connect_error) {
        die("Conexao falhou, avise o administrador do sistema");
    }
    
    $comandoSQL = "SELECT id FROM User WHERE id = '" . $id . "'";
    $resultado = $conn->query($comandoSQL);
    
    if($resultado->num_rows > 0) {
        $conn->close();
        return "O usuario com esse id ja foi cadastrado";
    } else {
        $comandoSQL = "INSERT INTO User (id, nome) VALUES ('" . $id . "', '" . $nome . "')";
        $resultado = $conn->query($comandoSQL);
        $conn->close();
        
        if($resultado === true) {
            return "Usuario cadastrado com sucesso";
        } else {
            return "Erro ao cadastrar usuario";
        }
    }
}

function excluirUser($id){
    global $servidor, $username, $senha, $database;
    
    $conn = new mysqli($servidor, $username, $senha, $database);
    if ($conn->connect_error) {
        die("Conexao falhou, avise o administrador do sistema");
    }
    
    $comandoSQL = "DELETE FROM User WHERE id = '" . $id . "'";
    $resultado = $conn->query($comandoSQL);
    
     if($resultado === true && $conn->affected_rows > 0) {
        return "Usuário excluído com sucesso!";
    } else {
        return "Usuário não encontrado!";
    }
    $conn->close();
    
   
}
?>