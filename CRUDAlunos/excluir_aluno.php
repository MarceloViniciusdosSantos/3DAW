<?php
include 'CRUDAluno.php';

$aluno = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar'])) {
    $matricula = $_POST['matricula'] ?? '';
    if (!empty($matricula)) {
        global $servidor, $username, $senha, $database;
        $conn = new mysqli($servidor, $username, $senha, $database);
        if(!$conn->connect_error){
            $matricula = $conn->real_escape_string($matricula);
            $comandoSQL = "SELECT * FROM ALUNOS WHERE matricula = '" . $matricula . "'";
            $resultado = $conn->query($comandoSQL);
            if($resultado->num_rows > 0) {
                $aluno = $resultado->fetch_assoc();
            }
            $conn->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Aluno</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        h1, h2 {
            color: #333;
        }
        
        .mensagem {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        
        .erro {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }
        
        .btn:hover {
            background: #0056b3;
            text-decoration: none;
        }
        
        .btn-danger {
            background: #dc3545;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .form-group {
            margin-bottom: 20px;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1em;
            box-sizing: border-box;
        }
        
        input:focus {
            outline: none;
            border-color: #007bff;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .aluno-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #dc3545;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Excluir Aluno</h1>
        <a href="menu.html" class="btn">Menu Principal</a>
    </div>

    <form method="POST">
        <div class="form-group">
            <label for="matricula">Matricula do Aluno:</label>
            <input type="text" id="matricula" name="matricula" required 
                   pattern="[0-9]+" title="Apenas numeros sao permitidos"
                   value="<?php echo isset($_POST['matricula']) ? htmlspecialchars($_POST['matricula']) : ''; ?>">
            <button type="submit" name="buscar" class="btn" style="margin-top: 10px;">Buscar Aluno</button>
        </div>
    </form>

    <?php if ($aluno): ?>
    <form action="CRUDAluno.php" method="POST">
        <input type="hidden" name="acao" value="excluir">
        <input type="hidden" name="matricula" value="<?php echo htmlspecialchars($aluno['matricula']); ?>">
        
        <div class="aluno-info">
            <strong>Aluno encontrado:</strong>
            <div>
                <div><strong>Nome:</strong> <?php echo htmlspecialchars($aluno['nome']); ?></div>
                <div><strong>Email:</strong> <?php echo htmlspecialchars($aluno['email']); ?></div>
            </div>
        </div>

        <button type="submit" class="btn btn-danger" style="width: 100%;">
            Excluir Aluno
        </button>
    </form>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar'])): ?>
        <div class="mensagem erro">Aluno nao encontrado!</div>
    <?php endif; ?>

    <script>
        document.getElementById('matricula').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>