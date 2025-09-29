<?php
$mensagem = isset($_GET['mensagem']) ? $_GET['mensagem'] : "";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
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
        
        .sucesso {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        ul {
            list-style: none;
            padding: 0;
        }
        
        ul li {
            margin: 10px 0;
            background: white;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        ul li a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            font-size: 1.1em;
        }
        
        ul li a:hover {
            color: #0056b3;
        }
        
        hr {
            margin: 30px 0;
            border: none;
            border-top: 1px solid #ddd;
        }
        
        a {
            color: #007bff;
            text-decoration: none;
        }
        
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1><strong>SISTEMA</h1>

    <?php if ($mensagem): ?>
        <p class="mensagem sucesso"><?php echo htmlspecialchars($mensagem); ?></p>
    <?php endif; ?>

    <h2>Gerenciar Perguntas</h2>
    <ul>
        <li><a href="criar_mc.php">Criar Pergunta de Múltipla Escolha</a></li>
        <li><a href="criar_texto.php">Criar Pergunta de Texto</a></li>
        <li><a href="listar.php">Listar Perguntas</a></li>
    </ul>

    <h2>Gerenciar Usuários</h2>
    <ul>
        <li><a href="criar_user.php">Cadastrar Usuário</a></li>
        <li><a href="listar_user.php">Listar Usuários</a></li>
    </ul>

    <h2>Jogo</h2>
    <ul>
        <li><a href="responder_perguntas.php">Responder Perguntas</a></li>
    </ul>

    <hr>

    <p><a href="index.php">Voltar à Página Inicial</a></p>
</body>
</html>