<?php
include "Perguntas.php";

$perguntas = carregarPerguntas();
$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $respostas = array_map('trim', explode(',', $_POST['respostas']));
    $mensagem = criarPerguntaME($perguntas, $_POST['pergunta'], $respostas, $_POST['correta']);
    header("Location: listar.php?mensagem=".urlencode($mensagem));
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Criar Pergunta MC</title>
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
    </style>
</head>
<body>
    <table class="container-table">
        <tr>
            <td>
                <table class="content-table">
                    <tr>
                        <td>
                            <h2>Criar Pergunta Múltipla Escolha</h2>

                            <form method="POST">
                                <table class="form-table">
                                    <tr>
                                        <td>
                                            <label>Pergunta:</label>
                                            <input type="text" name="pergunta" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>Respostas (separadas por vírgula):</label>
                                            <input type="text" name="respostas" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>Resposta Correta:</label>
                                            <input type="text" name="correta" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <button type="submit">Salvar Pergunta</button>
                                        </td>
                                    </tr>
                                </table>
                            </form>

                            <table class="nav-table">
                                <tr>
                                    <td>
                                        <a href="Admin.php" class="link">Voltar ao Menu</a>
                                        <a href="listar.php" class="link">Ir para Lista de Perguntas</a>
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