<?php
$servidor = "localhost";
$username = "root";
$senha = "";
$database = "mat_alunos";

$conn = new mysqli($servidor, $username, $senha, $database);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

function CarregarAlunos() {
    global $servidor, $username, $senha, $database;

    $conn = new mysqli($servidor, $username, $senha, $database);
    if($conn->connect_error){
        die("Falha na conexão! Não foi possível se conectar ao banco de dados");
    }

    $comandoSQL = "SELECT * FROM ALUNOS";
    $resultado = $conn->query($comandoSQL);

    $alunos = [];
    if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
            $alunos[] = $row;
        }
    }
    $conn->close();
    return $alunos;
}

function incluirAluno($nome, $matricula, $email){
    global $servidor, $username, $senha, $database;
    $conn = new mysqli($servidor, $username, $senha, $database);
    if($conn->connect_error){
        die("Falha na conexão! Não foi possível se conectar ao banco de dados");
    }
    
    // Prevenir SQL injection
    $matricula = $conn->real_escape_string($matricula);
    $nome = $conn->real_escape_string($nome);
    $email = $conn->real_escape_string($email);
    
    $comandoSQL = "SELECT matricula FROM ALUNOS WHERE matricula = '" . $matricula . "'";
    $resultado = $conn->query($comandoSQL);

    if($resultado->num_rows > 0) {
        $conn->close();
        return "erro:O aluno com essa matricula ja foi cadastrado";
    } else {
        $comandoSQL = "INSERT INTO ALUNOS (matricula, nome, email) VALUES ('" . $matricula . "', '" . $nome . "', '" . $email . "')";
        $resultado = $conn->query($comandoSQL);
        $conn->close();
        
        if($resultado === true) {
            return "sucesso:Aluno cadastrado com sucesso";
        } else {
            return "erro:Erro ao cadastrar Aluno";
        }
    }
}

function exlucirAluno($matricula){
    global $servidor, $username, $senha, $database;
    $conn = new mysqli($servidor, $username, $senha, $database);
    if($conn->connect_error){
        die("Falha na conexão! Não foi possível se conectar ao banco de dados");
    }
    
    $matricula = $conn->real_escape_string($matricula);
    $comandoSQL = "DELETE FROM ALUNOS WHERE matricula = '" . $matricula . "'";
    $resultado = $conn->query($comandoSQL);
    
    if($resultado === true && $conn->affected_rows > 0) {
        $conn->close();
        return "sucesso:Usuario excluido com sucesso!";
    } else {
        $conn->close();
        return "erro:Usuario nao encontrado!";
    }
}

function alterarAluno($matricula, $novoNome, $novoEmail) {
    global $servidor, $username, $senha, $database;
    $conn = new mysqli($servidor, $username, $senha, $database);
    if($conn->connect_error){
        die("Falha na conexão! Não foi possível se conectar ao banco de dados");
    }

    $matricula = $conn->real_escape_string($matricula);
    $novoNome = $conn->real_escape_string($novoNome);
    $novoEmail = $conn->real_escape_string($novoEmail);

    // Verificar se o aluno existe
    $comandoSQL = "SELECT matricula FROM ALUNOS WHERE matricula = '" . $matricula . "'";
    $resultado = $conn->query($comandoSQL);

    if($resultado->num_rows == 0) {
        $conn->close();
        return "erro:Aluno nao encontrado!";
    } else {
        // Atualizar os dados do aluno
        $comandoSQL = "UPDATE ALUNOS SET nome = '" . $novoNome . "', email = '" . $novoEmail . "' WHERE matricula = '" . $matricula . "'";
        $resultado = $conn->query($comandoSQL);
        $conn->close();
        
        if($resultado === true) {
            return "sucesso:Aluno alterado com sucesso!";
        } else {
            return "erro:Erro ao alterar aluno!";
        }
    }
}

// Processar requisições POST dos formulários
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    
    if ($acao === 'incluir') {
        $nome = $_POST['nome'] ?? '';
        $matricula = $_POST['matricula'] ?? '';
        $email = $_POST['email'] ?? '';
        
        $resultado = incluirAluno($nome, $matricula, $email);
        list($status, $mensagem) = explode(':', $resultado, 2);
        header('Location: menu.html?status=' . $status . '&message=' . urlencode($mensagem));
        exit;
        
    } elseif ($acao === 'alterar') {
        $matricula = $_POST['matricula'] ?? '';
        $novoNome = $_POST['novoNome'] ?? '';
        $novoEmail = $_POST['novoEmail'] ?? '';
        
        $resultado = alterarAluno($matricula, $novoNome, $novoEmail);
        list($status, $mensagem) = explode(':', $resultado, 2);
        header('Location: menu.html?status=' . $status . '&message=' . urlencode($mensagem));
        exit;
        
    } elseif ($acao === 'excluir') {
        $matricula = $_POST['matricula'] ?? '';
        
        $resultado = exlucirAluno($matricula);
        list($status, $mensagem) = explode(':', $resultado, 2);
        header('Location: menu.html?status=' . $status . '&message=' . urlencode($mensagem));
        exit;
    }
}
?>