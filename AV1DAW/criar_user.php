<?php
include "User.php";

$usuarios = carregarUser();
$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['id']);
    $nome = trim($_POST['nome']);
    
    if ($id === '' || $nome === '') {
        $mensagem = 'Preencha todos os campos.';
    } else {
        $mensagem = criarUser($usuarios, $id, $nome);
    }
    
    header("Location: listar_user.php?mensagem=" . urlencode($mensagem));
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
    <style>
        .container-table {
            width: 800px;
            margin: 0 auto;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .content-table {
            width: 100%;
            background: white;
            padding: 25px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        .form-table {
            width: 100%;
        }
        .nav-table {
            width: 100%;
            padding: 10px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            margin-bottom: 20px;
        }
        input[type="text"]:focus {
            outline: none;
            border-color: #007bff;
        }
        button {
            background: #007bff;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        .link {
            color: #007bff;
            text-decoration: none;
            margin-right: 15px;
        }
        h1, h2 {
            color: #333;
        }
    </style>
</head>
<body>
    <table class="container-table">
        <tr>
            <td>
                <table class="content-table">
                    <tr>
                        <td>
                            <h2>Cadastrar Usuário</h2>

                            <form method="POST">
                                <table class="form-table">
                                    <tr>
                                        <td>
                                            <label>ID:</label>
                                            <input type="text" name="id" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>Nome:</label>
                                            <input type="text" name="nome" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <button type="submit">Cadastrar</button>
                                        </td>
                                    </tr>
                                </table>
                            </form>

                            <table class="nav-table">
                                <tr>
                                    <td>
                                        <a href="Admin.php" class="link">Voltar ao Menu</a>
                                        <a href="listar_user.php" class="link">Ver Usuários</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>